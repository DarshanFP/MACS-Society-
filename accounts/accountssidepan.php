<?php 
    $user = $_SESSION['login_user']; 
    $sql = "SELECT * FROM employee WHERE empid = '$user' AND empstatus = 1";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    $sql1 = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $row1 = mysqli_fetch_assoc($sql1);

    $clusterid = $row1['ClusterID'];

    $name = mysqli_query($connection,"SELECT * FROM master");
    $name = mysqli_fetch_assoc($name);  
    $macsshortname = $name['shortform'];  
    
    $set_group_concat_max_len = mysqli_query($connection,"SET GLOBAL group_concat_max_len=4096");
    $set_only_full_group_by = mysqli_query($connection,"SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

    $_SESSION['start'] = time();
    if($_SESSION['expire'] > $_SESSION['start'])
      $_SESSION['expire'] = $_SESSION['start'] + (15*60);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>MACS - Emp Panel </title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="../assets/css/jquery-ui.custom.min.css" />
		<link rel="stylesheet" href="../assets/css/chosen.min.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker3.min.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-timepicker.min.css" />
		<link rel="stylesheet" href="../assets/css/daterangepicker.min.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.min.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-colorpicker.min.css" />
		<link rel="stylesheet" href="../assets/css/popup.css" />
		<link rel="stylesheet" href="../assets/css/popup1.css" />
		<link rel="stylesheet" href="../assets/css/jquery-ui.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="../assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />
        <link rel="icon" href="../bank.ico" type="image/ico"/> 
		
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="../assets/js/ace-extra.min.js"></script>
		
		<script src="../assets/js/jquery-2.1.4.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
	<script>
    $(document).ready(function(){      
		// Set the date we're counting down to
    var sessiontime;
    var now;    
      
		  now = new Date();
      now = now.getTime();
      sessiontime = now + (15*60);
    
    
		// var countDownDate = new Date("Jan 5, 2018 15:37:25").getTime();

		// Update the count down every 1 second
		var x = setInterval(function timer() {

			// Get todays date and time
			
			
			// Find the distance between now an the count down date
			var distance = sessiontime - now;
			now = now * 1;
			now = now + 1;
			// Time calculations for days, hours, minutes and seconds
			var minutes = Math.floor((distance % (60 * 60)) / ( 60));
			var seconds = Math.floor((distance % (60)) );
			
			// Output the result in an element with id="demo"
			   document.getElementById("session").innerHTML = minutes + "m " + seconds + "s " ;
			// If the count down is over, write some text 
			if(distance <=0 ){
				alert("Your Session Expired");
				clearInterval(x);
				window.location.href = "sessionexpire.php";
			}
			
		}, 1000);		
  });		
	</script>
		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default          ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="accounts.php" class="navbar-brand">
						<small stype="'font-size:8;">
							<i class="fa fa-bank"></i>
							<?php echo $name['shortform']." MACS, ".$name['address']; ?> - Emp Panel - Cluster : 
              <?php 
                echo $row1['ClusterName']; 
                date_default_timezone_set('Asia/Kolkata');
              
                  echo " - <span ";
                  if(isset($_SESSION['backdate'])){ 
                    if($_SESSION['backdate'] == date("Y-m-d")){
                      echo "style='color:white'";
                    }
                    else{
                      echo "style='color:red'";
                    }
                  }
                  else{
                    echo "style='color:white'";
                  }

                  if(isset($_SESSION['backdate'])){ 

                    } 
                    else{
                      $today=date("Y-m-d"); 
                      $_SESSION['backdate'] = $today;
                    }   
                    echo ">";
                    echo date("d-m-Y",strtotime($_SESSION['backdate']));              
                    echo "</span>";
                    $today = $_SESSION['backdate'];
              ?>
                  
						</small>
					</a>
          <a href="accounts_today.php"><button class="btn " style="margin-left:110px;">Today</button></a>									 	
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">						

						<li class="green dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<span id="session" style="padding:20px">  </span>								
							</a>							
						</li>
						
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="../assets/images/avatars/profile-pic.jpg" alt="Jason's Photo" />								
								<span class="user-info">
									<small>Welcome,</small>
									<?php echo $row['empname']; ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="accounts_pass_change.php">
										<i class="ace-icon fa fa-cog"></i>
										Change Password
									</a>
								</li>

								<li>
									<a href="accounts_profile.php">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="logout.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
				    
					<li <?php if($_SESSION['curpage']=="accounts"){echo "class = 'active'";} ?>>
						<a href="accounts.php">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard  </span>
						</a>

						<b class="arrow"></b>
					</li>					


					<li <?php if($_SESSION['curpage']=="accounts_member"){echo "class = 'active'";} ?>>
						<a href="accounts_member.php">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> Members </span>
						</a>
						<b class="arrow"></b>
					</li>
					<li <?php if($_SESSION['curpage']=="accounts_group"){echo "class = 'active'";} ?>>
						<a href="accounts_group.php">
							<i class="menu-icon fa fa-group"></i>
							<span class="menu-text"> Groups </span>
						</a>
						<b class="arrow"></b>
					</li>
					
          <li <?php if($_SESSION['curpage']=="accounts_cashtr" ){echo "class = 'active'";}  ?>>
						<a href="accounts_cashtr.php">
							<i class="menu-icon fa fa-inr"></i>
							<span class="menu-text"> Cash Transfer </span>

						</a>
          </li>
						
					<li <?php if($_SESSION['curpage']=="accounts_cashbook" ){echo "class = 'active'";}  ?>>
						<a href="accounts_cashbook.php">
							<i class="menu-icon fa fa-calculator"></i>
							<span class="menu-text"> Cash Book </span>

						</a>
          </li>  
          <li <?php if($_SESSION['curpage']=="accounts_loans" ){echo "class = 'active'";}  ?>>
						<a href="accounts_loans.php">
							<i class="menu-icon fa fa-exchange"></i>
							<span class="menu-text"> Loan Module </span>

						</a>
          </li>  


						
					<!-- <li <?php if($_SESSION['curpage']=="accounts_profit"){echo "class = 'active'";} ?>>
						<a href="accounts_profit.php">
							<i class="menu-icon fa fa-money"></i>
							<span class="menu-text"> Profit Allocation </span>
						</a>
						<b class="arrow"></b>						
					</li> -->

                    <li <?php if($_SESSION['curpage']=="accounts_trans" ){echo "class = 'active'";}  ?>>
						<a href="acc_trans_view.php">
							<i class="menu-icon fa fa-check-square"></i>
							<span class="menu-text"> Transaction View </span>

						</a>
                    </li>

					
					<li <?php if($_SESSION['curpage']=="accounts_reports" ){echo "class = 'active'";} ?>>
						<a href="accounts_reports.php" >
							<i class="menu-icon fa fa-bar-chart"></i>
							<span class="menu-text"> Reports </span>

							<b class="arrow "></b>
						</a>
						
					</li>
          
 					<li <?php if($_SESSION['curpage']=="accounts_backdated"){echo "class = 'active'";} ?>>
						<a href="accounts_backdated.php">
							<i class="menu-icon fa fa-arrow-left"></i>
							<span class="menu-text"> Back Date </span>
						</a>
						<b class="arrow"></b>
					</li>
          
          <li <?php if($_SESSION['curpage']=="accounts_ob"){echo "class = 'active'";} ?>>
						<a href="accounts_ob.php">
							<i class="menu-icon fa fa-history"></i>
							<span class="menu-text"> Opening Balances </span>
						</a>
						<b class="arrow"></b>
					</li>


					<li <?php if($_SESSION['curpage']=="logout"){echo "class = 'active'";} ?>>
						<a href="logout.php">
							<i class="menu-icon fa fa-sign-out"></i>

							<span class="menu-text">
								Logout
							</span>
						</a>
						<b class="arrow"></b>
						
					</li>
					
				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

			