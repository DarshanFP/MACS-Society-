<?php	  
  include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if(isset($_GET['cashid'])){	
	$cashid = $_GET['cashid'];
  $sql4 = mysqli_query($connection,"SELECT cluster.ClusterName, acc_cash_dummy_transfer.paymentcash, acc_cash_dummy_transfer.receiptcash, acc_cash_dummy_transfer.details 
                          FROM cluster, acc_cash_dummy_transfer 
                          WHERE acc_cash_dummy_transfer.groupid = cluster.ClusterID 
                          AND acc_cash_dummy_transfer.CashTrID = '$cashid' 
                          AND acc_cash_dummy_transfer.clusterid = '$clusterid'");
	$result4 = mysqli_fetch_assoc($sql4);
}
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Pending for approval with Payment ID :  <?php echo $cashid;?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									   
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-8"> <!-- PAGE CONTENT BEGINS -->
                
	                    
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label>Cluster Name </label>
															<input type="text" class="form-control" value="<?php echo $result4['ClusterName'] ?>" readonly >
														</div>
													</div>                
                          <div class="col-md-3">
														<div class="form-group">
															<label>Type </label>
                              <?php 
                                if($result4['paymentcash'] > 0)
                                  echo "<input type='text' class='form-control' value='Payment Sent' readonly >";
                                else
                                  echo "<input type='text' class='form-control' value='Payment Received' readonly >";
                              ?>
															
														</div>
													</div>                
                          
													<div class="col-md-3">
														<div class="form-group">
															<label>Amount </label>
															<?php 
                                if($result4['paymentcash'] > 0)
                                  echo "<input type='text' class='form-control' value='".$result4['paymentcash']."' readonly >";
                                else
                                  echo "<input type='text' class='form-control' value='".$result4['receiptcash']."' readonly >";
                              ?>

														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label>Details </label>
															<input type="text" class="form-control" value="<?php echo $result4['details'] ?>" readonly >
														</div>
													</div>	
												</div>

												<div class="row">   
													<div class="col-md-8">
														<div class="form-group label-floating">												

															<a href="accounts.php"><button type="button" class="btn btn-info btn-fill pull-left"><i class="fa fa-arrow-left" style="padding-right:5px;" aria-hidden="true"></i>Back</button></a>
														</div>
													</div>										
													<div class="col-md-4">
														<div class="form-group label-floating">	
                              <?php 
                                if($result4['paymentcash'] > 0)
                                  echo "<a href='accounts_cash_transfer_cancel.php?cashid=".$cashid."'><button type='button' class='btn btn-info btn-fill pull-right'>Cancel</button></a>";
                                else
                                  echo "<a href='accounts_cash_transfer_accept.php?cashid=".$cashid."'><button type='button' class='btn btn-info btn-fill pull-right'>Accept</button></a>";
                              ?>

															
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