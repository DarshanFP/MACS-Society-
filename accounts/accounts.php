<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts";
	include("accountssidepan.php");

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


    $loanproposals = mysqli_query($connection,"SELECT A.*, B.status FROM 
                                        (SELECT ClusterPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy WHERE ClusterID = '$clusterid' GROUP BY ClusterPropID) as A, 
                                        (SELECT ClusterPropID, status FROM clusterloanprop WHERE status != 4) as B 
                                        WHERE A.ClusterPropID = B.ClusterPropID");
    $loanproposalcount = mysqli_num_rows($loanproposals);


    $recentdep = mysqli_query($connection,"SELECT acc_deposits.*, memname FROM acc_deposits, members 
                                          WHERE acc_deposits.memid = members.memid ORDER BY acc_deposits.id DESC LIMIT 5");
    $depcount = mysqli_num_rows($recentdep);

    $recentloan = mysqli_query($connection,"SELECT acc_loans.*, memname FROM acc_loans, members 
                                          WHERE acc_loans.memid = members.memid ORDER BY acc_loans.id DESC LIMIT 5");
    $loancount = mysqli_num_rows($recentloan);

    $exppending = mysqli_query($connection,"SELECT acc_cash_dummy_expenses.*, SubHead FROM acc_cash_dummy_expenses, acc_subhead
                                            WHERE acc_cash_dummy_expenses.status = 0 
                                            AND acc_cash_dummy_expenses.subheadid = acc_subhead.SubID
                                            AND acc_cash_dummy_expenses.clusterid = '$clusterid'");
    $expcount = mysqli_num_rows($exppending);

    $depbalance = mysqli_query($connection,"SELECT  sum(cb), GroupName, GroupID FROM acc_deposits, members, groups
                              WHERE acc_deposits.memid = members.memid 
                              AND members.memgroupid = groups.GroupID 
                              AND groups.ClusterID = '$clusterid' GROUP BY GroupID");

    $depcount = mysqli_num_rows($depbalance);

    
    $loanbalance = mysqli_query($connection,"SELECT  sum(cb), GroupName, GroupID FROM acc_loans, members, groups
                              WHERE acc_loans.memid = members.memid AND members.memgroupid = groups.GroupID 
                              AND groups.ClusterID = '$clusterid' GROUP BY GroupID");

    $loancount = mysqli_num_rows($loanbalance);

    $memtransfer = mysqli_query($connection,"SELECT * FROM acc_mem_transfer_dummy WHERE ClusterID='$clusterid' AND status=1");
    $memtranscount = mysqli_num_rows($memtransfer);

    $transcancel= mysqli_query($connection,"SELECT acc_transactions.TransID, TransStatus, CancelStatus FROM acc_cashbook, acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND CancelStatus = 1 AND clusterid = '$clusterid' GROUP BY acc_transactions.TransID");
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
                                        <td align='right'><b><?php echo number_format($cashob,2,'.','');?></b></td>																                                																
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
                                              

                                              echo "<td align='right'>".number_format($row2['receiptcash'],2,'.','')."</td>";
                                              echo "<td align='right'>".number_format($row2['paymentcash'],2,'.','')."</td>";


                                              echo "<td align='right'>".number_format($row2['receiptadj'],2,'.','')."</td>";
                                              echo "<td align='right'>".number_format($row2['paymentadj'],2,'.','')."</td>";
                                              echo "</tr>";
                                              $headingsubhead = $row2['subheadid'];
                                              $debitcash = $debitcash + $row2['paymentcash'];
                                              $creditcash = $creditcash + $row2['receiptcash'];

                                              $debitadj = $debitadj + $row2['paymentadj'];
                                              $creditadj = $creditadj + $row2['receiptadj'];

                                            }
                                            else {

                                              echo "<tr>";																			
                                              echo "<td colspan='5'><b>".$row2['SubHead']."</b></td>";																			
                                             
                                              
                                              echo "</tr>";
                                              $headingsubhead = $row2['subheadid'];

                                              echo "<tr>";
                                              echo "<td>".$row2['TransID']."</td>";																			
                                              
                                              echo "<td align='right'>".number_format($row2['receiptcash'],2,'.','')."</td>";
                                              echo "<td align='right'>".number_format($row2['paymentcash'],2,'.','')."</td>";


                                              echo "<td align='right'>".number_format($row2['receiptadj'],2,'.','')."</td>";
                                              echo "<td align='right'>".number_format($row2['paymentadj'],2,'.','')."</td>";
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
                                        <td align='right'><b><?php echo number_format($cashcb,2,'.','');?></b></td>																                                
                                        <td></td>																
                                        <td></td>																
                                      </tr>
                                      <tr>																
                                        <td ><b>Grand Total</b></td>
                                        <td align='right'><b><?php echo number_format($debitcash+$cashcb,2,'.',''); ?></b></td>
                                        <td align='right'><b><?php echo number_format($creditcash,2,'.',''); ?></b></td>
                                        <td align='right'><b><?php echo number_format($creditadj,2,'.',''); ?></b></td>
                                        <td align='right'><b><?php echo number_format($debitadj,2,'.',''); ?></b></td>																

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
                                                        <small><a href='accounts_cash_transfer_all.php'> view all Cash Transfers </a></small>
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
                                                                                    echo "<td><a href='accounts_cash_transfer_view.php?cashid=".$cashrow['CashTrID']."'><button class'btn-primary'>".$cashrow['CashTrID']."</button></a></td>";
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
														Pending Loan Proposals 
                                                        <small><a href='acc_loans_proposals_all.php'> view all Loan Proposals </a></small>
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
                                                                                <th style="text-align: center;">Proposal ID</th>
                                                                                <th style="text-align: center;">No of Members</th>
                                                                                <th  style="text-align: center;">Total Proposed Loan</th>
                                                                                <th  style="text-align: center;">Status</th>                                                                                
                                                                            </tr>
                                                                        </thead>
																		<tbody>
                                                                            <?php 
                                                                                $slno = 1;
                                                                                if($loanproposalcount > 0){
                                                                                    while($loanproprow = mysqli_fetch_assoc($loanproposals)){                                                                                                                                
                                                                                        echo "<tr>";
                                                                                        echo "<td>".$slno."</td>";
                                                                                        echo "<td><a href='acc_loan_prop_view.php?clustpropid=".$loanproprow['ClusterPropID']."'><button class'btn-primary'>".$loanproprow['ClusterPropID']."</button></a></td>";                                                                                    
                                                                                        echo "<td align='center'>".$loanproprow['totloanees']."</td>";
                                                                                        echo "<td align='right'>".$loanproprow['totloan']."</td>";                                                                                    
                                                                                        $status = $loanproprow['status'];
                                                                                        if($status == 1)
                                                                                            echo "<td>Proposals Submitted</td>";
                                                                                        else if($status == 2)
                                                                                            echo "<td>Proposal Accepted</td>";
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
                                                    &nbsp<small class="alltransfers"><a href='acc_mem_transfer_all.php'> View all member transfers </a></small>    
													</h4>													
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="memtransfers" class="table  table-bordered table-hover">
																		<thead>
                                                                            <tr>														
                                                                                <th style="text-align: center;">Sl.No</th>
                                                                                <th style="text-align: center;">Member ID</th>
                                                                                <th style="text-align: center;">Member Name</th>
                                                                                <th style="text-align: center;">Present Group</th>
                                                                                <th style="text-align: center;">Transfer to</th>
                                                                                <th style="text-align: center;">Status</th>
                                                                                <th style="text-align: center;">Delete</th>                                                                                
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
                                                                                        $pgroupid = $rowtransfer['PGroupID'];
                                                                                        $pgroup = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID='$pgroupid'");
                                                                                        $pgroupname = mysqli_fetch_assoc($pgroup);
                                                                                        echo "<td align='center'>".$pgroupname['GroupName']."</td>";
                                                                                        $tgroupid = $rowtransfer['TGroupID'];
                                                                                        $tgroup = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID='$tgroupid'");
                                                                                        $tgroupname = mysqli_fetch_assoc($tgroup);
                                                                                        echo "<td align='center'>".$tgroupname['GroupName']."</td>";
                                                                                        if($rowtransfer['status']==0){
                                                                                            echo "<td class='center'><span class='label label-danger'>Rejected</span></td>";
                                                                                        }else if($rowtransfer['status']==1){
                                                                                            echo "<td class='center'><span class='label label-primary'>Pending</span></td>";
                                                                                        }else{                                                                                    
                                                                                            echo "<td class='center'><span class='label label-success'>Accepted</span></td>";
                                                                                        }
                                                                                        echo "<td class='center'>                                                                                                
                                                                                                <a><button class='btn btn-xs btn-info' id='memtransferbutton".$slno."'>
                                                                                                    <i class='ace-icon fa fa-trash bigger-120'></i>
                                                                                                </button>
                                                                                                </a>							  
                                                                                              </td>";                                                                                              
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
														Pending Transactions for cancellation 
                                                    &nbsp<small class="transcancel"><a href='acc_trans_view_all.php'> View all cancelled transactions</a></small>    
													</h4>													
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="transidcancel" class="table  table-bordered table-hover">
																		<thead>
                                                                            <tr>														
                                                                                <th style="text-align: center;">Sl.No</th>
                                                                                <th style="text-align: center;">Trans ID</th>                                                            
                                                                                <th style="text-align: center;">Status</th>
                                                                                <th class="center">Delete</th>                                                                                
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
                                                                                        echo "<td align='center' id='transid".$slno."' name='transid'>
                                                                                                    <a href='acc_trans_view.php?transid=".$rowtranscancel['TransID']."'><button class'btn-primary'>".$rowtranscancel['TransID']."</button></a></td>";                                                                                        
                                                                                        if($rowtranscancel['CancelStatus']==0){
                                                                                            echo "<td class='center'><span class='label label-danger'>Rejected</span></td>";
                                                                                        }else if($rowtranscancel['CancelStatus']==1){
                                                                                            echo "<td class='center'><span class='label label-primary'>Pending</span></td>";
                                                                                        }else{                                                                                    
                                                                                            echo "<td class='center'><span class='label label-success'>Accepted</span></td>";
                                                                                        }
                                                                                        echo "<td class='center'>                                                                                                
                                                                                                <a><button class='btn btn-xs btn-info' id='transbutton".$slno."'>
                                                                                                    <i class='ace-icon fa fa-trash bigger-120'></i>
                                                                                                </button>
                                                                                                </a>							  
                                                                                              </td>";                                                                                              
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



                    
                    
                    
									</div>
								</div>
							</div>
              
              
              <div class="col-md-5 col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
                    
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
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
                                                                        <tr>														
                                                                            <th  style="text-align: center;">Sl.No</th>                                        
                                                                            <th  style="text-align: center;">Group Name</th>
                                                                            <th  style="text-align: center;">Balance</th>														                                        													
                                                                        </tr>
                                                                        </thead>	

																		<tbody>
                                                                            <?php
                                                                                $totaldep = 0;
                                                                                if($depcount>0){
                                                                                $slno = 1;
                                                                                
                                                                                while($deprow = mysqli_fetch_assoc($depbalance)){
                                                                                    echo "<tr>";
                                                                                    echo "<td align='center'>".$slno."</td>";                                            
                                                                                    echo "<td align='left'><a href='acc_group_det.php?groupid=".$deprow['GroupID']."'>".$deprow['GroupName']."</a></td>";
                                                                                    echo "<td align='right'>".number_format($deprow['sum(cb)'],2,'.','')."</td>";
                                                                                    echo "</tr>";
                                                                                    $slno = $slno + 1;
                                                                                    $totaldep = $totaldep + $deprow['sum(cb)'];
                                                                                }
                                                                                }
                                                                                    echo "<tr>";
                                                                                    echo "<td align='center'></td>";                                            
                                                                                    echo "<td align='left'>Total</td>";
                                                                                    echo "<td align='right'>".number_format($totaldep,2,'.','')."</td>";
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
														 Group wise Loan Balances
                            
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
                                        <th  style="text-align: center;">Group Name</th>
                                        <th  style="text-align: center;">Balance</th>														
                                        													
                                      </tr>
                                    </thead>	

																		<tbody>
                                      <?php
                                        $totalloan = 0;
                                        if($loancount>0){
                                          $slno = 1;
                                          
                                          while($loanrow = mysqli_fetch_assoc($loanbalance)){
                                            echo "<tr>";
                                            echo "<td align='center'>".$slno."</td>";                                            
                                            echo "<td align='left'><a href='acc_group_det.php?groupid=".$loanrow['GroupID']."'>".$loanrow['GroupName']."</a></td>";
                                            echo "<td align='right'>".number_format($loanrow['sum(cb)'],2,'.','')."</td>";
                                            echo "</tr>";
                                            $slno = $slno + 1;
                                            $totalloan = $totalloan + $loanrow['sum(cb)'];
                                          }
                                        }
                                            echo "<tr>";
                                            echo "<td align='center'></td>";                                            
                                            echo "<td align='left'>Total</td>";
                                            echo "<td align='right'>".number_format($totalloan,2,'.','')."</td>";
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
														Recent Deposits
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
                                        <th  style="text-align: center;">Mem ID</th>
                                        <th  style="text-align: center;">Dep No</th>														
                                        <th  style="text-align: center;">Mem Name</th>
                                        <th  style="text-align: center;">Amount</th>														
                                      </tr>
                                    </thead>	

																		<tbody>
                                      <?php 
                                        if($depcount > 0){
                                          while($deprow = mysqli_fetch_assoc($recentdep)){
                                            echo "<tr>";
                                            echo "<td align = 'center'><a href='acc_mem_det.php?memid=".$deprow['memid']."'>".$deprow['memid']."</a></td>";
                                            echo "<td align = 'center'>".$deprow['depositno']."</td>";
                                            echo "<td align = 'left'>".$deprow['memname']."</td>";
                                            echo "<td align = 'right'>".number_format($deprow['ob'],2,'.','')."</td>";
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
                    
                    <div class="col-md-12 ">											
											

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Recent Loans
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
                                        <th  style="text-align: center;">Mem ID</th>														
                                        <th  style="text-align: center;">Loan No</th>														
                                        <th  style="text-align: center;">Mem Name</th>
                                        <th  style="text-align: center;">Amount</th>														
                                      </tr>
                                    </thead>	

																		<tbody>
                                      <?php 
                                        if($loancount > 0){
                                          while($loanrow = mysqli_fetch_assoc($recentloan)){
                                            echo "<tr>";
                                            echo "<td align = 'center'><a href='acc_mem_det.php?memid=".$loanrow['memid']."'>".$loanrow['memid']."</a></td>";
                                            echo "<td align = 'center'>".$loanrow['loanno']."</td>";
                                            echo "<td align = 'left'>".$loanrow['memname']."</td>";
                                            echo "<td align = 'right'>".number_format($loanrow['ob'],2,'.','')."</td>";
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
                    

                    
                    <div class="col-md-12 ">											
											

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														 Expenses Pending for Approval
                            <small><a href='acc_cb_expenses_all.php'> view all transactions </a></small>
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
                                            echo "<td align = 'center'><a href='acc_cb_expenses_view.php?expid=".$exprow['PaymentID']."'><button class'btn-primary'>".$exprow['PaymentID']."</button></a></td>";
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
		$('[id^=memtransferbutton]').click(function() 
		{   
            //$("table tr input").on('click', function(e){
            //alert($(this).closest('td').parent()[0].sectionRowIndex);
            //});​
            row = $(this).closest('td').parent()[0].sectionRowIndex;
            var memid = $('#memid'+row).text();				
			//alert(memid);
			$.ajax({  
				type: "POST",  
				url: "acc_mem_transfer_del.php",  
				data: "memid="+ memid, 
				success: function(response){ 																				
					location.reload();								
				} 
			}); 
			return false;
		});
        
        // $('.alltransfers').click(function() 
		// {   
        //     $.ajax({  
		// 		type: "POST",  
		// 		url: "acc_mem_transfer_all.php",  
		// 		success: function(response){
        //             //alert(response); 																				
		// 		    $('#memtransfers').html(response);
        //         } 
		// 	}); 
		// 	return false;
		// });
        $('[id^=transbutton]').click(function() 
		{   
            //alert("hello");
            //$("table tr input").on('click', function(e){
            //alert($(this).closest('td').parent()[0].sectionRowIndex);
            //});​
            row = $(this).closest('td').parent()[0].sectionRowIndex;
            var transid = $('#transid'+row).text();				
			//alert(transid);
			$.ajax({  
				type: "POST",  
				url: "acc_trans_cancel_del.php",  
				data: "transid="+ transid, 
				success: function(response){ 																				
					//alert(response);
                    location.reload();								
				} 
			}); 
			return false;
		});			
	});
</script>			