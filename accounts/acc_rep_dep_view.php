<?php	    
	include("accounts_session.php");
    $user = $_SESSION['login_user'];
    $depid = $_POST['depaccno'];
    $fromdate = $_POST['fdate'];
    $todate = $_POST['tdate'];
	
        
    $subidquery = mysqli_query($connection,"SELECT subheadid FROM acc_deposits WHERE depositno = '$depid'");
    $subidfetch = mysqli_fetch_assoc($subidquery);
    $subid = $subidfetch['subheadid'];

    $obquery = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$depid' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = acc_subhead.SubID AND date < '$fromdate' GROUP BY accno"); 
    $obfetch = mysqli_fetch_assoc($obquery);
    $rcash = $obfetch['rcash'];
    $radj = $obfetch['radj'];
    $pcash = $obfetch['pcash'];
    $padj = $obfetch['padj'];
    $ob =  $rcash + $radj - $pcash - $padj;
    $receipttotal = 0;
    $paymenttotal = 0;
    
    $finalq = mysqli_query($connection,"SELECT A.*, B.* FROM (SELECT acc_cashbook.TransID, date, receiptcash, receiptadj, paymentcash, paymentadj, remarks, details, acc_subhead.SubHeadModule  FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$depid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid = '$subid' AND date BETWEEN '$fromdate' AND '$todate' AND acc_cashbook.subheadid = acc_subhead.SubID ORDER BY date) AS A LEFT JOIN  
            (SELECT acc_cashbook.TransID as rtransid, date as rdate, receiptcash as rcash, receiptadj as radj, paymentcash as pcash, paymentadj as padj, remarks as rremarks, details as rdetails, acc_subhead.SubHeadModule  FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$depid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid != '$subid' AND date BETWEEN '$fromdate' AND '$todate' AND acc_cashbook.subheadid = acc_subhead.SubID ORDER BY date) AS B ON A.TransID = B.rtransid
            UNION
            SELECT A.*, B.* FROM (SELECT acc_cashbook.TransID, date, receiptcash, receiptadj, paymentcash, paymentadj, remarks, details, acc_subhead.SubHeadModule  FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$depid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid = '$subid' AND date BETWEEN '$fromdate' AND '$todate' AND acc_cashbook.subheadid = acc_subhead.SubID ORDER BY date) AS A RIGHT JOIN  
            (SELECT acc_cashbook.TransID as rtransid, date as rdate, receiptcash as rcash, receiptadj as radj, paymentcash as pcash, paymentadj as padj, remarks as rremarks, details as rdetails, acc_subhead.SubHeadModule  FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$depid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid != '$subid' AND date BETWEEN '$fromdate' AND '$todate' AND acc_cashbook.subheadid = acc_subhead.SubID ORDER BY date) AS B ON A.TransID = B.rtransid");
    $count = mysqli_num_rows($finalq);    
    
    
    

    if($count>0){
        $slno = 1;                  
                                                                                                                                                            
            while($row4 = mysqli_fetch_assoc($finalq)){                                                                                 
                $recept = $row4['receiptcash']+$row4['receiptadj'];
                $payment = $row4['paymentcash']+$row4['paymentadj'];
                $cb = $ob + $recept - $payment;
                $paymentint = $row4['pcash']+$row4['padj'];
                echo "<tr><td class='center'>".$slno."</td>";
                if(is_null($row4['TransID'])){
                    echo "<td class='center'>".$row4['rtransid']."</td>";
                    echo "<td class='center'>".date('d-m-Y',strtotime($row4['rdate']))."</td>";
                    echo "<td class='center'>".$row4['rdetails']."-".$row4['rremarks']."</td>";
                }
                else{                                                                                         
                    echo "<td class='center'>".$row4['TransID']."</td>";
                    echo "<td class='center'>".date('d-m-Y',strtotime($row4['date']))."</td>";
                    echo "<td class='center'>".$row4['details']."-".$row4['remarks']."</td>";                                                                                        
                }
                echo "<td align='right'>".number_format($recept,2)."</td>";
                echo "<td align='right'>".number_format($payment,2)."</td>";
                echo "<td align='right'>".number_format($cb,2)."</td>";
                echo "<td align='right'>".number_format($paymentint,2)."</td></tr>";
                $ob = $cb;
                $slno = $slno +1;                                                                                    
                $receipttotal = $receipttotal + $recept;
                $paymenttotal = $paymenttotal + $payment;
            }
            echo "<tr><td colspan='4' class='center'><b>Total</td>";
            echo "<td align='right'><b>".number_format($receipttotal,2)."</td>";
            echo "<td align='right'><b>".number_format($paymenttotal,2)."</td>";
            echo "<td></td><td></td></tr>";
        }                                                      

    ?>

			