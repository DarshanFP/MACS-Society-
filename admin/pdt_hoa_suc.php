<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_hoa";
	include("pdtsidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$subhead = $_POST['subhead'];
		$details = $_POST['details'];
		$ledgermodule = $_POST['ledgermodule'];
		$majorhead = $_POST['majorhead'];
		$sql1 = "select majorid from acc_majorheads where majorhead='$majorhead'";
		$result1 = mysqli_query($connection, $sql1);
        $row1 = mysqli_fetch_assoc($result1);
		$majorid = $row1['majorid'];
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];		
		
    
		mysqli_query($connection,"start transaction");
			
		$sql2 = "INSERT INTO `acc_subhead` (`SubHead`, `SubHeadDesc`, `SubHeadModule`, `MajorID`,`Status`) 
				     VALUES ( '$subhead', '$details', '$ledgermodule','$majorid',1)";
		$result2 = mysqli_query($connection,$sql2);	
		
        $subid = mysqli_insert_id($connection);
        
		if($ledgermodule==3 || $ledgermodule==4){
            $rateofint = mysqli_query($connection,"INSERT INTO `acc_rateofinterest` (`subheadid`, `roi`, `details`, `status`, `doe`,`entrydate`,`entryempid`) 
                VALUES ('$subid', 0 , '$subhead', 1, '$today', '$today', '$user')");            
        }
        
        if($ledgermodule==3 || $ledgermodule==4 ){	
		    if($result2 && $rateofint){
                mysqli_query($connection,"commit");
            }
            else{
                mysqli_query($connection,"rollback");    
            }
        }
        else if($result2){
                mysqli_query($connection,"commit");
        }
        else{
            mysqli_query($connection,"rollback");
        }
    
		
		$sql3 = "select acc_subhead.*, acc_subhead_module.ModuleType from acc_subhead, acc_subhead_module where acc_subhead.SubID = '$subid' and acc_subhead.SubHeadModule = acc_subhead_module.ModuleID";
		$result3 = mysqli_query($connection,$sql3);
		$row3 = mysqli_fetch_assoc($result3);
	}
	else {
		header("location:accounts_hoa.php");	
	}		
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								SubHead Added Successfully
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Subhead ID </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row3['SubID']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">SubHead</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row3['SubHead']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Details</label>

										<div class="col-sm-7">
												<input type="text" id="form-field-1" value="<?php echo $row3['SubHeadDesc']; ?>" class="col-xs-10 col-sm-5" disabled />	
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Ledger Module</label>

										<div class="col-sm-7">
											<input type="number" id="form-field-1" value="<?php echo $row3['ModuleType']; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
								
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5">Majorhead</label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" value="<?php echo $majorhead; ?>" class="col-xs-10 col-sm-5" disabled />		
										</div>
									</div>
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">											
											<a href="president_hoa.php"><button class="btn btn-info" type="button">
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