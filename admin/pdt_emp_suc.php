<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_employee";
	include("pdtsidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$name = $_POST['name'];
        $gender = $_POST['gender'];
        $aadhaar = $_POST['aadhaar'];
		$dob = $_POST['dob'];
		$doj = $_POST['doj'];
		$address = $_POST['address'];
		$cell = $_POST['cell'];
		$sql = "select * from employee";
		$result = mysqli_query($connection, $sql) or die(mysqli_error($sql));
		$count = mysqli_num_rows($result);	
		$count = 101 + $count;
		$empid = "E".$macsshortform.$count;
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
    
   	
		mysqli_query($connection, "start transaction");
		$sql1 = "INSERT INTO `employee` (`empid`, `empname`, `empgender`, `empaadhaar`,`empdob`,`empdoj`, `empaddress`,`empmobile`, `empstatus`, `empdate`, `empuserid`) 
				VALUES ('$empid', '$name', '$gender', '$aadhaar','$dob', '$doj', '$address','$cell', 1, '$today', '$user')";
		$result1 = mysqli_query($connection,$sql1) or die(mysqli_error($sql1));	
		
		
		$sql3 = "INSERT INTO `users` (`userid`, `password`, `roleid`, `userstatus`, `userdate`, `userempid`) 
				VALUES ('$empid', '123456', 2, 1,'$today', '$user')";
		$result3 = mysqli_query($connection,$sql3) or die(mysqli_error($sql3));
    
    $sql4 = "INSERT INTO `allot` (`EmpID`, `ClusterID`, `DOE`,  `Status`) 
				VALUES ('$empid','', '$today', 0)";
		$result4 = mysqli_query($connection,$sql4) or die(mysqli_error($sql4));
  
        if($result1 && $result3 && $result4){
            mysqli_query($connection, "commit");
        }
        else{
            mysqli_query($connection, "rollback"); 
        }    
	}
	else {
		header("location:president_employee.php");	
	}		
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Employee Added Successfully
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee ID </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $empid; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $name; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Gender</label>

										<div class="col-sm-7">
												<input type="text" id="form-field-1" value="<?php echo $gender; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Birth </label>

										<div class="col-sm-4">
																		<input type="text" id="form-field-1" value="<?php echo $dob; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Designation </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $desg; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Date of Joining </label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" value="<?php echo $doj; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" id="form-field-6" disabled><?php echo $address; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" value="<?php echo $cell; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">											
											<a href="president_employee.php"><button class="btn btn-info" type="button">
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>											
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