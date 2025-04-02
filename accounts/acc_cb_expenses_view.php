<?php	  
		include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if(isset($_GET['expid'])){	
	$expid = $_GET['expid'];
  $sql4 = mysqli_query($connection,"SELECT acc_subhead.SubHead, acc_cash_dummy_expenses.paymentcash, acc_cash_dummy_expenses.details 
                          FROM acc_subhead, acc_cash_dummy_expenses 
                          WHERE acc_cash_dummy_expenses.subheadid = acc_subhead.SubID 
                          AND acc_cash_dummy_expenses.PaymentID = '$expid' ");
	$result4 = mysqli_fetch_assoc($sql4);
}
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Pending for approval with Payment ID :  <?php echo $expid;?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									   
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-8"> <!-- PAGE CONTENT BEGINS -->
                
	                    <form >
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label>Select Head </label>
															<input type="text" class="form-control" value="<?php echo $result4['SubHead'] ?>" readonly >
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Amount </label>
															<input type="text" class="form-control" value="<?php echo $result4['paymentcash'] ?>" readonly >

														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Details </label>
															<input type="text" class="form-control" value="<?php echo $result4['details'] ?>" readonly >
														</div>
													</div>	
												</div>

												<div class="row">   
													<div class="col-md-8">
														<div class="form-group label-floating">												

															<a href="accounts.php"><button type="button" class="btn btn-info btn-fill pull-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
														</div>
													</div>										
													<div class="col-md-4">
														<div class="form-group label-floating">												

															<a href="acc_cb_expenses_cancel.php?expid=<?php echo $expid; ?>"><button type="button" class="btn btn-info btn-fill pull-right">Cancel</button></a>
														</div>
													</div>                            
												</div>
											</form>                
                
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			