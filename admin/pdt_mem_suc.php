<?php	    
	include("pdt_session.php");
	//$_SESSION['curpage']="president_member";
	include("pdtsidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$name = $_POST['name'];
        $count1 = $_POST['count1'];
        $gender = $_POST['gender'];
        $aadhaar = $_POST['aadhaar'];
        $memid = $_POST['memberid'];
		$dob = $_POST['dob'];
		$doj = $_POST['doj'];
		$groupid = $_POST['group'];
		$address = $_POST['address'];
        $cell = $_POST['cell'];
        $bankname = $_POST['bankname'];
        $bankifsc = $_POST['bankifsc'];
        $bankaccountno = $_POST['bankaccountno'];
        $bankaddress = $_POST['bankaddress'];
        $nominee = $_POST['nomineename'];
        $relation = $_POST['nomineerelation'];		
		$memid = "M".$macsshortform.$memid;
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
        if($count1==1){
            $_SESSION['curpage']="president_group";
        }
        else{
            $_SESSION['curpage']="president_member";
        } 
        
    $sql4 = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID = '$groupid'");
    $row4 = mysqli_fetch_assoc($sql4);
		
		
		mysqli_query($connection, "start transaction");
		$sql1 = "INSERT INTO `members` (`memid`, `memname`, `gender`,`aadhaar`, `dob`, `memaddress`,`memphone`,`bankname`,`bankifsc`,`bankaccountno`,`bankaddress`,`nominee`,`relation`,`doj`,`memstatus`,`memgroupid`, `mementrydate`) 
				VALUES ('$memid', '$name', '$gender','$aadhaar', '$dob', '$address','$cell','$bankname','$bankifsc','$bankaccountno','$bankaddress','$nominee','$relation','$doj', 1, '$groupid', '$today')";
		$result1 = mysqli_query($connection,$sql1) or die(mysqli_error($sql1));	
		
		$sql3 = "INSERT INTO `users` (`userid`, `password`, `roleid`, `userstatus`, `userdate`, `userempid`) VALUES ('$memid', '123456', 5, 1,'$today', '$user')";
		$result3 = mysqli_query($connection,$sql3) or die(mysqli_error($sql3));

        if($result1 && $result3){
		    mysqli_query($connection, "commit");
        }
        else{
            mysqli_query($connection, "rollback");
        }
	}
	else {
		header("location:president_member.php");	
	}		
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Member Added Successfully
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member ID </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $memid; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member Name </label>

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
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Aadhaar</label>

										<div class="col-sm-7">
												<input type="text" id="form-field-1" value="<?php echo $aadhaar; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Birth </label>

										<div class="col-sm-4">
												<input type="text" id="form-field-1" value="<?php echo $dob; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Date of Joining </label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" value="<?php echo $doj; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Group </label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" value="<?php echo $row4['GroupName']; ?>" class="col-xs-10 col-sm-5" disabled />		
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
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-7">Bank Name </label>

										<div class="col-sm-6">
											<input type="text" id="form-field-1" value="<?php echo $bankname; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Bank IFSC </label>

										<div class="col-sm-6">
											<input type="text" id="form-field-1" value="<?php echo $bankifsc; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Bank Account No. </label>

										<div class="col-sm-6">
											<input type="text" id="form-field-1" value="<?php echo $bankaccountno; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Bank Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" id="form-field-6" disabled><?php echo $bankaddress; ?></textarea>
										</div>
									</div>
									
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
                                            <?php
                                                if($count==1){											
											        echo '<a href="pdt_group_det.php?groupid='.$groupid.'"><button class="btn btn-info" type="button">';
                                                }
                                                else{
                                                    echo '<a href="president_member.php"><button class="btn btn-info" type="button">';
                                                }
                                            ?>    
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