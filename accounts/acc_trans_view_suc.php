<?php	    
	include("accounts_session.php");
    $_SESSION['curpage']="accounts_trans";
    $user = $_SESSION['login_user'];

    $sql1 = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $row1 = mysqli_fetch_assoc($sql1);

    $clusterid = $row1['ClusterID'];
	
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $transid = $_POST['transid'];
        //echo $transid;       
        $cancelcheck = mysqli_query($connection, "SELECT CancelStatus FROM acc_transactions WHERE TransID = '$transid'");
        $rowcheck = mysqli_fetch_assoc($cancelcheck);
        $check = $rowcheck['CancelStatus'];        
        echo $check;
        echo "|";
        $sql5 = mysqli_query($connection,"SELECT acc_cashbook.*,CancelStatus FROM acc_cashbook,acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND TransStatus = 1 AND acc_cashbook.TransID = '$transid' AND clusterid = '$clusterid'");
                    
        $sno = 1;
        while($row5=mysqli_fetch_assoc($sql5)){
            echo "<tr>";
            echo "<td align='center'>".$sno."</td>";
            echo "<td align='center'>".$transid."</td>";
            echo "<td align='center'>".date('d-m-Y',strtotime($row5['date']))."</td>";
            $subheadid = $row5['subheadid'];
            $memid = $row5['memid'];
            $sql61 = mysqli_query($connection,"SELECT SubHead FROM acc_subhead WHERE SubID = '$subheadid'");
            $row61 = mysqli_fetch_assoc($sql61);
            $subhead = $row61['SubHead'];
            $sql62 = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$memid'");
            $row62 = mysqli_fetch_assoc($sql62);
            $memname = $row62['memname'];
            echo "<td>".$memname."</td>";
            echo "<td align='center'>".$subhead."</td>";
            echo "<td align='center'>".$row5['accno']."</td>";
            echo "<td>".$row5['details']."</td>";            
            $receipt = $row5['receiptcash']+$row5['receiptadj'];
            $payment = $row5['paymentcash']+$row5['paymentadj'];
            echo "<td align='center'>".$receipt."</td>";            
            echo "<td align='center'>".$payment."</td>";            
            echo "</tr>";               
            $sno++;  
            
        }
        
    }     
?>    
                                                
    
