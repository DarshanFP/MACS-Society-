<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_ob";
	include("accountssidepan.php");

  $cashob = mysqli_query($connection, "SELECT sum(OB) FROM acc_subhead_ob, acc_subhead 
                                          WHERE acc_subhead.SubHeadModule = 10 
                                                AND acc_subhead.SubID = acc_subhead_ob.SubID
                                                AND acc_subhead_ob.ClusterID='$clusterid' GROUP BY ClusterID");
  $cashob = mysqli_fetch_assoc($cashob);
  $cashob = $cashob['sum(OB)'];
  if(!isset($_GET['id'])){
    if($cashob != ''){    
      header("location:accounts_ob_cash_suc.php");
    }  
  }
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balance : Cash
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="post" action="accounts_ob_cash_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cash OB </label>

										<div class="col-sm-7">
											<input type="number" id="form-field-1" value ="<?php echo $cashob; ?>" name="cashob" placeholder="Cash Balance" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
									</div>
                  
                  <div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button id="submit" class="btn btn-success" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
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