<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
  
    $sql = mysqli_query($connection,"SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE accno = '$memid' and subheadid = 14 AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 ");
    $count = mysqli_num_rows($sql);
   }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Share Capital Statement 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
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
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
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
                          <span style="float:right"><a href="acc_mem_sc_payment.php?memid=<?php echo $memid; ?>"><button class="btn btn-primary">
                              Payment
                              </button></a></span>
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
																				<th class="center">Trans ID</th>
																				<th class="center">Date</th>
                                        <th class="center">Opening</th>
                                        <th class="center">Receipt</th>
                                        <th class="center">Payment</th>
                                        <th class="center">Closing</th>
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                        if($count>0){
                                          $slno = 1;                  
                                          $ob = 0;
                                          while($row = mysqli_fetch_assoc($sql)){
                                            $recept = $row['receiptcash']+$row['receiptadj'];
                                            $payment = $row['paymentcash']+$row['paymentadj'];
                                            $cb = $ob + $recept - $payment;
                                            echo "<tr><td class='center'>".$slno."</td>";
                                            echo "<td class='center'>".$row['TransID']."</td>";
																				    echo "<td class='center'>".$row['date']."</td>";
                                            echo "<td class='center'>".number_format($ob,2)."</td>";
                                            echo "<td class='center'>".number_format($recept,2)."</td>";
                                            echo "<td class='center'>".number_format($payment,2)."</td>";
																				    echo "<td class='center'>".number_format($cb,2)."</td></tr>";
                                            $ob = $cb;
                                            
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
                        <a href="acc_mem_det.php?memid=<?php echo $memid; ?>"><button class="btn btn-primary" style="float:right;">
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