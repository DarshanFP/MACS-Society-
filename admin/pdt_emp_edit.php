<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_employee";
	include("pdtsidepan.php");
	if(isset($_GET['empid'])){
		$_SESSION['temp'] = $_GET['empid'];
	}
	if(isset($_SESSION['temp'])){		
		$empid = $_SESSION['temp'];					
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

		mysqli_query($connection, "start transaction");			
		
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		
		$sql1 = "SELECT * FROM empstatus";
		$result1 = mysqli_query($connection, $sql1);
		$count = mysqli_num_rows($result1);
		
		mysqli_query($connection, "commit");
		
		unset($_SESSION['temp']);		
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
								Edit Employee
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_emp_edit_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="empid"> Employee ID </label>

										<div class="col-sm-7">
											<input type="text" id="empid" name="empid" value="<?php echo $row['empid']; ?>" class="col-xs-10 col-sm-5" readonly />											
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" value="<?php echo $row['empname']; ?>" class="col-xs-10 col-sm-5" readonly />
										</div>
									</div>								
									
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Designation </label>
											
										<div class="col-sm-7">
											<select name="desg" id="form-field-4">
												
												<option value="<?php echo $row['designation']; ?>"><?php echo $row['designation'];?></option>												
												<?php 
													if($row['designation'] != "Head Clerk"){
														echo "<option value='Head Clerk'>Head Clerk</option>";
													}
													if($row['designation'] != "Cashier"){
														echo "<option value='Cashier'>Cashier</option>";
													}
													if($row['designation'] != "Clerk"){
														echo "<option value='Clerk'>Clerk</option>";
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Status </label>

										<div class="col-sm-7">											
											
											<select name="empstatus" id="form-field-5">
												<option value="<?php echo $row['statusid']; ?>"><?php echo $row['statusdesc'];?></option>
												<?php 
													if($count >0){
														while ($row1 = mysqli_fetch_assoc($result1)){
															if($row['statusid'] != $row1['statusid'] && $row1['statusid'] != 0){
																echo " <option value=".$row1['statusid'].">".$row1['statusdesc']."</option>";	
															}															
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Address </label>

										<div class="col-sm-9">
											<textarea cols="35" rows="3" name="address" id="form-field-6" ><?php echo $row['empaddress']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="mobile"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" id="mobile" minlength="10" maxlength="10" name = "cell" value="<?php echo $row['empmobile']; ?>" class="col-xs-10 col-sm-5" minlength="10" maxlength="10" autocomplete="off"/>
											<div id = "mobilestatus">Mobile number already exits</div>
										</div>
									</div>
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" type="submit" id="edit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
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
				if(mobile.length <= 10 )
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

<script>
    $(document).ready(function(){
        $('#mobile').keypress(function(e) {
	      if(isNaN(this.value+""+String.fromCharCode(e.charCode))) return false;
        })
        .on("cut copy paste",function(e){
	    e.preventDefault();
        });
    });
</script>			

<?php 
	include("footer.php");    
?>			