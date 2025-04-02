<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_ob";
	include("pdtsidepan.php");
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balances
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							  <a href="president_ob_cash.php" class="btn btn-app btn-success"> <i class=" bigger-230"></i> Cash </a>
                <a href="president_ob_heads.php" class="btn btn-app btn-primary"> <i class=" bigger-230"></i> Heads </a>
                <a href="president_ob_dues.php" class="btn btn-app btn-warning"> <i class=" bigger-230"></i> Due to/by </a>
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			