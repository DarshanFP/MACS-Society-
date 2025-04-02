<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_ob";
	include("pdtsidepan.php");

  if(isset($_GET['subid'])){
    $subid = $_GET['subid'];
    $subhead = mysqli_query($connection, "SELECT SubHead FROM acc_subhead WHERE SubID = '$subid'");
    $subhead = mysqli_fetch_assoc($subhead);
    $subhead = $subhead['SubHead'];
  }
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balance : <?php echo $subhead; ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							
                <form class="form-horizontal" role="form" method="post" action="president_ob_heads_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Opening Balance </label>

										<div class="col-sm-7">
											<input type="number" id="form-field-1" name="ob" placeholder="Opening Balance" class="col-xs-10 col-sm-5" autocomplete="off" required />
                      <input type="hidden" value="<?php echo $subid; ?>" name="subid" />
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