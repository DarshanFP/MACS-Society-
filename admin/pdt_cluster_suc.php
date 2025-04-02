<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_macs";
	include("pdtsidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['mobile'];
//    $macsid = $_POST['macs'];
		$sql = "select * from cluster";
		$result = mysqli_query($connection, $sql);
		$count = mysqli_num_rows($result);	   
		$count = 101 + $count;
		$clusterid = "C".$macsshortform.$count;
        
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
        mysqli_query($connection,"START TRANSACTION");
		$clusterinsert = "INSERT INTO `cluster` (`ClusterID`, `ClusterName`, `Address`, `Mobile`, `Status`) 
				VALUES ('$clusterid', '$name', '$address','$phone',1)";
        $result1 = mysqli_query($connection,$clusterinsert);	
        $clusterbalinsert = mysqli_query($connection,"INSERT INTO `acc_cluster_balance` (`ClusterID`, `Balance`) VALUES ('$clusterid',0)");
    
        if($clusterinsert && $clusterbalinsert){
            mysqli_query($connection,"COMMIT");
        }
        else{
            mysqli_query($connection,"ROLLBACK");
        }
  //  $sql2 = mysqli_query($connection,"SELECT Name FROM MACS WHERE MACSID = '$macsid'");
  //  $row2 = mysqli_fetch_assoc($sql2);
				
	}
	else {
		header("location:president_cluster.php");	
	}		
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Cluster Added Successfully
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cluster ID </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $clusterid; ?>" class="col-xs-10 col-sm-5" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cluster Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-2" value="<?php echo $name; ?>" class="col-xs-10 col-sm-5" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Address</label>

										<div class="col-sm-7">
												<input type="text" id="form-field-3" value="<?php echo $address; ?>" class="col-xs-10 col-sm-5" readonly />		
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3">Phone</label>

										<div class="col-sm-4">
										        <input type="text" id="form-field-4" value="<?php echo $phone; ?>" class="col-xs-10 col-sm-5" readonly />		
										</div>
									</div>
                  
<!--                   <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> MACS </label>

										<div class="col-sm-7">
												<input type="text" id="form-field-3" value="<?php //echo $row2['Name']; ?>" class="col-xs-10 col-sm-5" readonly />		
										</div>
									</div>
									 -->
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">											
											<a href="president_cluster.php"><button class="btn btn-info" type="button">
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