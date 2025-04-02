<?php	  
	include("admin_session.php");
	$_SESSION['curpage']="admin";
	include("adminsidepan.php");
	$clusbal = $connection->query("SELECt cluster.ClusterName, acc_cluster_balance.Balance FROM cluster, acc_cluster_balance WHERE acc_cluster_balance.ClusterID = cluster.ClusterID");	
	$groupdepbal = $connection->query("SELECT cluster.ClusterName, groups.GroupName, sum(cb) FROM cluster, groups, acc_deposits, members WHERE acc_deposits.memid = members.memid AND groups.GroupID = members.memgroupid AND cluster.ClusterID = groups.ClusterID GROUP BY groups.GroupID");
	$grouploanbal = $connection->query("SELECT cluster.ClusterName, groups.GroupName, sum(cb) FROM cluster, groups, acc_loans, members WHERE acc_loans.memid = members.memid AND groups.GroupID = members.memgroupid AND cluster.ClusterID = groups.ClusterID GROUP BY groups.GroupID");
	$expapproval = $connection->query("SELECT cluster.ClusterName, acc_cash_dummy_expenses.*, acc_subhead.SubHead FROM cluster, acc_cash_dummy_expenses, acc_subhead WHERE acc_cash_dummy_expenses.clusterid = cluster.ClusterID AND acc_cash_dummy_expenses.status = 0 AND acc_cash_dummy_expenses.subheadid = acc_subhead.SubID");	
	$cashapproval = $connection->query("SELECT cluster.ClusterName, acc_cash_dummy_transfer.* FROM cluster, acc_cash_dummy_transfer WHERE acc_cash_dummy_transfer.clusterid = cluster.ClusterID AND acc_cash_dummy_transfer.clusterid = 'C100' AND acc_cash_dummy_transfer.status = 0");	
	
	$openingbalance = $connection->query("SELECT sum(receiptcash) as receipt, sum(paymentcash) as payment FROM acc_cashbook, acc_transactions WHERE clusterid = 'C100' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.date < '$today'");
	$opbal = $openingbalance->fetch_assoc();
	$receipt = $opbal['receipt'];
	$payment = $opbal['payment'];
	$opening = $receipt - $payment;

	$cashbook = $connection->query("SELECT acc_subhead.SubHead, acc_cashbook.TransID, subheadid, receiptcash, receiptadj, paymentcash, paymentadj FROM acc_cashbook, acc_subhead, acc_transactions WHERE acc_cashbook.date = '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_subhead.SubID = acc_cashbook.subheadid");	

