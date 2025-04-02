<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
	if(isset($_GET['memid'])){
		$_SESSION['temp'] = $_GET['memid'];
	}
	if(isset($_SESSION['temp'])){		
		$memid = $_SESSION['temp'];					
		$sql = "SELECT
				  members.memid,
				  members.memname,
				  members.gender,
				  members.dob,
				  members.memaddress,
				  members.memphone,
				  members.doj,
				  members.memnominee,
				  members.memrelation,
				  memmonitoring.memdesg,
				  memmonitoring.ddoid
				FROM members,memmonitoring
				WHERE members.memid = memmonitoring.memid AND members.memid='$memid' AND members.memstatus = 1 AND memmonitoring.memmonstatus =1";
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);		
		unset($_SESSION['temp']);
		$count = 1;
	}
	else{
		header("location:accounts_member.php");
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
								<form class="form-horizontal" role="form" method="post" action="acc_mem_del_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member ID </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['memid']; ?>" class="col-xs-10 col-sm-5" disabled />
											<input type="hidden" id="form-field-1" value="<?php echo $row['memid']; ?>" name = "memid" class="col-xs-10 col-sm-5"/>		
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
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Designation </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['memdesg']; ?>" class="col-xs-10 col-sm-5" disabled />		
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
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Nominee Name</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['memnominee']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Relation </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['memrelation']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure to Delete Member, there is no revert!')">
												<i class="ace-icon fa fa-close bigger-110"></i>
												Delete
											</button>
											<a href="accounts_member.php"><button class="btn btn-info" type="button">
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>											
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