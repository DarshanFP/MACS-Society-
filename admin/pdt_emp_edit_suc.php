<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_employee";
	include("pdtsidepan.php");	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$empid = $_POST['empid'];	
		$desg = $_POST['desg'];	
		$empstatus = $_POST['empstatus'];	
		$address = $_POST['address'];	
		$cell = $_POST['cell'];	
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];	
		$sql = "SELECT
				  employee.empid,
				  employee.empname,
				  employee.empaddress,
				  employee.empmobile,
				  empmonitoring.designation,
				  empmonitoring.empworkstatus,
				  empstatus.statusdesc,
				  empstatus.statusid				  
				FROM
				  employee
				  INNER JOIN empmonitoring ON employee.empid = empmonitoring.empid
				  INNER JOIN empstatus ON empmonitoring.empworkstatus = empstatus.statusid
				WHERE
				  employee.empid='$empid' AND employee.empstatus = 1 AND empmonitoring.monstatus =1";
				  
			  
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		
		
		mysqli_query($connection, "start transaction");
		if($row['designation'] != $desg || $row['empworkstatus'] != $empstatus){
			$sql1 = "UPDATE empmonitoring SET dol = '$today', monstatus = 0 WHERE empid = '$empid' AND monstatus = 1";
			$result1 = mysqli_query($connection, $sql1);
			
			$sql2 = "INSERT INTO empmonitoring (`empid`, `designation`, `doj`, `empworkstatus`, `monstatus`, `mondate`, `monempid`) 
					 VALUES ('$empid', '$desg',DATE_ADD('$today', INTERVAL 1 DAY), '$empstatus', 1, '$today', '$user')";
            $result2 = mysqli_query($connection, $sql2);
            if($result1 && $result2){
               mysqli_query($connection, "COMMIT");     
            }
            else{
               mysqli_query($connection, "ROLLBACK");  
            }		 
		}
		
		if($row['empaddress'] != $address || $row['empmobile'] != $cell){
			$sql3 = "UPDATE employee SET empaddress = '$address', empmobile = '$cell' WHERE empid = '$empid' AND empstatus = 1";
            $result3 = mysqli_query($connection, $sql3);
        }    
		
		
		$sql4 = "SELECT * FROM empstatus WHERE statusid = '$empstatus'";
		$result4 = mysqli_query($connection, $sql4);
		$row4 = mysqli_fetch_assoc($result4);
		
		
	}
	else{
		header("location:president_employee.php");
	}	
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								<?php 
									if($row['designation'] == $desg && $row['empworkstatus'] == $empstatus && $row['empaddress'] == $address && $row['empmobile'] == $cell){
										echo "No Changes are made in Employee Edit";
									}
									else {
										echo "Employee Successfully Edited";
									}
								
								 ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" >
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="empid"> Employee ID </label>

										<div class="col-sm-7">
											<input type="text" id="empid" value="<?php echo $row['empid']; ?>" class="col-xs-10 col-sm-5" disabled />											
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['empname']; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>								
									
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Designation </label>
											
										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $desg; ?>" class="col-xs-10 col-sm-5" disabled />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Status </label>

										<div class="col-sm-7">																																	
											<input type="text" id="form-field-1" value="<?php echo $row4['statusdesc']; ?>" class="col-xs-10 col-sm-5" disabled />											
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" id="form-field-6" disabled ><?php echo $address; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" minlength="10" maxlength="10" id="form-field-1" value="<?php echo $cell; ?>" class="col-xs-10 col-sm-5" disabled />											
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
<script>		
		$(document).ready(function()
		{ 	$("#mobilestatus").hide();
			$('#mobile').keyup(function() 
			{   
				var mobile = $("#mobile").val();
				var empid = $("#empid").val();				
				if(mobile.length == 10 )
				{
					$.ajax({  
						type: "POST",  
						url: "mobilecheck.php",  
						data: { empid: empid,
								mobile: mobile }, 
						success: function(count){ 																				
							if(count == 1)
 							{	
								$("#mobilestatus").show();								
								$("#edit").addClass("disabled");
							}  
							else {
								$("#mobilestatus").hide();								
								$("#edit").removeClass("disabled");
							}								
						} 
					}); 
				}				
			return false;
			});			
		});
</script>			

<?php 
	include("footer.php");    
?>			