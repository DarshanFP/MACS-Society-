<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
    include("accountssidepan.php");
    if(isset($_GET['groupid'])){
      $groupid = $_GET['groupid'];
      $sql1 = mysqli_query($connection,"SELECT * FROM groups WHERE ClusterID = '$clusterid' AND GroupID != '$groupid'");
	}
    
    if(isset($_GET['memid'])){
        $_SESSION['temp'] = $_GET['memid'];
    }
	if(isset($_SESSION['temp'])){		
		$memid = $_SESSION['temp'];					
		$sql = "SELECT members.*, GroupName, groups.GroupID FROM members, groups WHERE members.memgroupid = groups.GroupID AND members.memid = '$memid'";
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		unset($_SESSION['temp']);		
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
								Member transfer
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="acc_mem_transfer_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="empid"> Member ID </label>

										<div class="col-sm-7">
											<input type="text" id="memid" name="memid" value="<?php echo $row['memid']; ?>" class="col-xs-10 col-sm-5" readonly />
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
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Present Group </label>

										<div class="col-sm-7">
                                        <input type="text" id="pgroup" name="pgroup" value="<?php echo $row['GroupName']; ?>" class="col-xs-10 col-sm-5" readonly />
                                        <input type="hidden" id="pgroupid" name="pgroupid" value="<?php echo $row['GroupID']; ?>" class="col-xs-10 col-sm-5" />
									                    											
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Transfer to </label>

										<div class="col-sm-7">
                                            <select name="tgroupid" id = "tgroupid" class="col-xs-10 col-sm-5" autocomplete="off" required>
                                                <?php
                                                    echo "<option></option>";
                                                    while($row1 = mysqli_fetch_assoc($sql1)){
                                                        echo "<option value = ".$row1['GroupID'].">".$row1['GroupName']."</option>";
                                                    }
                                                ?>
                                            </select>                											
										</div>
                                    </div>
                                    
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" type="submit" id="edit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
                                            <a href="acc_mem_transfer.php"><button class="btn btn-info" type="button">
                                            
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
            