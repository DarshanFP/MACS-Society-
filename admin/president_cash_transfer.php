<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president_cashtr";
	include("pdtsidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if($_SERVER["REQUEST_METHOD"] == "POST"){	
	$clusterto = $_POST['cluster'];
	$amount = $_POST['amount'];
	$details = $_POST['details'];	
	
	
	$trans = mysqli_query($connection, "SELECT * FROM acc_cash_dummy_transfer");
 	$transcount = mysqli_num_rows($trans);
  $transcount = 1001 + ($transcount/2);	
 	$transid = "Cash".$transcount;
 	
	mysqli_query($connection,"start transaction");	
  
  $subid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule = 10");
  $subid = mysqli_fetch_assoc($subid);
  $subid = $subid['SubID'];
 	
 	
	
	//Cash Book Entry Start			
	$sql1 = mysqli_query($connection,"INSERT INTO acc_cash_dummy_transfer (`date`, `clusterid`,`groupid`, `CashTrID`, `subheadid`, `details`, `paymentcash`,`remarks`,`status`,`entryempid`,`timedate`)
						VALUES('$today','$clusterid','$clusterto','$transid', '$subid', '$details','$amount','$details',0,'$user','$timedate')"); 
  
  $sql2 = mysqli_query($connection,"INSERT INTO acc_cash_dummy_transfer (`date`, `clusterid`,`groupid`, `CashTrID`, `subheadid`, `details`, `receiptcash`,`remarks`,`status`,`entryempid`,`timedate`)
						VALUES('$today','$clusterto','$clusterid','$transid', '$subid', '$details','$amount','$details',0,'$user','$timedate')"); 
	//Cash Book Entry End


	
	if($sql1 && $sql2 ){
		mysqli_query($connection,"commit");
	}
	else{
		mysqli_query($connection,"rollback");
	}
	
	$sql4 = mysqli_query($connection,"SELECT cluster.ClusterName, acc_cash_dummy_transfer.paymentcash, acc_cash_dummy_transfer.details 
                          FROM cluster, acc_cash_dummy_transfer 
                          WHERE acc_cash_dummy_transfer.groupid = cluster.ClusterID 
                          AND acc_cash_dummy_transfer.CashTrID = '$transid' ");
	$result4 = mysqli_fetch_assoc($sql4);
}
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Cash Transfer Entry sent for approval with Cash Transfer ID:
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
															<input type="text" class="form-control" value="<?php echo $result4['ClusterName'] ?>" readonly >
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

															<a href="president.php"><button type="button" class="btn btn-info btn-fill pull-right"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
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