<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president";
	include("pdtsidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if(isset($_GET['expid'])){	
	$expid = $_GET['expid'];
  $sql4 = mysqli_query($connection,"SELECT acc_subhead.SubHead, acc_cash_dummy_expenses.*, ClusterName 
                          FROM acc_subhead, acc_cash_dummy_expenses, cluster 
                          WHERE acc_cash_dummy_expenses.subheadid = acc_subhead.SubID 
                          AND acc_cash_dummy_expenses.clusterid = cluster.ClusterID
                          AND acc_cash_dummy_expenses.PaymentID = '$expid' ");
	$result4 = mysqli_fetch_assoc($sql4);
  
  mysqli_query($connection,"START TRANSACTION");
  $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
  $transcount = mysqli_num_rows($trans);
  $transcount = 1001 + $transcount;	
  $transid = "T".$macsshortform.$transcount;


  $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`) 
                                VALUES ('$transid',1)") or die(mysqli_error($connection));
  
  $clusterid = $result4['clusterid'];
  $subheadid = $result4['subheadid'];
  $details = $result4['details'];
  $amount = $result4['paymentcash'];    
  $remarks = $expid;
  $empid = 'admin';
  
  
  $expreject = mysqli_query($connection,"UPDATE acc_cash_dummy_expenses SET status = 1, TransID = '$transid' WHERE PaymentID = '$expid' ");	
  $sql5 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `TransID`, `subheadid`, `details`, `paymentcash`,`remarks`,`entryempid`)
						VALUES('$today','$clusterid', '$transid', '$subheadid', '$details','$amount','$remarks','$empid')") or die(mysqli_error($connection));	
  
  $balance = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance - $amount WHERE ClusterID = '$clusterid'");
  if($transinsert && $expreject && $sql5 &&$balance){
     mysqli_query($connection,"COMMIT"); 
  }
  else{
     mysqli_query($connection,"ROLLBACK"); 
  }
}
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Payment ID :  <?php echo $expid;?> Sucessfully accepted with <i class="ace-icon fa fa-angle-double-right"></i> TransID: <?php echo $transid;?> 
								<small>
									
									   
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-8"> <!-- PAGE CONTENT BEGINS -->
                
	                    <form >
												<div class="row">
                          <div class="col-md-3">
														<div class="form-group">
															<label>Cluster </label>
															<input type="text" class="form-control" value="<?php echo $result4['ClusterName']; ?>" readonly >
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label>Sub Head </label>
															<input type="text" class="form-control" value="<?php echo $result4['SubHead']; ?>" readonly >
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label>Amount </label>
															<input type="text" class="form-control" value="<?php echo $result4['paymentcash']; ?>" readonly >

														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label>Details </label>
															<input type="text" class="form-control" value="<?php echo $result4['details']; ?>" readonly >
														</div>
													</div>	
												</div>

												<div class="row">   
													<div class="col-md-8">
														
													</div>										
													<div class="col-md-4">
														<div class="form-group label-floating">												

															<a href="president.php"><button type="button" class="btn btn-info btn-fill pull-right">Back</button></a>
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