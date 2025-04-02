<?php 
	  include("pdt_session.php");	
		$office = $_POST['cluster'];
		$fromdate = $_POST['fdate'];
 		$todate = $_POST['tdate'];
		$user = $_SESSION['login_user'];

    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];

    $sql9 = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID = '$office'");
    $row9 = mysqli_fetch_assoc($sql9);
    if($office == 'All'){
      $clustername = 'All Clusters';
    }
    else{
      $clustername = $row9['ClusterName'];
    } 

		
		$cashfirstob = 0;					
		// retrieve opening cash balance
 		
 	  if($office != 'All'){
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid =  '$office' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
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
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
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
      $sql2 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash), sum(receiptadj), sum(paymentadj), SubHead, MajorHead FROM acc_cashbook, acc_subhead, acc_transactions, acc_majorheads WHERE clusterid =  '$office' AND date >=  '$fromdate' AND date <=  '$todate'  AND subheadid = SubID AND acc_subhead.MajorID = acc_majorheads.MajorID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid ");
      $count2 = mysqli_num_rows($sql2);
    }
    else{
      $sql2 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash), sum(receiptadj), sum(paymentadj), SubHead, MajorHead FROM acc_cashbook, acc_subhead, acc_transactions, acc_majorheads WHERE date >=  '$fromdate' AND date <=  '$todate' AND subheadid = SubID AND acc_subhead.MajorID = acc_majorheads.MajorID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid ");
      $count2 = mysqli_num_rows($sql2);  
    }
 		

    $output = "";
    $output .= "<table class='table' style='font-family:verdana;'>
                          <tr>
                            <td colspan='9' style='text-align:center; font-size:16px;'><b>Mother Teresa MACS,Vijayawada</b></td>
                          </tr>
                          <tr>
                            <td colspan='9' style='text-align:center; font-size:14px;'><b>Receipts & Payments : ".$clustername."</b></td>
                          </tr>";

                          $output .= "<tr>
                            <td colspan='9' style='text-align:center; font-size:15px;'>Statement dated ".date('d/m/Y', strtotime($fromdate))." to ".date('d/m/Y', strtotime($todate))."</td>
                          </tr>
                          </table>";
                          $output .='<table border="1" class="table table-bordered table-hover">	
                          <thead>
                          <tr>														
                            <th style="text-align: center;">Sl.No</th>														
                            <th colspan = "4" style="text-align: center;">Particulars</th>														                            
                            <th colspan = "2" style="text-align: center;">Receipts</th>																				                                    	
                            <th colspan = "2" style="text-align: center;">Payments</th>                            
                          </tr>
                          </thead><tbody>';
    
		$output .= "<tr>		
    <td></td>
		<td colspan = '4'><b>Opening Balance</b></td>		
		<td colspan = '2' align='right'><b>".number_format((float)$cashob, 2, '.', '')."</b></td>		
		<td colspan = '2'></td>				
		</tr>";
		$totreceipt = 0;
    $totpayment = 0;
    $slno = 1;
		if($count2>0){
      $majorhead = '';
			while($row2 = mysqli_fetch_assoc($sql2)){
        if($majorhead != $row2['MajorHead']){
          $output .= "<tr>
                <td></td>
                <td colspan = '4'><b>".$row2['MajorHead']."</b></td>
                <td colspan = '2'></td>
                <td colspan = '2'></td>
                </tr>";
        }
			  $receipt = $row2['sum(receiptcash)'] + $row2['sum(receiptadj)'];
        $payment = $row2['sum(paymentcash)'] + $row2['sum(paymentadj)'];
        $output .= "<tr>
                <td align='center'>".$slno."</td>
                <td colspan = '4'>".$row2['SubHead']."</td>
                <td colspan = '2' align='right'>".number_format((float)$receipt, 2, '.', '')."</td>
                <td colspan = '2' align='right'>".number_format((float)$payment, 2, '.', '')."</td>
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

	$output .= "<tr>						
          <td></td>
					<td colspan = '4'><b>Closing Balance</b></td>										
          <td colspan = '2'></td>
					<td colspan = '2' align='right'><b>".number_format((float)$cashcb, 2, '.', '')."</b></td>
				</tr>";

  $output .= "<tr>			
          <td></td>
					<td colspan = '4'><b>Grand Total</b></td>										
          <td colspan = '2' align='right'><b>".number_format((float)$grandreceipttotal, 2, '.', '')."</b></td>
					<td colspan = '2' align='right'><b>".number_format((float)$grandpaymenttotal, 2, '.', '')."</b></td>
				</tr>";
							  
			$output .='</tbody></table>';	
			header("Content-Type: application/xls, true");
			header("Content-Disposition:attachment; filename=receipts_payments.xls");
			echo $output;

 	mysqli_close($connection);



?>