<?php 
	include("pdt_session.php");
	$user = $_SESSION['login_user'];
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $cluster = $_POST['cluster'];
        $group = $_POST['group'];
        $month = $_POST['month'];
        $year = $_POST['year'];
           
        $month1 = $year.'-'.$month;       
        $obdate = $year.'-'.$month.'-01';
        if($cluster == 'All'){
            $sql = "SELECT A.*, AOB.*, B.*, BOB.*, C.*, COB.*, D.*, DOB.*, F.* FROM 
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GSReceipt, IFNULL(cashbook.payment,0) as GSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 2 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as A,                     
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GSOBReceipt, IFNULL(cashbook.payment,0) as GSOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 2 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as AOB,                     
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as SSReceipt, IFNULL(cashbook.payment,0) as SSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 3 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as B,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as SSOBReceipt, IFNULL(cashbook.payment,0) as SSOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 3 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as BOB,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MSReceipt, IFNULL(cashbook.payment,0) as MSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 4 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as C,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MSOBReceipt, IFNULL(cashbook.payment,0) as MSOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 4 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as COB,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GLReceipt, IFNULL(cashbook.payment,0) as GLPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 5 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as D,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GLOBReceipt, IFNULL(cashbook.payment,0) as GLOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 5 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as DOB,                    
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GLInterest from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 6 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as F
                    

                    WHERE B.memid = A.memid AND A.memid = AOB.memid AND B.memid = C.memid AND B.memid = BOB.memid AND C.memid = COB.memid AND C.memid = D.memid AND D.memid = DOB.memid AND D.memid = F.memid";
        $result = mysqli_query($connection, $sql);
        $count = mysqli_num_rows($result);

        $sql1 = "SELECT G.*, H.*, I.*, J.*, K.* FROM
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as AppReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 11 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as G,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as DocReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 12 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as H,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MFeReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 15 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as I,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as StaReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 16 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as J,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MutReceipt, IFNULL(cashbook.payment,0) as MutPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransID = 1 AND acc_cashbook.subheadid = 10 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid  GROUP BY memid) as K

                    WHERE G.memid = H.memid AND H.memid = I.memid AND I.memid = J.memid AND J.memid = K.memid";
        $result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        
        echo $cluster;
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
            echo "<td align='center'><a href='acc_mem_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";   
            echo "<td>".$row['memname']."</td>";
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
        ($row = mysqli_fetch_assoc($result1)){
            echo "<tr>";
            echo "<td align='center'>".$row['memid']."</td>";   
            echo "<td>".$row['memname']."</td>";            
            echo "<td align='right'>".$row['MutReceipt']."</td>";
            echo "<td align='right'>".$row['MutPayment']."</td>";
            echo "<td align='right'>".$row['AppReceipt']."</td>";
            echo "<td align='right'>".$row['DocReceipt']."</td>";
            echo "<td align='right'>".$row['MFeReceipt']."</td>";
            echo "<td align='right'>".$row['StaReceipt']."</td>";
            
            echo "</tr>";
            $totmutreceipt = $totmutreceipt + $row['MutReceipt'];
            $totmutpayment = $totmutpayment + $row['MutPayment'];
            $totappreceipt = $totappreceipt + $row['AppReceipt'];
            $totdocreceipt = $totdocreceipt + $row['DocReceipt'];
            $totmfereceipt = $totmfereceipt + $row['MFeReceipt'];
            $totstareceipt = $totstareceipt + $row['StaReceipt'];
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
        else{
            echo $cluster;
            echo "else";
            $sql = "SELECT A.*, AOB.*, B.*, BOB.*, C.*, COB.*, D.*, DOB.*, F.* FROM 
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GSReceipt, IFNULL(cashbook.payment,0) as GSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 2 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as A,                     
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GSOBReceipt, IFNULL(cashbook.payment,0) as GSOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 2 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as AOB,                     
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as SSReceipt, IFNULL(cashbook.payment,0) as SSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 3 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as B,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as SSOBReceipt, IFNULL(cashbook.payment,0) as SSOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 3 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as BOB,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MSReceipt, IFNULL(cashbook.payment,0) as MSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 4 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as C,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MSOBReceipt, IFNULL(cashbook.payment,0) as MSOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 4 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as COB,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GLReceipt, IFNULL(cashbook.payment,0) as GLPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 5 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as D,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GLOBReceipt, IFNULL(cashbook.payment,0) as GLOBPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 5 and date < '$obdate' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as DOB,                    
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as GLInterest from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 6 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as F
                    

                    WHERE B.memid = A.memid AND A.memid = AOB.memid AND B.memid = C.memid AND B.memid = BOB.memid AND C.memid = COB.memid AND C.memid = D.memid AND D.memid = DOB.memid AND D.memid = F.memid";
        $result = mysqli_query($connection, $sql);
        $count = mysqli_num_rows($result);

        $sql1 = "SELECT G.*, H.*, I.*, J.*, K.* FROM
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as AppReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 11 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as G,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as DocReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 12 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as H,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MFeReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 15 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as I,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as StaReceipt from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 16 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as J,
                    (SELECT members.memid, members.memname, IFNULL(cashbook.receipt,0) as MutReceipt, IFNULL(cashbook.payment,0) as MutPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransID = 1 AND acc_cashbook.groupid = '$group' and acc_cashbook.subheadid = 10 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$group' GROUP BY memid) as K

                    WHERE G.memid = H.memid AND H.memid = I.memid AND I.memid = J.memid AND J.memid = K.memid";
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
            echo "<td align='center'><a href='acc_mem_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";   
            echo "<td>".$row['memname']."</td>";
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
        ($row = mysqli_fetch_assoc($result1)){
            echo "<tr>";
            echo "<td align='center'>".$row['memid']."</td>";   
            echo "<td>".$row['memname']."</td>";            
            echo "<td align='right'>".$row['MutReceipt']."</td>";
            echo "<td align='right'>".$row['MutPayment']."</td>";
            echo "<td align='right'>".$row['AppReceipt']."</td>";
            echo "<td align='right'>".$row['DocReceipt']."</td>";
            echo "<td align='right'>".$row['MFeReceipt']."</td>";
            echo "<td align='right'>".$row['StaReceipt']."</td>";
            
            echo "</tr>";
            $totmutreceipt = $totmutreceipt + $row['MutReceipt'];
            $totmutpayment = $totmutpayment + $row['MutPayment'];
            $totappreceipt = $totappreceipt + $row['AppReceipt'];
            $totdocreceipt = $totdocreceipt + $row['DocReceipt'];
            $totmfereceipt = $totmfereceipt + $row['MFeReceipt'];
            $totstareceipt = $totstareceipt + $row['StaReceipt'];
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
    }
?>