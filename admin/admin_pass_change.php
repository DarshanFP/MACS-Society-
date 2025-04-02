<?php	    
	include("admin_session.php");
	$_SESSION['curpage']="admin";
	include("adminsidepan.php");
	$error = "";
	$error1 = ""; 
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$user = $_SESSION['login_user'];
		$pass = $_POST['cpass'];
		$npass = $_POST['npass'];
		$rpass = $_POST['rpass'];
		$pass = $connection->query("SELECT * FROM users WHERE userid='$user' AND password = '$pass' AND userstatus = 1");
		$count = $pass->num_rows;
		if($count == 1){
			if($npass == $rpass){
				$sql = $connection->query("UPDATE users SET password='$npass' WHERE userid='$user' AND userstatus = 1");
				$error1 = "Password Succesfully updated";
			}
			else{
				$error = "Passwords not matched";
			}
		}
		else {
			$error = "Invalid Current Password";
		}		
	}
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Change Password
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Current Password </label>

										<div class="col-sm-9">
											<input type="password" id="form-field-1" name="cpass" placeholder="Current Password" minlength="6" class="col-xs-10 col-sm-5" />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Password </label>

										<div class="col-sm-9">
											<input type="password" id="form-field-2" name="npass" placeholder="New Password" minlength="6" class="col-xs-10 col-sm-5" />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Repeat Password </label>

										<div class="col-sm-9">
											<input type="password" id="form-field-3" name="rpass" placeholder="Repeat Password" minlength="6" class="col-xs-10 col-sm-5" />
										</div>
									</div>
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
											<a href="president.php"><button class="btn btn-info" type="button">
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>
											<p><label style = "color:red"><?php echo $error;  ?></label>
											<label style = "color:green"><?php echo $error1;  ?></label></p>	
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