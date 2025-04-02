<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_ledgersetup";
	include("accountssidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$subhead = $_POST['subhead'];
		$roi = $_POST['roi'];
		$details = $_POST['details'];
		$doe = $_POST['doe'];
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
		
		$sql = "SELECT subid FROM acc_subhead WHERE subhead='$subhead'";
		$result = mysqli_query($connection,$sql);
		$row = mysqli_fetch_array($result);
		$subid = $row['subid'];
				
		$sql1 = "INSERT INTO `acc_rateofinterest` (`subheadid`, `roi`, `details`, `doe`, `status`, `entrydate`,`entryempid`) 
				VALUES ('$subid', '$roi', '$details','$doe', 1,'$today','$user')";
		$result1 = mysqli_query($connection,$sql1);	
		
		$sql2 = "select * from acc_rateofinterest";
		$result2 = mysqli_query($connection,$sql2);
		$row2 = mysqli_fetch_array($result2);
	}
	else {
		header("location:accounts_ledgersetup.php");	
	}		
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Ledger Setup Successful
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Subhead</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $subhead; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Rate of interest</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row2['roi']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date of effect</label>

										<div class="col-sm-7">
											<input type="date" id="form-field-1" value="<?php echo $row2['doe']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>	
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Details</label>

										<div class="col-sm-7">
												<input type="text" id="form-field-1" value="<?php echo $row2['details']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">											
											<a href="accounts_ledgersetup.php"><button class="btn btn-info" type="button">
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