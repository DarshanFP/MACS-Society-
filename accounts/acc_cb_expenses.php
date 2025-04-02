<?php	  
		include("accounts_session.php");
	$_SESSION['curpage']="accounts_cashbook";
	include("accountssidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$today = $_SESSION['backdate'];
	$subid = $_POST['subid'];
	$amount = $_POST['amount'];
	$details = $_POST['details'];	
	
	
	$trans = mysqli_query($connection, "SELECT * FROM acc_cash_dummy_expenses");
 	$transcount = mysqli_num_rows($trans);
  $transcount = 1001 + $transcount;	
 	$transid = "EXP".$transcount;
 	
	mysqli_query($connection,"start transaction");	
 	
 	
	
	//Cash Book Entry Start			
	$sql1 = mysqli_query($connection,"INSERT INTO acc_cash_dummy_expenses (`date`, `clusterid`,`groupid`, `PaymentID`, `subheadid`, `details`, `paymentcash`,`remarks`,`status`,`entryempid`,`timedate`)
						VALUES('$today','$clusterid','','$transid', '$subid', '$details','$amount','$details',0,'$user','$timedate')"); 
  
  
  
	//Cash Book Entry End


	
	if($sql1){
		mysqli_query($connection,"commit");
	}
	else{
		mysqli_query($connection,"rollback");
	}
	
	$sql4 = mysqli_query($connection,"SELECT acc_subhead.SubHead, acc_cash_dummy_expenses.paymentcash, acc_cash_dummy_expenses.details 
                          FROM acc_subhead, acc_cash_dummy_expenses 
                          WHERE acc_cash_dummy_expenses.subheadid = acc_subhead.SubID 
                          AND acc_cash_dummy_expenses.PaymentID = '$transid' ");
	$result4 = mysqli_fetch_assoc($sql4);
}
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Payment Entry sent for approval with Payment ID:
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									    <?php echo $transid;?>
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
														
													</div>										
													<div class="col-md-4">
														<div class="form-group label-floating">												

															<a href="accounts_cashbook.php"><button type="button" class="btn btn-info btn-fill pull-right"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
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