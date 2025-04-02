<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president";
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

    $cashtransfer = mysqli_query($connection,"SELECT acc_cash_dummy_transfer.*, ClusterName FROM acc_cash_dummy_transfer, cluster 
                                              WHERE acc_cash_dummy_transfer.clusterid = '$clusterid' 
                                              AND acc_cash_dummy_transfer.status = 0
                                              AND acc_cash_dummy_transfer.groupid = cluster.ClusterID");
    $cashtransfercount = mysqli_num_rows($cashtransfer);


    $loanproabstract = mysqli_query($connection,"SELECT A.*, B.status FROM 
                                        (SELECT MacsPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy GROUP BY MacsPropID) as A, 
                                        (SELECT MacsPropID, macsloanprop.status FROM macsloanprop 
                                        WHERE macsloanprop.status IN (2,3)) as B 
                                        WHERE A.MacsPropID = B.MacsPropID");
    $loanproposalcount = mysqli_num_rows($loanproabstract);


    $cbalance = mysqli_query($connection,"SELECT * FROM acc_cluster_balance");
    $cbcount = mysqli_num_rows($cbalance);  


    $depbalance = mysqli_query($connection,"SELECT sum(cb), ClusterName, cluster.ClusterID FROM acc_deposits, members, groups, cluster WHERE acc_deposits.memid = members.memid AND members.memgroupid = groups.GroupID AND groups.ClusterID = cluster.ClusterID GROUP BY ClusterID");

    $depcount = mysqli_num_rows($depbalance);

    
    $loanbalance = mysqli_query($connection,"SELECT sum(cb),ClusterName, cluster.ClusterID FROM acc_loans, members, groups, cluster WHERE acc_loans.memid = members.memid AND members.memgroupid = groups.GroupID AND groups.ClusterID = cluster.ClusterID GROUP BY ClusterID");

    $loancount = mysqli_num_rows($loanbalance);



    $exppending = mysqli_query($connection,"SELECT acc_cash_dummy_expenses.*, SubHead, ClusterName FROM acc_cash_dummy_expenses, acc_subhead, cluster
                                            WHERE acc_cash_dummy_expenses.status = 0 
                                            AND acc_cash_dummy_expenses.subheadid = acc_subhead.SubID
                                            AND acc_cash_dummy_expenses.clusterid = cluster.ClusterID");
    $expcount = mysqli_num_rows($exppending);

    $memtransfer = mysqli_query($connection,"SELECT * FROM acc_mem_transfer_dummy WHERE status=1");
    $memtranscount = mysqli_num_rows($memtransfer);

    $transcancel= mysqli_query($connection,"SELECT DISTINCT acc_cashbook.TransID,cluster.ClusterName, CancelStatus FROM acc_cashbook, acc_transactions, cluster WHERE acc_cashbook.TransID = acc_transactions.TransID AND CancelStatus  = 1 AND acc_cashbook.clusterid = cluster.ClusterID");
    $trcancelcount = mysqli_num_rows($transcancel); 

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
								<div>
									<div id="user-profile-1" class="user-profile row">
										
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
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
                                      <tr>														
                                        <th  style="text-align: center;">Sl.No.</th>														
                                        <th  style="text-align: center;">Cluster Name</th>
                                        <th  style="text-align: center;">Employee Name</th>
                                        <th  style="text-align: center;">Balance</th>														
                                      </tr>
                                    </thead>	

																		<tbody>
                                      <?php 
                                      
                                        if($cbcount > 0){
                                          $slno = 1;
                                          while($row3 = mysqli_fetch_assoc($cbalance)){
                                            $clusid = $row3['ClusterID'];
                                            if($clusid == 'C100'){
                                              $clusname = 'Head Office';
                                            }
                                            else{
                                              $clusname = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID = '$clusid'");
                                              $clusname = mysqli_fetch_assoc($clusname);
                                              $clusname = $clusname['ClusterName'];
                                            }
                                            $empsql = mysqli_query($connection,"SELECT `empname` FROM `employee`,`allot` WHERE employee.empid = allot.EmpID AND Status = 1 AND ClusterID = '$clusid'");
                                            $emprow = mysqli_fetch_assoc($empsql);
                                            $empname = $emprow['empname']; 
                                            echo "<tr>";
                                            echo "<td align='center'>".$slno."</td>";
                                            echo "<td align='left'><a href='pdt_cluster_det.php?clusterid=".$clusid."'>".$clusname."</td>";
                                            echo "<td align='left'>".$empname."</td>";
                                            echo "<td align='right'>".number_format($row3['Balance'],2)."</td>";
                                            echo "</tr>";
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
                    </div>

                    <div class="col-md-12 ">
											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														 Cluster wise deposit balances                            
													</h4>													
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
                                      <tr>														
                                        <th  style="text-align: center;">Sl.No</th>
                                        <th  style="text-align: center;">Cluster Name</th>                                        
                                        <th  style="text-align: center;">Balance</th>														                                        													
                                      </tr>
                                    </thead>	

																		<tbody>
                                      <?php
                                        if($depcount>0){
                                          $slno = 1;
                                          $totaldep = 0;
                                          while($deprow = mysqli_fetch_assoc($depbalance)){
                                            echo "<tr>";
                                            echo "<td align='center'>".$slno."</td>";
                                            echo "<td align='left'><a href='pdt_cluster_det.php?clusterid=".$deprow['ClusterID']."'>".$deprow['ClusterName']."</td>";                                            
                                            echo "<td align='right'>".number_format($deprow['sum(cb)'],2)."</td>";
                                            echo "</tr>";
                                            $slno = $slno + 1;
                                            $totaldep = $totaldep + $deprow['sum(cb)'];
                                          }
                                        }
                                            echo "<tr>";
                                            echo "<td align='center'></td>";                                            
                                            echo "<td align='left'>Total</td>";
                                            echo "<td align='right'>".number_format($totaldep,2)."</td>";
                                            echo "</tr>";                                      
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
                    
                    <div class="col-md-12 ">											
											

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														 Cluster wise Loan Balances
                            
													</h4>													
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
                                      <tr>														
                                        <th  style="text-align: center;">Sl.No</th>
                                        <th  style="text-align: center;">Cluster Name</th>                                        
                                        <th  style="text-align: center;">Balance</th>														
                                        													
                                      </tr>
                                    </thead>	

																		<tbody>
                                      <?php
                                        if($loancount>0){
                                          $slno = 1;
                                          $totalloan = 0;
                                          while($loanrow = mysqli_fetch_assoc($loanbalance)){
                                            echo "<tr>";
                                            echo "<td align='center'>".$slno."</td>";
                                            echo "<td align='left'><a href='pdt_cluster_det.php?clusterid=".$loanrow['ClusterID']."'>".$loanrow['ClusterName']."</td>";                                            
                                            echo "<td align='right'>".number_format($loanrow['sum(cb)'],2)."</td>";
                                            echo "</tr>";
                                            $slno = $slno + 1;
                                            $totalloan = $totalloan + $loanrow['sum(cb)'];
                                          }
                                        }
                                            echo "<tr>";
                                            echo "<td align='center'></td>";                                            
                                            echo "<td align='left'>Total</td>";
                                            echo "<td align='right'>".number_format($totalloan,2)."</td>";
                                            echo "</tr>";                                                                            
                                      
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
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-md-12 ">
                        <div class="widget-box transparent">
                            <div class="widget-header widget-header-small">
                                <h4 class="widget-title blue smaller">
                                    <i class="ace-icon fa fa-rss orange"></i>
                                    Expenses Pending for Approval
                                    <small><a href='pdt_cb_expenses_all.php'> View all transactions </a></small>
                                </h4>													
                            </div>
                        <div class="widget-body">
                            <div class="widget-main padding-8">
                                <div id="profile-feed-1" class="profile-feed">
                                    <div class="profile-activity clearfix">
                                        <div>                        
                                            <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                <thead>
                                                    <tr>														
                                                        <th  style="text-align: center;">Exp ID</th>
                                                        <th  style="text-align: center;">Cluster Name</th>
                                                        <th  style="text-align: center;">Sub Head</th>														
                                                        <th  style="text-align: center;">Purpose</th>
                                                        <th  style="text-align: center;">Amount</th>														
                                                    </tr>
                                                </thead>	

																		<tbody>
                                      <?php 
                                        if($expcount > 0){
                                          while($exprow = mysqli_fetch_assoc($exppending)){
                                            echo "<tr>";
                                            echo "<td align = 'center'><a href='pdt_cb_expenses_view.php?expid=".$exprow['PaymentID']."'><button class'btn-primary'>".$exprow['PaymentID']."</button></a></td>";
                                            echo "<td align = 'left'>".$exprow['ClusterName']."</td>";
                                            echo "<td align = 'left'>".$exprow['SubHead']."</td>";
                                            echo "<td align = 'left'>".$exprow['details']."</td>";
                                            echo "<td align = 'right'>".number_format($exprow['paymentcash'],2)."</td>";
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
                </div> 
              </div> 
              
              
              
              
              
              
              
							<div class="col-md-7 col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
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
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
                                      <tr>														
                                        <th rowspan="2" style="text-align: center;">Transaction ID</th>														
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

																		<tbody>
                                      
                                      <tr>																
                                        <td><b>Opening Balance</b></td>                                
                                        <td text-align='right'><b><?php echo number_format($cashob,2);?></b></td>																                                																
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
                                              echo "</tr>";
                                              $headingsubhead = $row2['subheadid'];

                                              echo "<tr>";
                                              echo "<td>".$row2['TransID']."</td>";																			
                                              
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
                                        <td ><b>Closing Balance</b></td>																
                                        <td></td>
                                        <td text-align='right'><b><?php echo number_format($cashcb,2);?></b></td>																                                
                                        <td></td>																
                                        <td></td>																
                                      </tr>
                                      <tr>																
                                        <td ><b>Grand Total</b></td>
                                        <td text-align='right'><b><?php echo number_format($debitcash+$cashcb,2); ?></b></td>
                                        <td text-align='right'><b><?php echo number_format($creditcash,2); ?></b></td>
                                        <td text-align='right'><b><?php echo number_format($creditadj,2); ?></b></td>
                                        <td text-align='right'><b><?php echo number_format($debitadj,2); ?></b></td>																

                                      </tr>
																		
																			
																		</tbody>
																	</table>																				
																</div>
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
                                        <small><a href='president_cash_transfer_all.php'> View all Cash Transfers </a></small>
                                    </h4>													
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <div id="profile-feed-1" class="profile-feed">
                                            <div class="profile-activity clearfix">
                                                <div>                                
                                                    <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                        <thead>
                                                        <tr>														
                                                            <th style="text-align: center;">Sl.No</th>
                                                            <th style="text-align: center;">Transfer ID</th>
                                                            <th style="text-align: center;">Transfer Type</th>
                                                            <th  style="text-align: center;">Office Name</th>
                                                            <th  style="text-align: center;">Date</th>
                                                            <th  style="text-align: center;">Amount</th>                                                                                
                                                        </tr>
                                                        </thead>	
                                                        <tbody>
                                                            <?php 
                                                                $slno = 1;
                                                                if($cashtransfercount > 0){
                                                                while($cashrow = mysqli_fetch_assoc($cashtransfer)){                                            
                                                                    $payment = $cashrow['paymentcash'];                                           
                                                                    echo "<tr>";
                                                                    echo "<td>".$slno."</td>";
                                                                    echo "<td><a href='president_cash_transfer_view.php?cashid=".$cashrow['CashTrID']."'><button class'btn-primary'>".$cashrow['CashTrID']."</button></a></td>";
                                                                    if($payment > 0)
                                                                    echo "<td>Cash Sent</td>";
                                                                    else
                                                                    echo "<td>Cash Received</td>";
                                                                    echo "<td>".$cashrow['ClusterName']."</td>";
                                                                    echo "<td>".$cashrow['date']."</td>";
                                                                    if($payment > 0)
                                                                    echo "<td align='right'>".$cashrow['paymentcash']."</td>";
                                                                    else
                                                                    echo "<td align='right'>".$cashrow['receiptcash']."</td>";
                                                                    
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
                        
                        
                        <div class="col-md-12 ">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small">
                                    <h4 class="widget-title blue smaller">
                                        <i class="ace-icon fa fa-rss orange"></i>
                                        Pending Loan Proposals 
                                        <small><a href='pdt_loan_prop_all.php?flag=1'> View all Loan Proposals </a></small>
                                    </h4>													
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <div id="profile-feed-1" class="profile-feed">
                                            <div class="profile-activity clearfix">
                                                <div>                                
                                                    <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                        <thead>
                                                        <tr>														
                                                            <th style="text-align: center;">Sl.No</th>
                                                            <th style="text-align: center;">MACS Prop ID</th>
                                                            <th style="text-align: center;">No of Members</th>
                                                            <th  style="text-align: center;">Total Proposed Loan</th>
                                                            <th  style="text-align: center;">Status</th>                                                            
                                                        </tr>
                                                        </thead>	
                                                        <tbody>
                                                            <?php 
                                                                $slno = 1;
                                                                if($loanproposalcount > 0){
                                                                    while($loanproprow = mysqli_fetch_assoc($loanproabstract)){                                                                                                                                
                                                                        $status = $loanproprow['status'];
                                                                        echo "<tr>";
                                                                        echo "<td>".$slno."</td>";
                                                                        if($status == 2)
                                                                            echo "<td><a href='pdt_loan_prop_view.php?macspropid=".$loanproprow['MacsPropID']."&flag=1'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                        else if($status == 3)
                                                                            echo "<td><a href='pdt_loan_prop_bank_view.php?macspropid=".$loanproprow['MacsPropID']."&flag=1'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                        echo "<td align='center'>".$loanproprow['totloanees']."</td>";
                                                                        echo "<td align='right'>".$loanproprow['totloan']."</td>";                                                                                    
                                                                        if($status == 2)
                                                                            echo "<td>Proposals Initiated</td>";
                                                                        else if($status == 3)
                                                                            echo "<td>Proposed Amount Sent to Bank</td>";                                                                        
                                                                        echo "</tr>";
                                                                        $slno = $slno +1;
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
                        <div class="col-md-12 ">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small">
                                    <h4 class="widget-title blue smaller">
                                        <i class="ace-icon fa fa-rss orange"></i>
                                        Pending Member Transfers
                                        <small><a href='pdt_mem_transfer_all.php'> View all Member Transfers </a></small>
                                    </h4>													
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <div id="profile-feed-1" class="profile-feed">
                                            <div class="profile-activity clearfix">
                                                <div>                                
                                                    <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                        <thead>
                                                        <tr>														
                                                            <th style="text-align: center;">Sl.No</th>
                                                            <th style="text-align: center;">Member ID</th>
                                                            <th style="text-align: center;">Member Name</th>
                                                            <th style="text-align: center;">Present Group</th>
                                                            <th style="text-align: center;">Transfer to</th>
                                                            <th style="text-align: center;">Cluster</th>
                                                            <th class="center">Accept</th>
                                                            <th class="center">Reject</th>                                                           
                                                        </tr>
                                                        </thead>	
                                                        <tbody>
                                                            <?php 
                                                                $slno = 0;
                                                                if($memtranscount > 0){
                                                                    while($rowtransfer = mysqli_fetch_assoc($memtransfer)){                                                                                                                                
                                                                            echo "<tr>";
                                                                            $sno = $slno+1;
                                                                            echo "<td align='center'>".$sno."</td>";
                                                                            echo "<td align='center' id='memid".$slno."' name='memid'>".$rowtransfer['memid']."</td>";                                                                                    
                                                                            echo "<td align='center'>".$rowtransfer['memname']."</td>";
                                                                            $clusterid = $rowtransfer['ClusterID'];
                                                                            $pgroupid = $rowtransfer['PGroupID'];
                                                                            $pgroup = mysqli_query($connection,"SELECT GroupName,ClusterName FROM groups,cluster WHERE groups.ClusterID=cluster.ClusterID AND GroupID='$pgroupid'");
                                                                            $pgroupname = mysqli_fetch_assoc($pgroup);
                                                                            echo "<td align='center'>".$pgroupname['GroupName']."</td>";
                                                                            $tgroupid = $rowtransfer['TGroupID'];
                                                                            $tgroup = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID='$tgroupid'");
                                                                            $tgroupname = mysqli_fetch_assoc($tgroup);
                                                                            echo "<td align='center'>".$tgroupname['GroupName']."</td>";
                                                                            echo "<td align='center'>".$pgroupname['ClusterName']."</td>";                                                                                    
                                                                            echo "<td aling='center'>
                                                                                    <a href=''><span id='accept".$slno."' class='accept' class='label label-success arrowed'>Accept</span></a>
                                                                                    </td>";
                                                                            echo "<td aling='center'>
                                                                                    <a href=''><span id='reject".$slno."' class='reject' class='label label-danger arrowed-right'>Reject</span></a></td>";                                                                                           
                                                                            echo "</tr>";
                                                                            $slno = $slno +1;
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
                        <div class="col-md-12 ">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small">
                                    <h4 class="widget-title blue smaller">
                                        <i class="ace-icon fa fa-rss orange"></i>
                                        Pending Transactions for Cancellation
                                        <small><a href='pdt_trans_cancel_all.php'> View all Transaction Cancel Requests </a></small>
                                    </h4>													
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <div id="profile-feed-1" class="profile-feed">
                                            <div class="profile-activity clearfix">
                                                <div>                                
                                                    <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                        <thead>
                                                        <tr>														
                                                            <th style="text-align: center;">Sl.No</th>
                                                            <th style="text-align: center;">Trans ID</th>                                                            
                                                            <th style="text-align: center;">Cluster</th>
                                                            <th class="center">View</th>
                                                        </tr>
                                                        </thead>	
                                                        <tbody>
                                                            <?php 
                                                                $slno = 0;
                                                                if($trcancelcount > 0){
                                                                    while($rowtranscancel = mysqli_fetch_assoc($transcancel)){                                                                                                                                
                                                                        echo "<tr>";
                                                                        $sno = $slno+1;
                                                                        echo "<td align='center'>".$sno."</td>";
                                                                        echo "<td align='center' id='transid".$slno."' name='transid'>".$rowtranscancel['TransID']."</td>";                                                                                                                                                
                                                                        echo "<td align='center'>".$rowtranscancel['ClusterName']."</td>";
                                                                        echo "<td aling='center'>
                                                                                <a href='pdt_trans_cancel_view.php?transid=".$rowtranscancel['TransID']."'><span id='view".$slno."' class='view' class='label label-success arrowed'><button>View</button></span></a>
                                                                              </td>";
                                                                        $slno = $slno +1;
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
								</div>
							</div>
              
              

              
              
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>
<script>		
		$(document).ready(function()
		{ 	
			$('.accept').click(function() 
            {   
                ////$("table tr input").on('click', function(e){
                ////alert($(this).closest('td').parent()[0].sectionRowIndex);
                ////});​
                row = $(this).closest('td').parent()[0].sectionRowIndex;
                var memid = $('#memid'+row).text();
                //alert(memid);
					$.ajax({  
						type: "POST",  
						url: "pdt_mem_transfer_accept.php",  
						data: "memid="+ memid, 
						success: function(response){ 																				
							location.reload();								
						} 
					}); 
								
			return false;
            });
            $('.reject').click(function() 
            {   
                ////$("table tr input").on('click', function(e){
                ////alert($(this).closest('td').parent()[0].sectionRowIndex);
                ////});​
                row = $(this).closest('td').parent()[0].sectionRowIndex;
                var memid = $('#memid'+row).text();
                //alert(memid);
					$.ajax({  
						type: "POST",  
						url: "pdt_mem_transfer_reject.php",  
						data: "memid="+ memid, 
						success: function(response){ 																				
							location.reload();								
						} 
					}); 
								
			return false;
			});			
		});
</script>
