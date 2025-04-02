<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_member";
	include("pdtsidepan.php");
	if(isset($_GET['memid'])){
		$_SESSION['temp'] = $_GET['memid'];
	}
	if(isset($_SESSION['temp'])){		
		$memid = $_SESSION['temp'];					
		$sql = "SELECT members.*,GroupName from members, groups WHERE memid='$memid'";
		$result = mysqli_query($connection, $sql) or die(mysqli_error());
		$row = mysqli_fetch_assoc($result);		
    
    $share = mysqli_query($connection,"SELECT balance FROM acc_sharecapital WHERE memid = '$memid'");
    $share = mysqli_fetch_assoc($share);
    $share = $share['balance'];
    
    
    $deposit = mysqli_query($connection,"SELECT sum(cb), sum(status) FROM acc_deposits WHERE memid = '$memid' GROUP BY memid");
    $deposit = mysqli_fetch_assoc($deposit);
    $depstatus = $deposit['sum(status)'];
    $deposit = $deposit['sum(cb)'];
    
    
    
    $loan = mysqli_query($connection,"SELECT sum(cb), sum(status) FROM acc_loans WHERE memid = '$memid' GROUP BY memid");
    $loan = mysqli_fetch_assoc($loan);
    $loanstatus = $loan['sum(status)'];
    $loan = $loan['sum(cb)'];
    
    
    
    
		unset($_SESSION['temp']);
		$count = 1;
	}
	else{
		header("location:president_member.php");
	}	
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Delete Member
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_mem_del_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member ID </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name ="memid" value="<?php echo $row['memid']; ?>" class="col-xs-10 col-sm-5" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['memname']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Gender</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['gender']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Birth </label>

										<div class="col-sm-4">
											<input type="date" id="form-field-1" value="<?php echo $row['dob']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Date of Joining </label>

										<div class="col-sm-4">
											<input type="date" id="form-field-1" value="<?php echo $row['doj']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" id="form-field-6" disabled><?php echo $row['memaddress']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" value="<?php echo $row['memphone']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
                      <?php 
                        if($share > 0 || $deposit > 0 || $loan >0 || $loanstatus > 0 || $depstatus > 0){
                          echo '<button class="btn btn-danger" type="button" disabled>
                                  <i class="ace-icon fa fa-close bigger-110"></i>
                                  Delete
                                </button>';    
                        }
                        else {
                          echo '<button class="btn btn-danger" type="submit" onclick="return confirm(\'Are you sure to Delete Member, there is no revert!\')">
                                  <i class="ace-icon fa fa-close bigger-110"></i>
                                  Delete
                                </button>';    
                        }
                      
                      ?>
                      
											
											<a href="president_member.php"><button class="btn btn-info" type="button">
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>		
                      <?php 
                        if($share > 0 || $deposit > 0 || $loan >0){
                            echo "<span style='color:red;'>First you have clear all dues/ recoveries pertaining to member</span>"; 
                        }                     
                      ?>
										</div>
									</div>
								</form>		
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<?php 
	include("footer.php");    
?>			