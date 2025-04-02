<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_hoa";
	include("pdtsidepan.php");
	$id = $_GET['id'];
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Add New Head
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_hoa_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">SubHead</label>
										
										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="subhead" placeholder="Enter SubHead" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
										
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Details </label>

										<div class="col-sm-7">
											<textarea type="text" id="form-field-3" name="details" placeholder="Enter details"  class="col-xs-7 col-sm-5" autocomplete="off" ></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4">Ledger Module </label>

										<div class="col-sm-7">
											<select name="ledgermodule" id="ledger" required >
												 <?php $sql3 = "select * from acc_subhead_module";
													
														$result3 = mysqli_query($connection,$sql3);
														echo	"<option></option>";
															while($row3 = mysqli_fetch_assoc($result3)){
														   echo	"<option value = ".$row3['ModuleID'].">".$row3['ModuleType']."</option>";
														  }
														
											?> 		
                    </select>
										</div>
									 </div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">MajorHead </label>

										<div class="col-sm-9">
											<select name="majorhead" id="major" required >
											<?php $sql1 = "select majorhead from acc_majorheads where mainid=1 or mainid=3";
														$sql2 = "select majorhead from acc_majorheads where mainid=2 or mainid=4";
														$result1 = mysqli_query($connection,$sql1);
														$result2 = mysqli_query($connection,$sql2);
																										 		
														echo	"<option></option>";
														if($id==1){
															while($row1 = mysqli_fetch_assoc($result1)){
														   echo	"<option>".$row1['majorhead']."</option>";
														  }
														}	
														if($id==2){
															while($row2 = mysqli_fetch_assoc($result2)){
														   echo	"<option>".$row2['majorhead']."</option>";
														  }
														}
											?> 
											</select>		
										</div>
									</div>
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button id="submit" class="btn btn-success" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
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
<script>
	$(document).ready(function(){		
		var major = $('#major').val();
		var ledger = $('#ledger').val();
		if(major == '' or ledger == ''){
			$('#submit').prop("disabled", true);
		}
		$('#major').change(function(){
			major = $('#major').val();
			ledger = $('#ledger').val();
			if(major == '' or ledger == ''){
				$('#submit').prop("disabled", true);
			}
			else{
				$('#submit').prop("disabled", false);	
			}
		});
	});
</script>

<script>		
		$(document).ready(function()
		{ 	
		 	$("#accountstring").hide();
			$('#ledger').change(function() 
			{   
				var ledger = $("#ledger").val();
				
				if(ledger==1){
					$("#accountstring").show();
					$('#sheadstring').prop("required", true);
				}
				if(ledger==0){
					$("#accountstring").hide();
					$('#sheadstring').prop("required", false);
				}
				return false;
			});			
		});
</script>