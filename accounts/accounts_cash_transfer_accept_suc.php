<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");

  if(isset($_GET['cashid'])){	
	$cashid = $_GET['cashid'];
  $sql4 = mysqli_query($connection,"SELECT cluster.ClusterName, acc_cash_dummy_transfer.*
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
								Cash Transfer ID:<?php echo $cashid; ?> Successfully accepted with TransID:<?php echo $result4['TransID']; ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-8"> <!-- PAGE CONTENT BEGINS -->
                
	                    
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label>Cluster Name </label>
															<input type="text" class="form-control" value="<?php echo $result4['ClusterName'] ?>" readonly >
														</div>
													</div>                                          
                          
													<div class="col-md-4">
														<div class="form-group">
															<label>Amount </label>
															<input type="text" class="form-control" value="<?php echo $result4['receiptcash'] ?>" readonly >

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
                              <a href='accounts.php'><button type='button' class='btn btn-info btn-fill pull-right'>Back</button></a>
															
														</div>
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