<?php	    
	include("accounts_session.php");
    $user = $_SESSION['login_user'];
    $memid = $_POST['depaccno'];
    $fromdate = $_POST['fdate'];
    $todate = $_POST['tdate'];

    $subidquery = mysqli_query($connection,"SELECT subheadid FROM acc_sharecapital WHERE memid = '$memid'");
    $subidfetch = mysqli_fetch_assoc($subidquery);
    $subid = $subidfetch['subheadid'];

    $finalq = mysqli_query($connection,"SELECT acc_cashbook.TransID, date, receiptcash, receiptadj, paymentcash, paymentadj, remarks, details, acc_subhead.SubHeadModule  FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$memid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid = '$subid' AND date BETWEEN '$fromdate' AND '$todate' AND acc_cashbook.subheadid = acc_subhead.SubID ORDER BY date");
    $count = mysqli_num_rows($finalq); 

    $obquery = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions, acc_subhead WHERE accno = '$memid' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = acc_subhead.SubID AND date < '$fromdate' GROUP BY accno"); 
    $obfetch = mysqli_fetch_assoc($obquery);
    $rcash = $obfetch['rcash'];
    $radj = $obfetch['radj'];
    $pcash = $obfetch['pcash'];
    $padj = $obfetch['padj'];
    $ob =  $rcash + $radj - $pcash - $padj;
    echo $ob."*";
    $receipttotal = 0;
    $paymenttotal = 0;
    
       
    echo $memfetch;
    if($count>0){
        $slno = 1;                  
                                                                                                                                                            
            while($row4 = mysqli_fetch_assoc($finalq)){                                                                                 
                $receipt = $row4['receiptcash']+$row4['receiptadj'];
                $payment = $row4['paymentcash']+$row4['paymentadj'];
                $cb = $ob + $recept - $payment;                
                echo "<tr><td class='center'>".$slno."</td>";                
                
                echo "<td class='center'>".$row4['TransID']."</td>";
                echo "<td class='center'>".date('d-m-Y',strtotime($row4['date']))."</td>";
                echo "<td class='center'>".$row4['details']."-".$row4['remarks']."</td>";                                                                                        
            
                echo "<td align='right'>".number_format($receipt,2)."</td>";
                echo "<td align='right'>".number_format($payment,2)."</td>";
                echo "<td align='right'>".number_format($cb,2)."</td></tr>";
                
                $ob = $cb;
                $receipttotal = $receipttotal + $receipt;
                $paymenttotal = $paymenttotal + $payment;
                $slno = $slno +1;                                                                                    
            }
            echo "<tr><td colspan='4' class='center'><b>Total</td>";
            echo "<td align='right'><b>".number_format($receipttotal,2)."</td>";
            echo "<td align='right'><b>".number_format($paymenttotal,2)."</td>";
            echo "<td></td></tr>";
        }                                                      

    ?>

			