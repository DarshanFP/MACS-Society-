<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_loans";
    include("accountssidepan.php");
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $newloan = mysqli_query($connection, "SELECT acc_loan_dummy.*, SubHead FROM acc_loan_dummy, acc_subhead WHERE id = '$id' 
                            AND acc_loan_dummy.subid = acc_subhead.SubID");   
    $newloan = mysqli_fetch_assoc($newloan);
    $memid = $newloan['memid'];
    $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
    $result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);  

    $others = mysqli_query($connection,"SELECT acc_loan_income.*, SubHead FROM acc_loan_income, acc_subhead
                                    WHERE acc_subhead.SubHeadModule = 11 AND acc_subhead.SubID = acc_loan_income.SubID AND MemID ='$memid'
                                    AND acc_loan_income.status = 0");
    $otherscount = mysqli_num_rows($others);
  
  
}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 New Loan 								
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
														Loan Details 
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
																				<th class="center">Loan Type</th>
																				<th class="center">Previous Loan No</th>
																				<th class="center">Previous Amount</th>													
                                                                                <th class="center">Disbursed Amount</th>													
                                                                                <th class="center">Total Amount</th>											
                                                                                <th class="center">Installment</th>													
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
																				echo "<tr><td class='center'>".$newloan['SubHead']."</td>"; 
                                                                                echo "<td class='center'>".$newloan['loanno']."</td>";																				
                                                                                echo "<td class='center'>".$newloan['loanbalance']."</td>";																				
                                                                                echo "<td class='center'>".$newloan['proposedloan']."</td>";
                                                                                $total = $newloan['loanbalance'] + $newloan['proposedloan'];
                                                                                echo "<td class='center'>".$total."</td>";																				
																				echo "<td class='center'>".$newloan['installment']."</td></tr>";
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
														Loan Related Charges - <span style="color :red;"><?php if($otherscount ==0) echo "No Documentation Charges Collected from Member"; ?></span>
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
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            if($otherscount > 0){
                                                                                $slno = 1;                                        
                                                                                while($rowother = mysqli_fetch_assoc($others)){
                                                                                    echo "<tr><td class='center'>".$slno."</td>"; 
                                                                                    echo "<td class='center'>".$rowother['SubHead']."</td>";																				
                                                                                    echo "<td class='center'>".$rowother['Amount']."</td></tr>";
                                                                                    
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
                                                <a href="acc_loan_prop_view.php?clustpropid=<?php echo $newloan['ClusterPropID'];?>"><button class="btn btn-primary" style="float:left;">
                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                </button></a>
                                                <?php 
                                                    if($otherscount == 0){
                                                        echo "<button class='btn btn-primary' style='float:right;' disabled>Generate</button>";
                                                    }
                                                    else{
                                                        echo "<a href='acc_loan_prop_loanno_suc.php?id=".$id."'><button class='btn btn-primary' style='float:right;'>
                                                                Generate</button></a>";
                                                    }
                                                ?>
                                                
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