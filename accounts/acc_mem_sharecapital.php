<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($sql1));
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
  
    $sql = mysqli_query($connection,"SELECT acc_deposits.*, SubHead FROM acc_deposits, acc_subhead
                                    WHERE acc_subhead.SubHeadModule = 2 AND acc_subhead.SubID = acc_deposits.subheadid");
  
    $row = mysqli_fetch_assoc($sql);   

  
    $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule = 2");
    $count2 = mysqli_num_rows($sql2);


  
    	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Share Capital Receipt 
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

											<form class="form-horizontal" role="form" method="post" action="acc_mem_sharecapital_suc.php">
                        

                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Share Capital amount </label>

                                                <div class="col-sm-5">
                                                    <input type="text" id="form-field-1" name="scamount" placeholder="Share Capital Amount" class="col-xs-10 col-sm-5" autocomplete="off" required />
                                                    <input type="hidden" id="form-field-1" name="memid" value="<?php echo $memid; ?>" >
                                                </div>
                                                </div>

                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Admission Fee </label>

                                                <div class="col-sm-5">
                                                    <input type="text" id="form-field-1" name="admissionfee" placeholder="Admission Fee" class="col-xs-10 col-sm-5" autocomplete="off" required />
                                                </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Receipt </label>

                                                <div class="col-sm-5">
                                                    <input type="date" id="form-field-3" name="dor" placeholder="mm/dd/yyyy"  class="col-xs-10 col-sm-5" autocomplete="off" required />
                                                </div>
                                                </div>
                                                
                                                

                                                <div class="clearfix form-group">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button id="submit" class="btn btn-success" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    Submit
                                                    </button>
                                                    <a href="acc_mem_det.php?memid=<?php echo $memid; ?>"><button class="btn btn-info" type="button">
                                                    <i class="ace-icon fa fa-arrow-left bigger-110"></i>
                                                    Back
                                                    </button></a>											
                                                </div>
                                                </div>
                                            </form>	
	
												
                      
                      
                      
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