<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_ledgerseup";
	include("accountssidepan.php");
	if(isset($_GET['subhead'])){
		$_SESSION['temp'] = $_GET['subhead'];
	}
	if(isset($_SESSION['temp'])){		
		$subhead = $_SESSION['temp'];
	}
	unset($_SESSION['temp']);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Setup Ledger
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="acc_ledgersetup_suc.php">
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Subhead</label>

										<div class="col-sm-9">
											<input type="text" id="form-field-1" name="subhead" value="<?php echo $subhead; ?>" class="col-xs-10 col-sm-5" readonly />	
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3">Rate of interest</label>

										<div class="col-sm-7">
											<input type="number" id="form-field-3" name="roi" placeholder="Enter Rate of interest"  class="col-xs-7 col-sm-5" autocomplete="off" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3">Date of effect</label>

										<div class="col-sm-7">
											<input type="date" id="form-field-3" name="doe" placeholder="Enter Date of effect"  class="col-xs-7 col-sm-5" autocomplete="off" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Details</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="details" placeholder="Details" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
									</div>
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button id="submit" class="btn btn-success" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
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