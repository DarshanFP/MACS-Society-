<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_member";
include("pdtsidepan.php");;
if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
		$sql1 = "SELECT members.*, GroupName, ClusterName from members, groups, cluster 
    where memid = '$memid' and members.memgroupid = groups.GroupID and cluster.ClusterID = groups.ClusterID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
  
     $sql = mysqli_query($connection,"SELECT * FROM acc_sharecapital WHERE memid = '$memid'");
  
    $row = mysqli_fetch_assoc($sql);   

  
    $sql2 = mysqli_query($connection,"SELECT acc_deposits.*, SubHead FROM acc_deposits, acc_subhead
                                    WHERE acc_subhead.SubHeadModule = 4 AND acc_subhead.SubID = acc_deposits.subheadid AND memid = '$memid'");
    $count2 = mysqli_num_rows($sql2);

    $sql3 = mysqli_query($connection,"SELECT acc_loans.*, SubHead FROM acc_loans, acc_subhead
                                    WHERE acc_subhead.SubHeadModule = 3 AND acc_subhead.SubID = acc_loans.subheadid AND memid = '$memid'");
    $count3 = mysqli_num_rows($sql3);

  
    	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Member Details
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<span><a href="pdt_rep_mem_ledger.php?memid=<?php echo $memid; ?>"><button class='btn btn-primary'>Ledger View</button></a></span>
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

											         
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Cluster Name </div>
													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['ClusterName'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name"></div>

													<div class="profile-info-value">
														<span class="editable" id="username"></span>
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
																				echo "<td class='center'><a href='pdt_mem_acc_det.php?accno=".$row['memid']."'>".$row['memid']."</a></td>";																				
																				echo "<td class='center'>".$row['balance']."</td>";
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
                          
<!-- 														<button class="btn btn-primary" style = "float:right;" >
                              New Deposit
                             </button>   -->
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
																				echo "<td class='center'><a href='pdt_mem_acc_det.php?accno=".$row2['depositno']."&memid=".$memid."'>".$row2['depositno']."</a></td>";
                                        echo "<td class='center'>".$row2['cb']."</td>";
                                        if($row2['status'] == 1 && $row2['cb'] > 0)
                                          echo "<td class='center'>Active</td></tr>";
                                        else if($row2['status'] == 1 && $row2['cb'] <= 0)
                                          echo "<td class='center'><button>Ready to Close</button></td></tr>";
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
                          
<!--                           <button class="btn btn-primary" style = "float:right;" >
                              New Loan
                            </button>  
												</div>
                         -->
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
																				echo "<td class='center'><a href='pdt_mem_acc_det.php?accno=".$row3['loanno']."&memid=".$memid."'>".$row3['loanno']."</a></td>";
                                        echo "<td class='center'>".$row3['dateofissue']."</td>";
                                        echo "<td class='center'>".$row3['ob']."</td>";
                                        echo "<td class='center'>".$row3['cb']."</td>";
                                        if($row3['status'] == 1 && $row3['cb'] > 0)
                                          echo "<td class='center'>Active</td></tr>";
                                        else if($row3['status'] == 1 && $row3['cb'] <= 0)
                                          echo "<td class='center'><button >Ready to Close</button></td></tr>";
                                        else if($row3['status'] == 0)
                                          echo "<td class='center'>Closed</td></tr>";
																				$slno = $slno +1;					
																			}				
																		}
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
                                                                <a href="president_member.php"><button class="btn btn-primary" style="float:right;">
                                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                                </button></a>
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