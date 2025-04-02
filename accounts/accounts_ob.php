<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_ob";
	include("accountssidepan.php");
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balance
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							  <a href="accounts_ob_cash.php" class="btn btn-app btn-success"> <i class=" bigger-230"></i> Cash </a>
                <a href="accounts_ob_members.php" class="btn btn-app btn-primary"> <i class=" bigger-230"></i> Mem Bal </a>                
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			