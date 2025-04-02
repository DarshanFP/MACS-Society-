<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");

if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
		$sql1 = "SELECT members.*, GroupName, GroupID from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
  
   
    $sql = mysqli_query($connection,"SELECT acc_sharecapital.memid, sum(receiptcash) as creceipt,sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                    FROM acc_sharecapital, acc_cashbook, acc_transactions WHERE acc_sharecapital.memid = '$memid' and acc_sharecapital.memid = acc_cashbook.accno AND 
                                    acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 14");
  
    $row = mysqli_fetch_assoc($sql);   
    $sccount = mysqli_num_rows($sql);
  
    $sql2 = mysqli_query($connection,"SELECT acc_deposits.depositno, acc_deposits.status, acc_deposits.memid, SubHead, sum(receiptcash) as creceipt,sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                    FROM acc_deposits, acc_subhead, acc_cashbook, acc_transactions WHERE acc_subhead.SubHeadModule = 4 AND acc_subhead.SubID = acc_deposits.subheadid AND acc_deposits.memid = '$memid' 
                                    AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.accno = acc_deposits.depositno AND acc_deposits.subheadid = acc_cashbook.subheadid GROUP BY acc_deposits.depositno");
    $count2 = mysqli_num_rows($sql2);

    $sql3 = mysqli_query($connection,"SELECT acc_loans.loanno, acc_loans.status, acc_loans.dateofissue, acc_loans.installment, acc_loans.ob, acc_loans.memid, SubHead, sum(receiptcash) as creceipt,sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                    FROM acc_loans, acc_subhead, acc_cashbook, acc_transactions WHERE acc_subhead.SubHeadModule = 3 AND acc_subhead.SubID = acc_loans.subheadid AND acc_loans.memid = '$memid' 
                                    AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.accno = acc_loans.loanno AND acc_loans.subheadid = acc_cashbook.subheadid GROUP BY acc_loans.loanno");
    $count3 = mysqli_num_rows($sql3);

    $others = mysqli_query($connection,"SELECT memid, acc_subhead.SubHead, subheadid, sum(receiptcash) + sum(receiptadj) as receipt, sum(paymentcash) + sum(paymentadj) as payment FROM acc_cashbook, acc_subhead, acc_transactions 
                                    WHERE acc_subhead.SubID = acc_cashbook.subheadid AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 and acc_cashbook.subheadid in (10,11,15,16) and memid = '$memid' GROUP by subheadid");
    $otherscount = mysqli_num_rows($others);
    
    }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header col-sm-10"" >
							<h1>
								 Member Details
								<small>
								<i class="ace-icon fa fa-angle-double-right"></i>
                                <?php
                                    if($sccount > 0){
                                        echo "<span><a href='acc_mem_receipt.php?memid=".$memid."'><button class='btn btn-primary'>Receipt</button></a></span>";
                                        echo "<span><a href='acc_mem_payment.php?memid=".$memid."'><button class='btn btn-success'>Cheque Payment</button></a></span>";                                
                                        echo "<span><a href='acc_mem_settle.php?memid=".$memid."'><button class='btn btn-danger'>Final Settlement</button></a></span>";
                                    }   
                                    else{
                                        echo "<span><button class='btn btn-primary' disabled>Receipt</button></a></span>";
                                        echo "<span><button class='btn btn-success'disabled>Cheque Payment</button></a></span>";                                                                        
                                    }                  
                                    
                                ?>	
                                <span style="float:right;"><a href="acc_rep_mem_ledger.php?memid=<?php echo $memid; ?>"><button class='btn btn-primary'>Ledger View</button></a></span>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-10">											

											<div class="space-12"></div>

											<div class="profile-user-info profile-user-info-striped">
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member ID </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $memid;?> </span>
                                                    </div>
                                                    <div class="profile-info-name"> Bank Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankname'];?> </span>
                                                    </div>
                          
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank IFSC</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankifsc'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
														<a href="acc_group_det.php?groupid=<?php echo $row1['GroupID']; ?>"><span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span></a>
                                                    </div>
                                                    <div class="profile-info-name">Bank Account No.</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaccountno'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Address</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaddress'];?> </span>
													</div>
												</div>

											</div>

											<div class="space-20"></div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Share Capital 
                                                    </h4>
                                                    <?php 
                                                        if($sccount > 0){
                                                            echo "<button class='btn btn-primary' style = 'float:right;' disabled>Share Capital</button>";
                                                        }
                                                        else{
                                                            echo "<a href='acc_mem_sharecapital.php?memid=".$memid."'><button class='btn btn-primary' style = 'float:right;'>Share Capital</button></a>";													
                                                        }
                                                    ?>
                                                    
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
																			<tr>													
																				<th class="detail-col">S.No.</th>
																				<th class="center">Member ID</th>
																				<th class="center">Total Share Capital</th>													
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            
                                                                                $slno = 1;                                        
																				echo "<tr><td class='center'>".$slno."</td>"; 
																				echo "<td class='center'><a href='acc_mem_sc_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";																				
																				$balance = $row['creceipt'] + $row['areceipt'] - $row['cpayment'] - $row['apayment'];
																				echo "<td class='center'>".number_format($balance,2,'.','')."</td></tr>";
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

                                            <div class="widget-box transparent">
												<div class="widget-header widget-header-small" style = "text-align:left;" >
													<h4 class="widget-title blue smaller" align ="left">
														<i class="ace-icon fa fa-rss orange"></i>
														Deposits 
													</h4>
                                                    <?php
                                                        if($sccount > 0){
                                                            echo "<a href='acc_mem_dep_new.php?memid=".$memid."'><button class='btn btn-primary' style = 'float:right;'>New Deposit</button></a>";
                                                            echo "<a href='acc_mem_dep_monthly_int.php?memid=".$memid."'><button class='btn btn-success' style = 'float:right;'>Monthly Interest</button></a>";                                                            
														}
                                                        else{
                                                            echo "<button class='btn btn-primary' style = 'float:right;' disabled>New Deposit</button>";  
                                                        }
                                                    ?>
														
												</div>

                        
												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
																			<tr>													
																				<th class="detail-col">S.No.</th>
																				<th class="center">Type of Deposit</th>
																				<th class="center">Deposit Number</th>													
                                                                                <th class="center">Balance</th>													
                                                                                <th class="center">Status</th>													
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            
                                                                         if($count2>0){
																			$slno=1;
																			while($row2 = mysqli_fetch_assoc($sql2))
																			{ 	
                                        
																				echo "<tr><td class='center'>".$slno."</td>"; 
																				echo "<td class='center'>".$row2['SubHead']."</td>";																				
																				echo "<td class='center'><a href='acc_mem_dep_det.php?depid=".$row2['depositno']."&memid=".$memid."'>".$row2['depositno']."</a></td>";
                                                                                $balance = $row2['creceipt'] + $row2['areceipt'] - $row2['cpayment'] - $row2['apayment'];
                                                                                echo "<td class='center'>".number_format($balance,2,'.','')."</td>";
                                                                                if($row2['status'] == 1 && $balance > 0)
                                                                                echo "<td class='center'>Active</td></tr>";
                                                                                else if($row2['status'] == 1 && $balance <= 0)
                                                                                echo "<td class='center'><a href='acc_mem_det_dep_close.php?depno=".$row2['depositno']."&memid=".$memid."'><button>Click to Close</button></a></td></tr>";
                                                                                else if($row2['status'] == 0)
                                                                                echo "<td class='center'>Closed</td></tr>";
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
                      
                      
                      
                      
                      
                                            <div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Loans 
													</h4>					
                                                    <?php 
                                                        if($sccount > 0){
                                                            echo "<a href='acc_mem_loan_new.php?memid=".$memid."'><button class='btn btn-primary' style = 'float:right;' >New Loan</button> </a>";
                                                        }
                                                        else{
                                                            echo "<button class='btn btn-primary' style = 'float:right;' disabled >New Loan</button>";
                                                        }
                                                    ?>
                                                    
												</div>
												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
																			<tr>													
																				<th class="detail-col">S.No.</th>
																				<th class="center">Type of Loan</th>
																				<th class="center">Loan Number</th>													
                                                                                <th class="center">Date of Issue</th>													
                                                                                <th class="center">Total Loan</th>													
                                                                                <th class="center">Balance</th>	
                                                                                <th class="center">Status</th>
                                                                                <th class="center">Installment</th>
                                                                                <th class="center"></th>
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            
                                                                        if($count3>0){
																			$slno=1;
																			while($row3 = mysqli_fetch_assoc($sql3))
																			{ 	
                                        
																				echo "<tr><td class='center'>".$slno."</td>"; 
																				echo "<td class='center'>".$row3['SubHead']."</td>";																				
																				echo "<td class='center'><a href='acc_mem_loan_det.php?loanno=".$row3['loanno']."&memid=".$memid."'>".$row3['loanno']."</a></td>";
                                                                                echo "<td class='center'>".date('d-m-Y',strtotime($row3['dateofissue']))."</td>";
                                                                                
                                                                                
                                                                                $balance =  $row3['cpayment'] + $row3['apayment'] - $row3['creceipt'] - $row3['areceipt'];
                                                                                echo "<td class='center'>".number_format($row3['ob'],2,'.','')."</td>";
                                                                                echo "<td class='center'>".number_format($balance,2,'.','')."</td>";
                                                                                if($row3['status'] == 1 && $balance > 0)
                                                                                echo "<td class='center'>Active</td>";
                                                                                else if($row3['status'] == 1 && $balance <= 0)
                                                                                echo "<td class='center'><a href='acc_mem_det_loan_close.php?loanno=".$row3['loanno']."&memid=".$memid."'><button >Click to Close</button></a></td>";
                                                                                else if($row3['status'] == 0)
                                                                                echo "<td class='center'>Closed</td>";
                                                                                echo "<td class='center'>".number_format($row3['installment'],2,'.','')."</td>";
                                                                                if($row3['status'] == 0)
                                                                                    echo "<td class='center'></td></tr>";
                                                                                else
                                                                                    echo "<td class='center'><a href='acc_mem_loan_enhance.php?memid=".$memid."&loanno=".$row3['loanno']."'><button class'btn-primary'>Enhance</button></a></td></tr>";
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



                                            <div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Others
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
																				<th class="detail-col">S.No.</th>
																				<th class="center">Head</th>
                                                                                <th class="center">Amount</th>													
                                                                                <th class="center"></th>													
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            if($otherscount > 0){
                                                                                $slno = 1;                                        
                                                                                while($rowother = mysqli_fetch_assoc($others)){
                                                                                    echo "<tr><td class='center'>".$slno."</td>"; 
                                                                                    echo "<td class='center'><a href='acc_mem_other_det.php?subid=".$rowother['subheadid']."&&memid=".$memid."'>".$rowother['SubHead']."</a></td>";
                                                                                    $amount= $rowother['receipt'] - $rowother['payment'];																				
                                                                                    echo "<td class='center'>".number_format($amount,2,'.','')."</td>";
                                                                                    echo "<td class='center'><a href='acc_mem_other_payment.php?memid=".$memid."&subid=".$rowother['subheadid']."'><button class'btn-primary'>Re-Pay</button></a></td></tr>";
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
                                                <a href="accounts_member.php"><button class="btn btn-primary" style="float:right;">
                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                </button></a>
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