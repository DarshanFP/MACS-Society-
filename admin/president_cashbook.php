<?php     
  include("pdt_session.php");
	$_SESSION['curpage']="president_cashbook";
	include("pdtsidepan.php");
		
    $cashfirstob = 0;

 	
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

?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1> <b>Cash Book on&nbsp&nbsp</b><span id="bookdate"><?php echo date("d-m-Y",strtotime($today));?></span>
                <small>
                  <i class="ace-icon fa fa-angle-double-right"></i>
                  <a href="pdt_cashbook_receipt.php"><button class="btn btn-info btn-fill">Receipts </button></a>
                  <a href="pdt_cashbook_payment.php"><button class="btn btn-info btn-fill">Payments </button></a>	
                  <a href="pdt_cashbook_adj.php"><button class="btn btn-info btn-fill">Adjustment </button></a>	
                </small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-10"> <!-- PAGE CONTENT BEGINS -->
<!-- 							    <div class="row">
										<div class = "col-md-3">
									 		<p style="padding:12px"><a href="acc_mem_new.php"><button class="btn btn-success">Add New Member</button></a></p>		
										</div>
										<div class = "col-md-9">
										 	<form  role="form" method="post" action="">
												<button class="btn btn-search" type="submit" style="float:right;height:42px;margin-right:2px; margin-top:15px;"><i class='ace-icon fa fa-search bigger-120'></i></button>
												<input type="text" id="memsearch" name="memsearch"  style="float:right;height:42px; margin-top:15px;" placeholder="Search" class="col-xs-4 col-sm-2" autocomplete="off" required  />
											</form>													
										</div>
								</div>								 -->
								
								<div class="row">
									<div class="col-md-10 col-xs-12">
                    <table class="table table-bordered table-hover">	
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
                        </thead>	
                        <tbody id="trows1">
                              <tr>																
                                <td colspan='2'><b>Opening Balance</b></td>                                
                                <td align='right'><b><?php echo number_format($cashob,2);?></b></td>																                                																
                                <td></td>
                                <td></td>
                                <td></td>																
                              </tr>
                              <?php 
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
                               ?>
                              <tr>																
                                <td colspan='2'><b>Closing Balance</b></td>																
                                <td></td>
                                <td align='right'><b><?php echo number_format($cashcb,2);?></b></td>																                                
                                <td></td>																
                                <td></td>																
                              </tr>
                              <tr>																
                                <td colspan="2"><b>Grand Total</b></td>
                                <td align='right'><b><?php echo number_format($debitcash+$cashcb,2); ?></b></td>
                                <td align='right'><b><?php echo number_format($creditcash,2); ?></b></td>
                                <td align='right'><b><?php echo number_format($creditadj,2); ?></b></td>
                                <td align='right'><b><?php echo number_format($debitadj,2); ?></b></td>																
                                
                              </tr>
                            </tbody>
                      </table>
                  </div>
								</div
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>	
					
