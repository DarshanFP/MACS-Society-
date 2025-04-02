<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_dues";
	include("pdtsidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$id = $_POST['id'];
    $head = $_POST['head'];
		$details = $_POST['details'];
    $remarks = $_POST['remarks'];
		$user = $_SESSION['login_user'];		
    if($id == 1){
    $subheadid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule = 5");    
    }
    else{
      $subheadid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule = 6");
    }
    $subheadid = mysqli_fetch_assoc($subheadid);
    $subheadid = $subheadid['SubID'];
		
    $dues = mysqli_query($connection,"SELECT * FROM acc_dues");
    $dues = mysqli_num_rows($dues);
    $dues = 101 + $dues;
    $duesid = "Due".$dues;    
		
		$dueinsert = mysqli_query($connection,"INSERT INTO `acc_dues`(`duesid`, `duestype`, `duesname`, `subheadid`, `balance`, `status`, `details`, `remarks`, `empid`) 
      VALUES ('$duesid','$id','$head','$subheadid',0,1,'$details','$remarks','$user')");	
		
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
                <?php 
                  if($id == 1)
                    echo "Due to Head Added Successfully";
                  else
                    echo "Due by Head Added Successfully";
                ?>
								
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Head Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $head; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Details</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $details; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Remarks</label>

										<div class="col-sm-7">
												<input type="text" id="form-field-1" value="<?php echo $remarks; ?>" class="col-xs-10 col-sm-5" disabled />	
										</div>
									</div>
									
									
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">											
											<a href="president_dues.php"><button class="btn btn-info" type="button">
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