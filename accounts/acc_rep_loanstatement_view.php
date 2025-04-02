<?php 
	include("accounts_session.php");
	$user = $_SESSION['login_user'];
    if($_SERVER['REQUEST_METHOD']=='POST'){	
        $group = $_POST['group'];
        $month = $_POST['month'];
        $year = $_POST['year'];

        $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
        $clus = mysqli_fetch_assoc($cluster);

        $clusterid = $clus['ClusterID'];
           
        $month1 = $year.'-'.$month;       
        $obdate = $year.'-'.$month.'-01';
        if($group != 'All'){
            $sql = "SELECT * FROM (SELECT members.memid, members.memname, groups.GroupName, acc_cashbook.TransID, date, accno, sum(receiptcash) as creceipt, sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                FROM acc_cashbook, acc_transactions, members, groups WHERE groups.GroupID = acc_cashbook.groupid AND members.memid = acc_cashbook.memid and acc_cashbook.TransID = acc_transactions.TransID and acc_transactions.TransStatus = 1 AND subheadid = 5 and LEFT(acc_cashbook.date,7) ='$month1' AND acc_cashbook.groupid = '$group' GROUP BY acc_transactions.TransID) AS A WHERE apayment > 0 || cpayment > 0";
            
        }
        else{
            $sql = "SELECT * FROM (SELECT members.memid, members.memname, groups.GroupName, acc_cashbook.TransID, date, accno, sum(receiptcash) as creceipt, sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                FROM acc_cashbook, acc_transactions, members, groups WHERE groups.GroupID = acc_cashbook.groupid AND acc_cashbook.clusterid = '$clusterid' AND members.memid = acc_cashbook.memid and acc_cashbook.TransID = acc_transactions.TransID and acc_transactions.TransStatus = 1 AND subheadid = 5 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_transactions.TransID) AS A WHERE apayment > 0 || cpayment > 0 ORDER BY GroupName";
        }
        $result = mysqli_query($connection, $sql);
        echo $count = mysqli_num_rows($result);
        

       echo "1|";
        $totalissued = 0;
        $throughbank = 0;
        $throughcash = 0;
        while ($row = mysqli_fetch_assoc($result)){
            $transid = $row['TransID'];
            $bankquery = mysqli_query($connection,"SELECT SubHead FROM acc_cashbook, acc_subhead WHERE acc_cashbook.subheadid = acc_subhead.SubID AND TransID = '$transid' AND subheadid NOT IN (5,6)");
            $bankfetch = mysqli_fetch_assoc($bankquery);
            $bankname = $bankfetch['SubHead'];
            echo "<tr>";
            echo "<td>".$row['GroupName']."</td>";   
            echo "<td align='center'>".$row['memid']."</td>";   
            echo "<td>".$row['memname']."</td>";            
            echo "<td align='center'>".$row['TransID']."</td>";
            echo "<td align='center'>".$row['date']."</td>";
            $prevloan = $row['creceipt'] + $row['areceipt'];
            $loanissued =  $row['cpayment'] + $row['apayment'] - $row['creceipt'] - $row['areceipt'];
            $totalloan = $prevloan + $loanissued;
            echo "<td align='right'>".number_format($prevloan,2)."</td>";
            echo "<td align='right'>".number_format($loanissued,2)."</td>";
            echo "<td align='right'>".number_format($totalloan,2)."</td>";
            echo "<td>".$bankname."</td>";
            $bankloan = $row['apayment'] - $row['areceipt'];
            $cashloan = $row['cpayment'] - $row['creceipt'];
            echo "<td align='right'>".number_format($bankloan,2)."</td>";
            echo "<td align='right'>".number_format($cashloan,2)."</td>";            
            echo "</tr>";
            $totalissued = $totalissued + $loanissued;
            $throughbank = $throughbank + $bankloan;
            $throughcash = $throughcash + $cashloan;            
        }     
        echo "<tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td><b>Total</td>";
        echo "<td></td>";
        echo "<td align='right'><b>".number_format($totalissued,2)."</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td align='right'><b>".number_format($throughbank,2)."</td>";
        echo "<td align='right'><b>".number_format($throughcash,2)."</td>";
        echo "</b></tr>";
    }
?>