?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header">
							<h1>
								Dash Board
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-5 col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div class="col-md-12 ">											
									<div class="widget-box transparent">
										<div class="widget-header widget-header-small">
											<h4 class="widget-title blue smaller">
												<i class="ace-icon fa fa-rss orange"></i>
												Cash Balances in All Culsters
											</h4>													
										</div>
										<div class="widget-body">
											<div class="widget-main padding-8">
												<div id="profile-feed-1" class="profile-feed">
													<div class="profile-activity clearfix">									
														<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
															<thead>
																<tr>														
																	<th style="text-align: center;">Sl.No.</th>														
																	<th style="text-align: center;">Clustere Name</th>
																	<th style="text-align: center;">Balance</th>														
																</tr>
															</thead>	
															<tbody>	
																 <?php
																  	$slno = 1;
																  	if($clusbal->num_rows > 0){
																		  while($row = $clusbal->fetch_assoc()){
																			  echo "<tr>";
																			  echo "<td align='center'>".$slno."</td>";
																			  echo "<td align='left'>".$row['ClusterName']."</td>";
																			  echo "<td align='right'>".number_format($row['Balance'],2)."</td>";
																			  $slno = $slno + 1;
																		  }
																	  }
																 ?>																									
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-12 ">
									<div class="widget-box transparent">
										<div class="widget-header widget-header-small">
											<h4 class="widget-title blue smaller">
											<i class="ace-icon fa fa-rss orange"></i>
											Group wise deposit balances                            
											</h4>													
										</div>
										<div class="widget-body">
											<div class="widget-main padding-8">
												<div id="profile-feed-1" class="profile-feed">
													<div class="profile-activity clearfix">								
														<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
															<thead>
																<tr>														
																	<th style="text-align: center;">Sl.No</th>
																	<th style="text-align: center;">Cluster Name</th>
																	<th style="text-align: center;">Group Name</th>
																	<th style="text-align: center;">Balance</th>														                                        													
																</tr>
															</thead>	
															<tbody>
																<?php 
																	$slno = 1;
																	$total = 0;
																	if($groupdepbal->num_rows > 0 ){
																		while($row = $groupdepbal->fetch_assoc()){
																			echo "<tr>";
																			echo "<td align='center'>".$slno."</td>";
																	 		echo "<td align='left'>".$row['ClusterName']."</td>";
																			echo "<td align='left'>".$row['GroupName']."</td>";
																	 		echo "<td align='right'>".number_format($row['sum(cb)'],2)."</td>";
																			echo "</tr>";
																			$slno = $slno + 1; 
																			$total = $total + $row['sum(cb)'];
																		}
																		echo "<tr>";																		
																		echo "<td align='left' colspan='3'>Total</td>";
																		echo "<td align='right'>".number_format($total,2)."</td>";
																		echo "</tr>";
																	} 
																?>
																																                                  
															</tbody>
														</table>																				
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>                    
											
								<div class="col-md-12 ">											
									<div class="widget-box transparent">
										<div class="widget-header widget-header-small">
											<h4 class="widget-title blue smaller">
											<i class="ace-icon fa fa-rss orange"></i>
											Group wise Loan Balances
											</h4>													
										</div>
										<div class="widget-body">
											<div class="widget-main padding-8">
												<div id="profile-feed-1" class="profile-feed">
													<div class="profile-activity clearfix">																	
														<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
															<thead>
																<tr>														
																	<th style="text-align: center;">Sl.No</th>
																	<th style="text-align: center;">Cluster Name</th>
																	<th style="text-align: center;">Group Name</th>
																	<th style="text-align: center;">Balance</th>														                                        													
																</tr>
															</thead>	
															<tbody>
																<?php 
																	$slno = 1;
																	$total = 0;
																	if($grouploanbal->num_rows > 0 ){
																		while($row = $grouploanbal->fetch_assoc()){
																			echo "<tr>";
																			echo "<td align='center'>".$slno."</td>";
																	 		echo "<td align='left'>".$row['ClusterName']."</td>";
																			echo "<td align='left'>".$row['GroupName']."</td>";
																	 		echo "<td align='right'>".number_format($row['sum(cb)'],2)."</td>";
																			echo "</tr>";
																			$slno = $slno + 1; 
																			$total = $total + $row['sum(cb)'];
																		}
																		echo "<tr>";																		
																		echo "<td align='left' colspan='3'>Total</td>";
																		echo "<td align='right'>".number_format($total,2)."</td>";
																		echo "</tr>";
																	} 
																?>                                                                    
															</tbody>
														</table>																				
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>      
									
								<div class="col-md-12 ">											
									<div class="widget-box transparent">
										<div class="widget-header widget-header-small">
											<h4 class="widget-title blue smaller">
											<i class="ace-icon fa fa-rss orange"></i>
											Expenses Pending for Approval
											<small><a href="admin_cb_expenses_all.php"> view all transactions </a></small>
											</h4>													
										</div>
										<div class="widget-body">
											<div class="widget-main padding-8">
												<div id="profile-feed-1" class="profile-feed">
													<div class="profile-activity clearfix">
														<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
															<thead>
																<tr>														
																	<th style="text-align: center;">Exp ID</th>
																	<th style="text-align: center;">Cluster Name</th>
																	<th style="text-align: center;">Sub Head</th>														
																	<th style="text-align: center;">Purpose</th>
																	<th style="text-align: center;">Amount</th>														
																</tr>
															</thead>	
															<tbody>
																<?php
																 	if($expapproval->num_rows > 0){
																		 while($row = $expapproval->fetch_assoc()){
																			echo "<tr>";
																			echo "<td align='center'><a href='admin_cb_expenses_view.php?expid=".$row['PaymentID']."'><button >".$row['PaymentID']."</button></a></td>";
																		   	echo "<td align='left'>".$row['ClusterName']."</td>";
																		   	echo "<td align='left'>".$row['SubHead']."</td>";
																		  	echo "<td align='left'>".$row['details']."</td>";
																		   	echo "<td align='right'>".number_format($row['paymentcash'],2)."</td>";
																	   		echo "</tr>";																			 
																		 }
																	 }
																?>                                      							                                      
															</tbody>
														</table>																											
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>   
							</div>    
													
							<div class="col-md-7 col-xs-12"> <!-- PAGE CONTENT BEGINS -->	
								<div class="col-md-12 ">											
									<div class="widget-box transparent">
										<div class="widget-header widget-header-small">
											<h4 class="widget-title blue smaller">
											<i class="ace-icon fa fa-rss orange"></i>
											Cash Book 
											</h4>													
										</div>
										<div class="widget-body">
											<div class="widget-main padding-8">
												<div id="profile-feed-1" class="profile-feed">
													<div class="profile-activity clearfix">
														<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
															<thead>
																<tr>														
																	<th rowspan="2" style="text-align: center;">Transaction ID</th>														
																	<th colspan="2" style="text-align: center;">Cash</th>
																	<th colspan="2" style="text-align: center;">Adjustment</th>														
																</tr>
																<tr>
																	<th style="text-align: center;">Receipt</th>
																	<th style="text-align: center;">Payment</th>
																	<th style="text-align: center;">Receipt</th>
																	<th style="text-align: center;">Payment</th>
																</tr>									  
															</thead>	
															<tbody>                                      
																<tr>																
																	<td><b>Opening Balance</b></td>                                
																	<td align="right"><b><?php echo number_format($opening,2); ?></b></td>																                                																
																	<td></td>
																	<td></td>
																	<td></td>																
																</tr>
																<?php 
																	$receiptcashtotal = $opening;
																	$paymentcashtotal = 0;
																	$receiptadjtotal = 0;
																	$paymentadjtotal = 0;
																	$closing = $opening;
																 	if($cashbook->num_rows > 0){
																		 $subhead = 0;
																		 
																		 while($row = $cashbook->fetch_assoc()){
																			if($row['subheadid'] == $subhead){
																				echo "<tr>";
																				echo "<td>".$row['TransID']."</td>";
																				echo "<td align='right'>".number_format($row['receiptcash'],2)."</td>";																				
																				echo "<td align='right'>".number_format($row['paymentcash'],2)."</td>";
																				echo "<td align='right'>".number_format($row['receiptadj'],2)."</td>";
																				echo "<td align='right'>".number_format($row['paymentadj'],2)."</td>";
																				echo "</tr>";
																			}
																			else{
																				echo "<tr>";
																				echo "<td colspan='3'><b>".$row['SubHead']."</b></td>";
																				echo "<td></td>";
																			   	echo "<td></td>";
																				echo "</tr>";
																				echo "<tr>";
																				echo "<td>".$row['TransID']."</td>";
																				echo "<td align='right'>".number_format($row['receiptcash'],2)."</td>";																				
																				echo "<td align='right'>".number_format($row['paymentcash'],2)."</td>";
																				echo "<td align='right'>".number_format($row['receiptadj'],2)."</td>";
																				echo "<td align='right'>".number_format($row['paymentadj'],2)."</td>";
																				echo "</tr>";   
																			}
																			$subhead = $row['subheadid'];
																			$receiptcashtotal = $receiptcashtotal + $row['receiptcash'];
																			$paymentcashtotal = $paymentcashtotal + $row['paymentcash'];
																			$receiptadjtotal = $receiptadjtotal + $row['receiptadj'];
																			$paymentadjtotal = $paymentadjtotal + $row['paymentadj'];
																			$closing = $receiptcashtotal + $receiptadjtotal - $paymentcashtotal - $paymentadjtotal;
																		 }
																	 }
																?>																
																
																<tr>																
																	<td><b>Closing Balance</b></td>																
																	<td></td>
																	<td align="right"><b><?php echo number_format($closing,2); ?></b></td>																                                
																	<td></td>																
																	<td></td>																
																</tr>
																<tr>																
																	<td><b>Grand Total</b></td>
																	<td align="right"><b><?php echo number_format($receiptcashtotal,2); ?></b></td>
																	<td align="right"><b><?php echo number_format($receiptcashtotal,2); ?></b></td>
																	<td align="right"><b><?php echo number_format($receiptadjtotal,2); ?></b></td>
																	<td align="right"><b><?php echo number_format($paymentadjtotal,2); ?></b></td>																

																</tr>	
															</tbody>
														</table>																				
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
											
								<div class="col-md-12 ">
									<div class="widget-box transparent">
										<div class="widget-header widget-header-small">
											<h4 class="widget-title blue smaller">
											<i class="ace-icon fa fa-rss orange"></i>
											Cash Transfers Pending 
											<small><a href="admin_cash_transfer_all.php"> view all Cash Transfers </a></small>
											</h4>													
										</div>
										<div class="widget-body">
											<div class="widget-main padding-8">
												<div id="profile-feed-1" class="profile-feed">
													<div class="profile-activity clearfix">
														<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
															<thead>
																<tr>														
																	<th style="text-align: center;">Sl.No</th>
																	<th style="text-align: center;">Transfer ID</th>
																	<th style="text-align: center;">Transfer Type</th>
																	<th style="text-align: center;">Office Name</th>
																	<th style="text-align: center;">Date</th>
																	<th style="text-align: center;">Amount</th>                                                                                
																</tr>
															</thead>	
															<tbody>
																<?php
																 	if($cashapproval->num_rows > 0){
																		 $slno = 1;
																		 while($row = $cashapproval->fetch_assoc()){
																			echo "<tr>";
																			echo "<td>".$slno."</td>";
																			echo "<td><a href='admin_cash_transfer_view.php?cashid=".$row['CashTrID']."'><button >".$row['CashTrID']."</button></a></td>";
																			if($row['receiptcash']  > 0)
																				echo "<td>Cash Received</td>";
																			else
																				echo "<td>Cash Sent</td>";
																			echo "<td>".$row['ClusterName']."</td>";
																			echo "<td>".$row['date']."</td>";
																			if($row['receiptcash']  > 0)																			
																				echo "<td align='right'>".number_format($row['receiptcash'],2)."</td>";
																			else
																			 	echo "<td align='right'>".number_format($row['paymentcash'],2)."</td>";
																		 	echo "</tr>";
																		 }
																	 }
																?>
                                      							
															</tbody>
														</table>																														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>                    
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			