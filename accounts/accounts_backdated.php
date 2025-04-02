<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");
    $test = 0;
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $test = 0;
    $cashbook = $_POST['cashdate'];
    $_SESSION['backdate'] = $cashbook;
    $test = 1;
  }

?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Select Date
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<span id="bookdate"> <?php  if($test == 1){echo "- Date Set as: ".date("d-m-Y", strtotime($_SESSION['backdate']));}?></span>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" method="post">
                  <label style="margin-left:110px">Select Date </label>
                  <input type="date" name="cashdate" id="cashbookdate" style="font-size:11pt;height:30px;width:160px;" required>	
                  <button type="submit" class = "btn btn-info" id="cashbook">Set</button>																		

                </form>	

                
                
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			