<?php 
    include('accounts_session.php');
		$user = $_SESSION['login_user'];

    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];


        $group = $_POST['group'];
		$tdate = $_POST['tdate'];
 		$fdate = $_POST['fdate'];

        echo $group;
        echo $today;

		echo date('d/m/Y', strtotime($date));
		$delimiter = "----";
		echo $delimiter;
					
		// retrieve opening cash balance
 		$cashfirstob = 0;
 	  if($group != 'All'){
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE groupid =  '$group' AND date < '$fdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY groupid");
      $cou = mysqli_num_rows($sql1);
      if ($cou >0){
        $cash = mysqli_fetch_assoc($sql1);
        $cashreceipt = $cash['sum(receiptcash)'];
        $cashpayment = $cash['sum(paymentcash)'];
        $cashob = $cashfirstob + $cashreceipt - $cashpayment;
      }
      else{
        $cashob = $cashfirstob;
      }
    }
    else{
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$fdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
      $cou = mysqli_num_rows($sql1);
      if ($cou >0){
        $cash = mysqli_fetch_assoc($sql1);
        $cashreceipt = $cash['sum(receiptcash)'];
        $cashpayment = $cash['sum(paymentcash)'];
        $cashob = $cashfirstob + $cashreceipt - $cashpayment;
      }
      else{
        $cashob = $cashfirstob;
      }  
    }
		
    if($group != 'All'){
      $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions WHERE groupid =  '$group' AND date BETWEEN '$fdate' AND '$tdate' AND subheadid = SubID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY subheadid,date ");
      $count2 = mysqli_num_rows($sql2);
    }
    else{
      $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions WHERE acc_cashbook.clusterid = '$clusterid' AND acc_cashbook.date BETWEEN '$fdate' AND '$tdate' AND subheadid = SubID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY subheadid,date ");
      $count2 = mysqli_num_rows($sql2);  
    }
    
		echo "<tr>																
		<td colspan='4'><b>Opening Balance</b></td>		
		<td align='right'><b>".number_format($cashob,2)."</b></td>		
    <td></td>
		<td></td>		
		<td></td>																
		</tr>";

		$debitcash = 0.00;
		$debitadj = 0.00;
		$creditcash = 0.00;
		$creditadj = 0.00;
		$cashcb = 0.00;
    if($count2>0){
        $headingsubhead = 0;


    while($row2 = mysqli_fetch_assoc($sql2)){
        if($headingsubhead == $row2['subheadid']){

            echo "<tr>";
            echo "<td>".date('d-m-Y', strtotime($row2['date']))."</td>";
            echo "<td>".$row2['TransID']."</td>";
            if (!empty($row2['memid'])) {
            $memid = $row2['memid'];
            $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
            $row7 = mysqli_fetch_assoc($sql7);
            echo "<td>".$memid."</td>";
            echo "<td>".$row7['memname']." - ".$row2['details']."</td>";  
            }
            else{
            echo "<td></td>";  
            echo "<td>".$row2['details']."</td>";      
            }
            
            echo "<td align='right'>".number_format($row2['receiptcash'],2)."</td>";
            echo "<td align='right'>".number_format($row2['paymentcash'],2)."</td>";

            
            echo "<td align='right'>".number_format($row2['receiptadj'],2)."</td>";
            echo "<td align='right'>".number_format($row2['paymentadj'],2)."</td>";
            echo "</tr>";
            $headingsubhead = $row2['subheadid'];
            $debitcash = $debitcash + $row2['paymentcash'];
            $creditcash = $creditcash + $row2['receiptcash'];
            
            $debitadj = $debitadj + $row2['paymentadj'];
            $creditadj = $creditadj + $row2['receiptadj'];
            
        }
        else {

            echo "<tr>";																			
            echo "<td colspan='4'><b>".$row2['SubHead']."</b></td>";																			
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "</tr>";
            $headingsubhead = $row2['subheadid'];

            echo "<tr>";
            echo "<td>".date('d-m-Y', strtotime($row2['date']))."</td>";
            echo "<td>".$row2['TransID']."</td>";																			
            if (!empty($row2['memid'])) {
            $memid = $row2['memid'];
            $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
            $row7 = mysqli_fetch_assoc($sql7);
            echo "<td>".$memid."</td>";
            echo "<td>".$row7['memname']." - ".$row2['details']."</td>";  
            }
            else{
            echo "<td></td>";  
            echo "<td>".$row2['details']."</td>";      
            }
            echo "<td align='right'>".number_format($row2['receiptcash'],2)."</td>";
            echo "<td align='right'>".number_format($row2['paymentcash'],2)."</td>";

            
            echo "<td align='right'>".number_format($row2['receiptadj'],2)."</td>";
            echo "<td align='right'>".number_format($row2['paymentadj'],2)."</td>";
            echo "</tr>";
            $headingsubhead = $row2['subheadid'];
            $debitcash = $debitcash + $row2['paymentcash'];
            $creditcash = $creditcash + $row2['receiptcash'];
            $debitadj = $debitadj + $row2['paymentadj'];
            $creditadj = $creditadj + $row2['receiptadj'];																																						
        }
    }
    }
	$creditcash = $cashob + $creditcash;
	$cashcb = $creditcash - $debitcash;
	$grandpaymentadj = $grandreceiptcash + $sumreceiptadj;

	echo "<tr>																
					<td colspan='4'><b>Closing Balance</b></td>										
          <td></td>
					<td align='right'><b>".number_format($cashcb,2)."</b></td>					
					<td></td>																
					<td></td>																
				</tr>";
	$debitcashcb = $debitcash + $cashcb;
	echo	"<tr>																
					<td colspan='4'><b>Grand Total</b></td>
					<td align='right'><b>".number_format($debitcashcb,2)."</b></td>
					<td align='right'><b>".number_format($creditcash,2)."</b></td>
					<td align='right'><b>".number_format($debitadj,2)."</b></td>
					<td align='right'><b>".number_format($creditadj,2)."</b></td>
				</tr>";
?>