<?php	    
	include("accounts_session.php");
    $user = $_SESSION['login_user'];
    $duesid = $_POST['duesid'];
    $fromdate = $_POST['fdate'];
    $todate = $_POST['tdate'];
	
    $queryob = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions
                            WHERE accno = '$duesid'  AND date < '$fromdate' AND  acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid = 16 GROUP BY accno" ); 
    $fetchob = mysqli_fetch_assoc($queryob);
    $ob =  $fetchob['rcash'] + $fetchob['radj'] -  $fetchob['padj'] - $fetchob['pcash'];

    $finalquery = mysqli_query($connection,"SELECT * FROM acc_cashbook, acc_transactions
                            WHERE accno = '$duesid'  AND  acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND subheadid = 16 AND date BETWEEN '$fromdate' AND '$todate' ORDER BY date"); 
                
    $count = mysqli_num_rows($finalquery);

    $receipttotal = 0;
    $paymenttotal = 0;

	if($count>0){
    $slno = 1;                      
        while($row4 = mysqli_fetch_assoc($finalquery)){
            $receipt = $row4['receiptcash'] + $row4['receiptadj'];
            $payment = $row4['paymentcash'] + $row4['paymentadj'];
            $cb = $ob - $payment + $receipt;
            echo "<tr><td class='center'>".$slno."</td>";
            echo "<td class='center'>".$row4['TransID']."</td>";
            echo "<td class='center'>".date('d-m-Y',strtotime($row4['date']))."</td>";
            echo "<td align = 'right'>".$row4['details']."-".$row4['remarks']."</td>";
            echo "<td align = 'right'>".number_format($receipt,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($payment,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($cb,2,'.','')."</td></tr>";
            
            $slno = $slno + 1;
            $ob = $cb;
            $receipttotal = $receipttotal + $receipt;
            $paymenttotal = $paymenttotal + $payment;
        }
        echo "<tr><td colspan='4' class='center'><b>Total</td>";
        echo "<td align='right'><b>".number_format($receipttotal,2)."</td>";
        echo "<td align='right'><b>".number_format($paymenttotal,2)."</td>";
        echo "<td></td></tr>";
    }                                        
                                                                        
																			
																		
			