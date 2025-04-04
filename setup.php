<?php
   include("config.php");
   session_start();
   $error = " ";   

   
   if($_SERVER["REQUEST_METHOD"] == "POST") {     
      
      $name = $_POST['name'];
      $shortname = $_POST['shortname']; 
      $address = $_POST['address']; 
      $clusterid = "C".$shortname."101";
      $userid = $shortname."admin";
      $today = date("Y-m-d");
      mysqli_query($connection,"START TRANSACTION");
      $master = mysqli_query($connection,"INSERT INTO master (`name`, `shortform`, `address`) VALUES ('$name','$shortname','$address')");
      $cluster = mysqli_query($connection,"INSERT INTO `cluster`(`ClusterID`, `ClusterName`, `Address`, `Status`) VALUES ('$clusterid','Head Office','$address',1)");
      $user = mysqli_query($connection,"INSERT INTO `users`(`userid`, `password`, `roleid`, `userstatus`, `userdate`, `userempid`) VALUES ('$userid','admin123',1,1,'$today','initial')");
      $allot = mysqli_query($connection,"INSERT INTO `allot`(`EmpID`, `ClusterID`, `DOE`, `Status`) VALUES ('$userid','$clusterid','$today',1)");
      $clusterbal = mysqli_query($connection,"INSERT INTO `acc_cluster_balance`(`ClusterID`, `Balance`) VALUES ('$clusterid',0)");
      if($master && $cluster && $user && $clusterbal){
        mysqli_query($connection,"COMMIT");
        header("location: index.php");	 
      }
      else{
        mysqli_query($connection,"ROLLBACK");
      }
       
   }
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>MACS Application Setup</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="icon" href="bank.ico" type="image/ico"/> 

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>									
									<span class="red">MACS</span>
									<span class="white" id="id-text2">Application</span>
								</h1>
								<h4 class="blue" id="id-company-text">Setup</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h5 class="header blue lighter bigger">												
												Enter MACS Primary Details for Setup
											</h5>

											<div class="space-6"></div>

											<form method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="name" class="form-control" placeholder="MACS Name" autocomplete="off" required />															
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="shortname" class="form-control" placeholder="MACS Short Name" autocomplete="off" required />															
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="address" class="form-control" placeholder="MACS Address" autocomplete="off" required />															
														</span>
													</label>
                                                    

													<div class="space"></div>

													<div class="clearfix">
														<!-- <label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> Remember Me</span>
														</label> -->

														<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">															
															<span class="bigger-110">Setup </span>
														</button>
														<p style="color:red"><?php echo $error; ?></p>
													</div>
													
													<div class="space-4"></div>
												</fieldset>
											</form>

											<!-- <div class="social-or-login center">
												<span class="bigger-110">Or Login Using</span>
											</div>

											<div class="space-6"></div>

											<div class="social-login center">
												<a class="btn btn-primary">
													<i class="ace-icon fa fa-facebook"></i>
												</a>

												<a class="btn btn-info">
													<i class="ace-icon fa fa-twitter"></i>
												</a>

												<a class="btn btn-danger">
													<i class="ace-icon fa fa-google-plus"></i>
												</a>
											</div> -->
										</div>

										<!-- <div class="toolbar clearfix">
											<div>
												 <a href="#" data-target="#forgot-box" class="forgot-password-link">
													 <i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password 
												</a>
											</div>

											<div>
												 <a href="#" data-target="#signup-box" class="user-signup-link">
													I want to register
													<i class="ace-icon fa fa-arrow-right"></i>
												</a>
											</div>
										</div>-->
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
								
								
							</div><!-- /.position-relative -->


						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
		</script>
	</body>
</html>
