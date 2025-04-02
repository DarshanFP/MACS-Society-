<?php 
	  include("pdt_session.php");	
		$user = $_SESSION['login_user'];
    $clusterid = $_POST['cluster'];
    $today = $_POST['sdate'];
 		$date = $_POST['sdate'];
		
		//echo date('d/m/Y', strtotime($date));
		//echo $clusterid;
    //$delimiter = "----";
		//echo $delimiter;
					
		// retrieve opening cash balance
 		
 	  $cashfirstob = 0;

    if($clusterid != 'C000'){     	
        $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid =  '$clusterid' AND date < '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
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
    
        $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions 
                                          WHERE clusterid =  '$clusterid' AND DATE =  '$today' AND subheadid = SubID 
                                          AND acc_cashbook.TransID = acc_transactions.TransID 
                                          AND acc_transactions.TransStatus	= 1 
                                           ORDER BY subheadid ");
        $count2 = mysqli_num_rows($sql2);
        
        $sql4 = mysqli_query($connection,"SELECT ClusterName from cluster WHERE ClusterID = '$clusterid'");
        $row4 = mysqli_fetch_assoc($sql4);
        $clustername = $row4['ClusterName'];
    }
    else{
        $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE date < '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
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
    
        $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions 
                                          WHERE DATE =  '$today' AND subheadid = SubID 
                                          AND acc_cashbook.TransID = acc_transactions.TransID 
                                          AND acc_transactions.TransStatus	= 1 
                                           ORDER BY subheadid ");
        $count2 = mysqli_num_rows($sql2);
      
        $sql4 = mysqli_query($connection,"SELECT ClusterName from cluster WHERE ClusterID = '$clusterid'");
        $row4 = mysqli_fetch_assoc($sql4);
        $clustername = $row4['ClusterName']; 
    }
			                    $output = "";
                          $output .= "<table class='table' style='font-family:verdana;'>
                          <tr>
                            <td colspan='6' style='text-align:center; font-size:16px;'><b>Mother Teresa MACS,Vijayawada</b></td>
                          </tr>
                          <tr>
                            <td colspan='6' style='text-align:center; font-size:14px;'><b>Cash Book : ".$clustername."</b></td>
                          </tr>";

                          $output .= "<tr>
                            <td colspan='6' style='text-align:center; font-size:15px;'>Statement dated ".date('d/m/Y', strtotime($date))."</td>
                          </tr>
                          </table>";
                          $output .='<table border="1" class="table table-bordered table-hover">	
                          <thead>
                          <tr>														
                            <th rowspan="2" style="text-align: center;">Transaction ID</th>														                            
                            <th rowspan="2" style="text-align: center;">Details</th>																				                                    	
                            <th colspan="2" style="text-align: center;">Cash</th>
                            <th colspan="2" style="text-align: center;">Adjustment</th>														
                          </tr>
                            <th style="text-align: center;">Receipt</th>
                            <th style="text-align: center;">Payment</th>
                            <th style="text-align: center;">Receipt</th>
                            <th style="text-align: center;">Payment</th>
                          <tr>
                          </tr>
                        </thead><tbody>';
                          $output .= '<tr>																
                                  <td colspan="2"><b>Opening Balance</b></td>                                
                                  <td align="right"><b>';
                          $output .= number_format($cashob,2);
                          $output .= '</b></td>																                                																
                                  <td></td>
                                  <td></td>
                                  <td></td>																
                                </tr>';
                                  $debitcash = 0.00;
                                  $creditcash = 0.00;
                                  $debitadj = 0.00;
                                  $creditadj = 0.00;
                                  $cashcb = 0.00;
                                if($count2>0){
                                  $headingsubhead = 0;


                                  while($row2 = mysqli_fetch_assoc($sql2)){
                                    if($headingsubhead == $row2['subheadid']){

                                      $output .= "<tr>";
                                      $output .= "<td>".$row2['TransID']."</td>";
                                      if (!empty($row2['memid'])) {
                                        $memid = $row2['memid'];
                                        $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
                                        $row7 = mysqli_fetch_assoc($sql7);                                        
                                        $output .= "<td>".$row7['memname']." - ".$row2['details']."</td>";  
                                      }
                                      else{                                        
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
                                      $output .= "<td colspan='6'><b>".$row2['SubHead']."</b></td>";																			                                      
                                      //$output .= "<td></td>";
                                      //$output .= "<td></td>";
                                      //$output .= "<td></td>";
                                      $output .= "</tr>";
                                      $headingsubhead = $row2['subheadid'];

                                      $output .= "<tr>";
                                      $output .= "<td>".$row2['TransID']."</td>";																			
                                      if (!empty($row2['memid'])) {
                                        $memid = $row2['memid'];
                                        $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
                                        $row7 = mysqli_fetch_assoc($sql7);
                                        
                                        $output .= "<td>".$row7['memname']." - ".$row2['details']."</td>";  
                                      }
                                      else{
                                        
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
                               
                              $output .= "<tr>																
                                <td colspan='2'><b>Closing Balance</b></td>																
                                <td></td>
                                <td align='right'><b>";
                              $output .= number_format($cashcb,2);
                              $output .= "</b></td>																                                
                                <td></td>																
                                <td></td>																
                              </tr>
                              <tr>																
                                <td colspan='2'><b>Grand Total</b></td>
                                <td align='right'><b>";
                              $output .= number_format($debitcash+$cashcb,2); 
                              $output .= "</b></td>
                                <td align='right'><b>";
                              $output .= number_format($creditcash,2); 
                              $output .= "</b></td>
                                <td align='right'><b>";
                              $output .= number_format($creditadj,2); 
                              $output .= "</b></td>
                                <td align='right'><b>";
                              $output .= number_format($debitadj,2); 
                              $output .= "</b></td>              
                              </tr>";
							  
			$output .='</tbody></table>';	
			header("Content-Type: application/xls, true");
			header("Content-Disposition:attachment; filename=cashbookreport.xls");
			echo $output;

 	mysqli_close($connection);



?>