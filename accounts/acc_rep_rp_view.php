<?php     
    include("accounts_session.php");
		
    $office = $_POST['group'];
    $fromdate = $_POST['fdate'];
    $todate = $_POST['tdate'];
    $user = $_SESSION['login_user'];

    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];

		
    $delimiter = "----";
    echo $delimiter;
    $cashfirstob = 0;					
		// retrieve opening cash balance
 		
 	  if($office != 'All'){
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE groupid =  '$office' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY groupid");
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
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
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
		
    if($office != 'All'){
      $sql2 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash), sum(receiptadj), sum(paymentadj), SubHead, MajorHead FROM acc_cashbook, acc_subhead, acc_transactions, acc_majorheads WHERE groupid =  '$office' AND date >=  '$fromdate' AND date <=  '$todate'  AND subheadid = SubID AND acc_subhead.MajorID = acc_majorheads.MajorID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY MajorHead, subheadid ");
      $count2 = mysqli_num_rows($sql2);
    }
    else{
      $sql2 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash), sum(receiptadj), sum(paymentadj), SubHead, MajorHead FROM acc_cashbook, acc_subhead, acc_transactions, acc_majorheads WHERE clusterid =  '$clusterid' AND date >=  '$fromdate' AND date <=  '$todate' AND subheadid = SubID AND acc_subhead.MajorID = acc_majorheads.MajorID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY MajorHead,subheadid ");
      $count2 = mysqli_num_rows($sql2);  
    }
		echo "<tr>		
    <td></td>
		<td><b>Opening Balance</b></td>		
		<td align='right'><b>".number_format((float)$cashob, 2, '.', '')."</b></td>		
		<td></td>				
		</tr>";
		$totreceipt = 0;
    $totpayment = 0;
    $slno = 1;
		if($count2>0){
      $majorhead = '';
			while($row2 = mysqli_fetch_assoc($sql2)){
        if($majorhead != $row2['MajorHead']){
          echo "<tr>
                <td></td>
                <td><b>".$row2['MajorHead']."</b></td>
                <td></td>
                <td></td>
                </tr>";
        }
			  $receipt = $row2['sum(receiptcash)'] + $row2['sum(receiptadj)'];
        $payment = $row2['sum(paymentcash)'] + $row2['sum(paymentadj)'];
        echo "<tr>
                <td align='center'>".$slno."</td>
                <td>".$row2['SubHead']."</td>
                <td align='right'>".number_format((float)$receipt, 2, '.', '')."</td>
                <td align='right'>".number_format((float)$payment, 2, '.', '')."</td>
                </tr>";             
        $totreceipt = $totreceipt + $receipt;
        $totpayment = $totpayment + $payment;
        $slno = $slno + 1;
        $majorhead = $row2['MajorHead'];
		  }
	  }
	$cashcb = $cashob + $totreceipt - $totpayment;
  $grandreceipttotal = $cashob + $totreceipt;
  $grandpaymenttotal = $cashcb + $totpayment;

	echo "<tr>						
          <td></td>
					<td><b>Closing Balance</b></td>										
          <td></td>
					<td align='right'><b>".number_format((float)$cashcb, 2, '.', '')."</b></td>
				</tr>";

echo "<tr>			
          <td></td>
					<td><b>Grand Total</b></td>										
          <td align='right'><b>".number_format((float)$grandreceipttotal, 2, '.', '')."</b></td>
					<td align='right'><b>".number_format((float)$grandpaymenttotal, 2, '.', '')."</b></td>
				</tr>";
	


?>