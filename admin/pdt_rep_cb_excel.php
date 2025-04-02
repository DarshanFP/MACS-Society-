<?php 
	include("pdt_session.php");	
	$user = $_SESSION['login_user'];
    $cluster = $_POST['cluster'];
    $fdate = $_POST['fdate'];
    $tdate = $_POST['tdate'];
    
    //echo $cluster;
    //echo $today;

    //echo date('d/m/Y', strtotime($date));
    //$delimiter = "----";
    //echo $delimiter;
                
    // retrieve opening cash balance
    $cashfirstob = 0;
 	  if($cluster != 'All'){
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid =  '$cluster' AND date < '$fdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
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
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE date < '$fdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
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
		
    if($cluster != 'All'){
      $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions WHERE clusterid =  '$cluster' AND date BETWEEN '$fdate' AND '$tdate' AND subheadid = SubID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY subheadid,date ");
      $count2 = mysqli_num_rows($sql2);
    }
    else{
      $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions WHERE acc_cashbook.date BETWEEN '$fdate' AND '$tdate' AND subheadid = SubID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY subheadid,date ");
      $count2 = mysqli_num_rows($sql2);  
    }
    
    $output = "";
    $output .= "<table class='table' style='font-family:verdana;'>
                          <tr>
                            <td colspan='8' style='text-align:center; font-size:14px;'><b>Cashbook</b></td>
                          </tr>";

    $output .= "<tr>
                <td colspan='8' style='text-align:center; font-size:15px;'>Statement dated ".date('d/m/Y', strtotime($fdate))." to ".date('d/m/Y', strtotime($tdate))."</td>
                </tr>
                </table>";
    $output .= '<table border="1">	
                        <thead>
                          <tr>
                            <th rowspan="2" style="text-align: center;">Date</th>														
                            <th rowspan="2" style="text-align: center;">Transaction ID</th>
                            <th rowspan="2" style="text-align: center;">Member ID</th>														                            
                            <th rowspan="2" style="text-align: center;">Details</th>																				                                    	
                            <th colspan="2" style="text-align: center;">Cash</th>
                            <th colspan="2" style="text-align: center;">Adjustment</th>														
                          </tr>
                            <th style="text-align: center;">Receipt</th>
                            <th style="text-align: center;">Payment</th>
                            <th style="text-align: center;">Receipt</th>
                            <th style="text-align: center;">Payment</th>
                          
                        </thead>';            
    
    $output .= "<tr>																
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

            $output .= "<tr>";
            $output .= "<td>".date('d-m-Y', strtotime($row2['date']))."</td>";
            $output .= "<td>".$row2['TransID']."</td>";
            if (!empty($row2['memid'])) {
            $memid = $row2['memid'];
            $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
            $row7 = mysqli_fetch_assoc($sql7);
            $output .= "<td>".$memid."</td>";
            $output .= "<td>".$row7['memname']." - ".$row2['details']."</td>";  
            }
            else{
            $output .= "<td></td>";  
            $output .= "<td>".$row2['details']."</td>";      
            }
            
            $output .= "<td align='right'>".$row2['receiptcash']."</td>";
            $output .= "<td align='right'>".$row2['paymentcash']."</td>";

            
            $output .= "<td align='right'>".$row2['receiptadj']."</td>";
            $output .= "<td align='right'>".$row2['paymentadj']."</td>";
            $output .= "</tr>";
            $headingsubhead = $row2['subheadid'];
            $debitcash = $debitcash + $row2['paymentcash'];
            $creditcash = $creditcash + $row2['receiptcash'];
            
            $debitadj = $debitadj + $row2['paymentadj'];
            $creditadj = $creditadj + $row2['receiptadj'];
            
        }
        else {

            $output .= "<tr>";																			
            $output .= "<td colspan='4'><b>".$row2['SubHead']."</b></td>";																			
            $output .= "<td></td>";
            $output .= "<td></td>";
            $output .= "<td></td>";
            $output .= "<td></td>";
            $output .= "</tr>";
            $headingsubhead = $row2['subheadid'];

            $output .= "<tr>";
            $output .= "<td>".date('d-m-Y', strtotime($row2['date']))."</td>";
            $output .= "<td>".$row2['TransID']."</td>";																			
            if (!empty($row2['memid'])) {
            $memid = $row2['memid'];
            $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
            $row7 = mysqli_fetch_assoc($sql7);
            $output .= "<td>".$memid."</td>";
            $output .= "<td>".$row7['memname']." - ".$row2['details']."</td>";  
            }
            else{
            $output .= "<td></td>";  
            $output .= "<td>".$row2['details']."</td>";      
            }
            $output .= "<td align='right'>".$row2['receiptcash']."</td>";
            $output .= "<td align='right'>".$row2['paymentcash']."</td>";

            
            $output .= "<td align='right'>".$row2['receiptadj']."</td>";
            $output .= "<td align='right'>".$row2['paymentadj']."</td>";
            $output .= "</tr>";
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

	$output .= "<tr>																
					<td colspan='4'><b>Closing Balance</b></td>										
          <td></td>
					<td align='right'><b>".number_format($cashcb,2)."</b></td>					
					<td></td>																
					<td></td>																
				</tr>";
	$debitcashcb = $debitcash + $cashcb;
	$output .=	"<tr>																
					<td colspan='4'><b>Grand Total</b></td>
					<td align='right'><b>".number_format($debitcashcb,2)."</b></td>
					<td align='right'><b>".number_format($creditcash,2)."</b></td>
					<td align='right'><b>".number_format($debitadj,2)."</b></td>
					<td align='right'><b>".number_format($creditadj,2)."</b></td>
				</tr>"; 
							  
    $output .='</tbody></table>';	
    header("Content-Type: application/xls, true");
    header("Content-Disposition:attachment; filename=cashbook.xls");
    echo $output;

 	mysqli_close($connection);
?>