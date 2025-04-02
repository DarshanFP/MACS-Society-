<?php	    
	include("accounts_session.php");
	//$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");	
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $memid = $_POST['memid'];
        $count1 = $_POST['count1'];
        $groupid = $_POST['group'];
        $aadhaar = $_POST['aadhaar'];		
		$address = $_POST['address'];	
        $cell = $_POST['cell'];
        $bankname = $_POST['bankname'];
        $bankifsc = $_POST['bankifsc'];
        $bankaccountno = $_POST['bankaccountno'];
        $bankaddress = $_POST['bankaddress'];
		$today = date("Y-m-d");
        $user = $_SESSION['login_user'];
        if($count==1){
            $_SESSION['curpage']="accounts_group";
        }
        else{
            $_SESSION['curpage']="accounts_member";
        }	
		
				
		mysqli_query($connection, "start transaction");
				
		$sql3 = "UPDATE members SET aadhaar = '$aadhaar', memaddress = '$address', memphone = '$cell' ,bankname = '$bankname', bankifsc='$bankifsc',bankaccountno='$bankaccountno',bankaddress='$bankaddress' WHERE memid = '$memid' AND memstatus = 1";
		$result3 = mysqli_query($connection, $sql3) or die(mysqli_error($sql3));

        $sql4 = "SELECT members.*,GroupID,GroupName from members, groups WHERE GroupID=memgroupid AND memid='$memid'";
		$result4 = mysqli_query($connection, $sql4) or die(mysqli_error($sql4));
		$row4 = mysqli_fetch_assoc($result4);

    if($result3)
        mysqli_query($connection, "COMMIT");
    else
        mysqli_query($connection, "ROLLBACK");
	}
	else{
		header("location:accounts_member.php");
	}	
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
										Member Successfully Edited								
								
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" >
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="empid"> Member ID </label>

										<div class="col-sm-7">
											<input type="text" id="memid" value="<?php echo $row4['memid']; ?>" class="col-xs-10 col-sm-5" disabled />											
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Member Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row4['memname']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>								
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Gender</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row4["gender"]; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date Of Birth</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row4["dob"]; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Group </label>
											
										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row4["GroupName"]; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6">Aadhaar No.</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row4["aadhaar"]; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6">Member Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" id="form-field-6" disabled ><?php echo $row4["memaddress"]; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" minlength="10" maxlength="10" id="form-field-1" value="<?php echo $row4["memphone"]; ?>" class="col-xs-10 col-sm-5" disabled />											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile">Bank Name</label>

										<div class="col-sm-6">
											<input type="text" id="bankname" name = "bankname" value="<?php echo $row4['bankname']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile">Bank IFSC</label>

										<div class="col-sm-6">
											<input type="text" id="bankifsc"  minlength="11" maxlength="11" name = "bankifsc" value="<?php echo $row4['bankifsc']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile">Bank Account No.</label>

										<div class="col-sm-6">
											<input type="text" id="bankaccountno" name = "bankaccountno" value="<?php echo $row4['bankaccountno']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Bank Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" name="bankaddress" id="form-field-6" ><?php echo $row4['bankaddress']; ?></textarea>
										</div>
									</div>
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
                                            <?php
                                            if($count1==1){
                                                echo '<a href="acc_group_det.php?groupid='.$row4['GroupID'].'"><button class="btn btn-info" type="button">';
                                            }
                                            else{											
                                                echo '<a href="accounts_member.php"><button class="btn btn-info" type="button">';
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