<?php	    
	include("accounts_session.php");
	//$_SESSION['curpage']="accounts_member";
    include("accountssidepan.php");
    if(isset($_GET['groupid'])){
        $_SESSION['curpage']="accounts_group";
        $groupid=$_GET['groupid'];
        $count1=1;
    }
    else{
        $_SESSION['curpage']="accounts_member";
    }
    if(isset($_GET['memid'])){
		$_SESSION['temp'] = $_GET['memid'];
	}
	if(isset($_SESSION['temp'])){		
		$memid = $_SESSION['temp'];					
		$sql = "SELECT members.*, GroupName, groups.GroupID FROM members, groups WHERE members.memgroupid = groups.GroupID AND members.memid = '$memid'";
		$result = mysqli_query($connection, $sql) or die(mysqli_error($sql));
		$row = mysqli_fetch_assoc($result);
		unset($_SESSION['temp']);		
	}
	else{
		header("location:president_member.php");
	}	
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Edit Member
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="acc_mem_edit_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="empid"> Member ID </label>

										<div class="col-sm-7">
											<input type="text" id="memid" name="memid" value="<?php echo $row['memid']; ?>" class="col-xs-10 col-sm-5" readonly />
                                            <input type="hidden" id="count1" name="count1" value="<?php echo $count1; ?>" class="col-xs-10 col-sm-5"/>											
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member Name </label>

										<div class="col-sm-7">
											<input type="text" id="memname" name="memname" value="<?php echo $row['memname']; ?>" class="col-xs-10 col-sm-5" readonly />
										</div>
									</div>								
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Gender</label>

										<div class="col-sm-4">
                                        <input type="text" id="gender" name="gender" value="<?php echo $row['gender']; ?>" class="col-xs-10 col-sm-5" readonly />
											
										</div>
									</div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Birth </label>

										<div class="col-sm-4">
											<input type="date" id="dob" name="dob" value="<?php echo $row['dob']; ?>" class="col-xs-10 col-sm-5" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Group </label>

										<div class="col-sm-7">
                                        <input type="text" id="group" name="group" value="<?php echo $row['GroupName']; ?>" class="col-xs-10 col-sm-5" readonly />
									                    											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Aadhaar No. </label>

										<div class="col-sm-4">
											<input type="text" id="aadhaar" name="aadhaar" value="<?php echo $row['aadhaar']; ?>" class="col-xs-10 col-sm-5" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Member Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" name="address" id="form-field-6" ><?php echo $row['memaddress']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" id="mobile" minlength="10" maxlength="10" name = "cell" value="<?php echo $row['memphone']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile">Bank Name</label>

										<div class="col-sm-6">
											<input type="text" id="bankname" name = "bankname" value="<?php echo $row['bankname']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile">Bank IFSC</label>

										<div class="col-sm-6">
											<input type="text" id="bankifsc"  minlength="11" maxlength="11" name = "bankifsc" value="<?php echo $row['bankifsc']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile">Bank Account No.</label>

										<div class="col-sm-6">
											<input type="text" id="bankaccountno" name = "bankaccountno" value="<?php echo $row['bankaccountno']; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Bank Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" name="bankaddress" id="form-field-6" ><?php echo $row['bankaddress']; ?></textarea>
										</div>
									</div>
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" type="submit" id="edit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
                                            <?php
                                            if($count1==1){
                                                echo '<a href="acc_group_det.php?groupid='.$groupid.'"><button class="btn btn-info" type="button">';
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
<script>
    $(document).ready(function(){
        $('#mobile,#aadhaar,#bankaccountno').keypress(function(e) {
	      if(isNaN(this.value+""+String.fromCharCode(e.charCode))) return false;
        })
        .on("cut copy paste",function(e){
	    e.preventDefault();
        });
    });
</script>            