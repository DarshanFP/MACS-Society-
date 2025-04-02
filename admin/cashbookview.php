<?php 
	  include("pdt_session.php");	
		$user = $_SESSION['login_user'];
    $clusterid = $_POST['cluster'];
    $today = $_POST['date'];
 		$date = $_POST['date'];
		
		//echo date('d/m/Y', strtotime($date));
		echo $clusterid;
    $delimiter = "----";
		//echo $delimiter;
					
		// retrieve opening cash balance
 		
 	  $cashfirstob = 0;

    if($clusterid != 'All'){     	
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
    }
    else{
        $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE date < '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
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
      
    }
			
                          echo '<tr>																
                                  <td colspan="2"><b>Opening Balance</b></td>                                
                                  <td align="right"><b>';
                          echo number_format($cashob,2);
                          echo '</b></td>																                                																
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

                                      echo "<tr>";
                                      echo "<td>".$row2['TransID']."</td>";
                                      if (!empty($row2['memid'])) {
                                        $memid = $row2['memid'];
                                        $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
                                        $row7 = mysqli_fetch_assoc($sql7);                                        
                                        echo "<td>".$row7['memname']." - ".$row2['details']."</td>";  
                                      }
                                      else{                                        
                                        echo "<td>".$row2['details']."</td>";      
                                      }
                                      
                                      echo "<td align='right'>".$row2['receiptcash']."</td>";
                                      echo "<td align='right'>".$row2['paymentcash']."</td>";

                                      
                                      echo "<td align='right'>".$row2['receiptadj']."</td>";
                                      echo "<td align='right'>".$row2['paymentadj']."</td>";
                                      echo "</tr>";
                                      $headingsubhead = $row2['subheadid'];
                                      $debitcash = $debitcash + $row2['paymentcash'];
                                      $creditcash = $creditcash + $row2['receiptcash'];
                                      
                                      $debitadj = $debitadj + $row2['paymentadj'];
                                      $creditadj = $creditadj + $row2['receiptadj'];
                                      
                                    }
                                    else {

                                      echo "<tr>";																			
                                      echo "<td colspan='3'><b>".$row2['SubHead']."</b></td>";																			                                      
                                      echo "<td></td>";
                                      echo "<td></td>";
                                      echo "<td></td>";
                                      echo "</tr>";
                                      $headingsubhead = $row2['subheadid'];

                                      echo "<tr>";
                                      echo "<td>".$row2['TransID']."</td>";																			
                                      if (!empty($row2['memid'])) {
                                        $memid = $row2['memid'];
                                        $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
                                        $row7 = mysqli_fetch_assoc($sql7);
                                        
                                        echo "<td>".$row7['memname']." - ".$row2['details']."</td>";  
                                      }
                                      else{
                                        
                                        echo "<td>".$row2['details']."</td>";      
                                      }
                                      echo "<td align='right'>".$row2['receiptcash']."</td>";
                                      echo "<td align='right'>".$row2['paymentcash']."</td>";

                                      
                                      echo "<td align='right'>".$row2['receiptadj']."</td>";
                                      echo "<td align='right'>".$row2['paymentadj']."</td>";
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
                               
                              echo "<tr>																
                                <td colspan='2'><b>Closing Balance</b></td>																
                                <td></td>
                                <td align='right'><b>";
                              echo number_format($cashcb,2);
                              echo "</b></td>																                                
                                <td></td>																
                                <td></td>																
                              </tr>
                              <tr>																
                                <td colspan='2'><b>Grand Total</b></td>
                                <td align='right'><b>";
                              echo number_format($debitcash+$cashcb,2); 
                              echo "</b></td>
                                <td align='right'><b>";
                              echo number_format($creditcash,2); 
                              echo "</b></td>
                                <td align='right'><b>";
                              echo number_format($creditadj,2); 
                              echo "</b></td>
                                <td align='right'><b>";
                              echo number_format($debitadj,2); 
                              echo "</b></td>              
                              </tr>";
                            
                      
?>