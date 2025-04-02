<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_ledgersetup";
	include("pdtsidepan.php");
	if(isset($_GET['subhead'])){
		$_SESSION['temp'] = $_GET['subhead'];
	}
	if(isset($_SESSION['temp'])){		
		$subhead = $_SESSION['temp'];					
		$sql = "select subhead,roi,acc_rateofinterest.details from acc_subhead,acc_rateofinterest where subhead='$subhead' and subid=subheadid and acc_rateofinterest.status=1";
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		unset($_SESSION['temp']);		
	}
	else{
		header("location:accounts_ledgersetup.php");
	}	
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Edit Rate of Interest 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_ledgersetup_edit_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="subhead">Subhead</label>

										<div class="col-sm-7">
											<input type="text" id="subhead" name="subhead" value="<?php echo $row['subhead']; ?>" class="col-xs-10 col-sm-5" readonly />											
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Rate of interest</label>

										<div class="col-sm-7">
											<input type="text" id="roi" name="roi" value="<?php echo $row['roi']; ?>" class="col-xs-10 col-sm-5" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3">Details</label>

										<div class="col-sm-4">
											<input type="text" id="details" name="details" value="<?php echo $row['details']; ?>" class="col-xs-10 col-sm-5" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date of effect</label>

										<div class="col-sm-7">
											<input type="date" id="doe" name="doe" value="<?php echo $row['doe']; ?>" class="col-xs-10 col-sm-5" required />
										</div>
									</div>
																		
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" type="submit" id="edit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
											<a href="president_ledgersetup.php"><button class="btn btn-info" type="button">
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