<?php 
	include("pdt_session.php");
	$user = $_SESSION['login_user'];
    if($_SERVER['REQUEST_METHOD']=='POST'){	
        $cluster = $_POST['cluster'];
        $month = $_POST['month'];
        $year = $_POST['year'];

        $month1 = $year.'-'.$month;       
        $obdate = $year.'-'.$month.'-01';        

               
    $sql = "SELECT A.*, AOB.*, B.*, BOB.*, C.*, COB.*, D.*, DOB.*, F.* FROM 
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as GSReceipt, IFNULL(cashbook.payment,0) as GSPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 2 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as A,                     
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as GSOBReceipt, IFNULL(cashbook.payment,0) as GSOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 2 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as AOB,                     
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as SSReceipt, IFNULL(cashbook.payment,0) as SSPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 3 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as B,
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as SSOBReceipt, IFNULL(cashbook.payment,0) as SSOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 3 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as BOB,
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as MSReceipt, IFNULL(cashbook.payment,0) as MSPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 4 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as C,
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as MSOBReceipt, IFNULL(cashbook.payment,0) as MSOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 4 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as COB,
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as GLReceipt, IFNULL(cashbook.payment,0) as GLPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 5 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as D,
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as GLOBReceipt, IFNULL(cashbook.payment,0) as GLOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 5 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as DOB,                    
        (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as GLInterest from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 6 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as F
        WHERE B.GroupID = A.GroupID AND A.GroupID = AOB.GroupID AND B.GroupID = C.GroupID AND B.GroupID = BOB.GroupID AND C.GroupID = COB.GroupID AND C.GroupID = D.GroupID AND D.GroupID = DOB.GroupID AND D.GroupID = F.GroupID";
    $result = mysqli_query($connection, $sql);
    $count = mysqli_num_rows($result);
    
    $sql1 = "SELECT G.*, H.*, I.*, J.*, K.* FROM
                (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as AppReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 11 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as G,
                (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as DocReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 12 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as H,
                (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as MFeReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 15 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as I,
                (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as StaReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 16 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as J,
                (SELECT groups.GroupID, groups.GroupName, IFNULL(cashbook.receipt,0) as MutReceipt, cashbook.payment as MutPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 10 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as K
            WHERE G.GroupID = H.GroupID AND H.GroupID = I.GroupID AND I.GroupID = J.GroupID AND J.GroupID = K.GroupID";
    $result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
        
    

                    
       echo "1|";
        $totgsreceipt = 0;
        $totgspayment = 0;
        $totgsclosing = 0;
        $totssreceipt = 0;
        $totsspayment = 0;
        $totssclosing = 0;
        $totmsreceipt = 0;
        $totmspayment = 0;
        $totmsclosing = 0;
        $totglreceipt = 0;
        $totglpayment = 0;
        $totglclosing = 0;
        $totglinterest = 0;
        while ($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td align='center'><a href='acc_rep_group_ledger_view_dup.php?group=".$row['GroupID']."&&month=".$month."&&year=".$year."'>".$row['GroupID']."</a></td>";   
            echo "<td>".$row['GroupName']."</td>";
            $gsclosing = $row['GSOBReceipt'] - $row['GSOBPayment'] + $row['GSReceipt'] - $row['GSPayment'];
            echo "<td align='right'>".$row['GSReceipt']."</td>";
            echo "<td align='right'>".$row['GSPayment']."</td>";
            echo "<td align='right'>".number_format($gsclosing,2)."</td>";
            $ssclosing = $row['SSOBReceipt'] - $row['SSOBPayment'] + $row['SSReceipt'] - $row['SSPayment'];
            echo "<td align='right'>".$row['SSReceipt']."</td>";
            echo "<td align='right'>".$row['SSPayment']."</td>";
            echo "<td align='right'>".number_format($ssclosing,2)."</td>";
            $msclosing = $row['MSOBReceipt'] - $row['MSOBPayment'] + $row['MSReceipt'] - $row['MSPayment'];
            echo "<td align='right'>".$row['MSReceipt']."</td>";
            echo "<td align='right'>".$row['MSPayment']."</td>";
            echo "<td align='right'>".number_format($msclosing,2)."</td>";
            $glclosing = $row['GLOBPayment'] - $row['GLOBReceipt'] + $row['GLPayment'] - $row['GLReceipt'];
            echo "<td align='right'>".$row['GLReceipt']."</td>";
            echo "<td align='right'>".$row['GLPayment']."</td>";
            echo "<td align='right'>".number_format($glclosing,2)."</td>";
            echo "<td align='right'>".$row['GLInterest']."</td>";                                    
            echo "</tr>";
            $totgsreceipt = $totgsreceipt + $row['GSReceipt'];
            $totgspayment = $totgspayment + $row['GSPayment'];
            $totgsclosing = $totgsclosing + $gsclosing;
            $totssreceipt = $totssreceipt + $row['SSReceipt'];
            $totsspayment = $totsspayment + $row['SSPayment'];
            $totssclosing = $totssclosing + $ssclosing;
            $totmsreceipt = $totmsreceipt + $row['MSReceipt'];
            $totmspayment = $totmspayment + $row['MSPayment'];
            $totmsclosing = $totmsclosing + $msclosing;
            $totglreceipt = $totglreceipt + $row['GLReceipt'];
            $totglpayment = $totglpayment + $row['GLPayment'];
            $totglclosing = $totglclosing + $glclosing;
            $totglinterest = $totglinterest + $row['GLInterest'];
        }     
        
        echo "<tr>";
        echo "<td align='center'></td>";   
        echo "<td><b>Total</td>";        
        echo "<td align='right'><b>".number_format($totgsreceipt,2)."</td>";
        echo "<td align='right'><b>".number_format($totgspayment,2)."</td>";
        echo "<td align='right'><b>".number_format($totgsclosing,2)."</td>";        
        echo "<td align='right'><b>".number_format($totssreceipt,2)."</td>";
        echo "<td align='right'><b>".number_format($totsspayment,2)."</td>";
        echo "<td align='right'><b>".number_format($totssclosing,2)."</td>";
        echo "<td align='right'><b>".number_format($totmsreceipt,2)."</td>";
        echo "<td align='right'><b>".number_format($totmspayment,2)."</td>";
        echo "<td align='right'><b>".number_format($totmsclosing,2)."</td>";        
        echo "<td align='right'><b>".number_format($totglreceipt,2)."</td>";
        echo "<td align='right'><b>".number_format($totglpayment,2)."</td>";
        echo "<td align='right'><b>".number_format($totglclosing,2)."</td>";
        echo "<td align='right'><b>".number_format($totglinterest,2)."</td>";
        echo "</b></tr>";


        echo "|";
        $totmutreceipt = 0;
        $totmutpayment = 0;
        $totappreceipt = 0;
        $totdocreceipt = 0;
        $totmfereceipt = 0;
        $totstareceipt = 0;
        while
        ($row1 = mysqli_fetch_assoc($result1)){
            echo "<tr>";
            echo "<td align='center'><a href='acc_rep_group_ledger_view_dup.php?group=".$row1['GroupID']."&&month=".$month."&&year=".$year."'>".$row1['GroupID']."</a></td>";   
            echo "<td>".$row['GroupName']."</td>";            
            echo "<td align='right'>".$row1['MutReceipt']."</td>";
            echo "<td align='right'>".$row1['MutPayment']."</td>";
            echo "<td align='right'>".$row1['AppReceipt']."</td>";
            echo "<td align='right'>".$row1['DocReceipt']."</td>";
            echo "<td align='right'>".$row1['MFeReceipt']."</td>";
            echo "<td align='right'>".$row1['StaReceipt']."</td>";
            
            echo "</tr>";
            $totmutreceipt = $totmutreceipt + $row1['MutReceipt'];
            $totmutpayment = $totmutpayment + $row1['MutPayment'];
            $totappreceipt = $totappreceipt + $row1['AppReceipt'];
            $totdocreceipt = $totdocreceipt + $row1['DocReceipt'];
            $totmfereceipt = $totmfereceipt + $row1['MFeReceipt'];
            $totstareceipt = $totstareceipt + $row1['StaReceipt'];
        }        
        echo "<tr>";
            echo "<td align='center'></td>";   
            echo "<td><b>Total</td>";            
            echo "<td align='right'><b>".number_format($totmutreceipt,2)."</td>";
            echo "<td align='right'><b>".number_format($totmutpayment,2)."</td>";
            echo "<td align='right'><b>".number_format($totappreceipt,2)."</td>";
            echo "<td align='right'><b>".number_format($totdocreceipt,2)."</td>";
            echo "<td align='right'><b>".number_format($totmfereceipt,2)."</td>";
            echo "<td align='right'><b>".number_format($totstareceipt,2)."</td>";            
            echo "</tr>";
    }
?>