<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_cluster";
	include("pdtsidepan.php");

//  $sql = mysqli_query($connection,"SELECT * FROM MACS");
//  $count = mysqli_num_rows($sql);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Add New Cluster
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_cluster_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Cluster Name</label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="name" placeholder="Enter Cluster Name" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Address</label> 

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="address" placeholder="Enter Address" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3">Phone No</label>

										<div class="col-sm-4">
											<input type="text" id="form-field-3" name="mobile" placeholder="Mobile"  class="col-xs-7 col-sm-5" autocomplete="off" required />
										</div>
									</div>

<!--                   <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3">Under MACS</label>

										<div class="col-sm-7">
											<select name="macs" class="col-xs-10 col-sm-5" autocomplete="off" required>
                      <?php
                     //     while($row = mysqli_fetch_assoc($sql)){
                     //       echo "<option value = ".$row['MACSID'].">".$row['Name']."</option>";
                     //     }
                        ?>
                      </select> 
										</div>
									</div>
 -->
                  
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button id="submit" class="btn btn-success" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
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
<script>		
		$(document).ready(function()
		{ 	$("#phonestatus").hide();
			$('#phone').keyup(function() 
			{   
				var phone = $("#phone").val();					
				if(phone.length <= 10 )
				{
					$.ajax({  
						type: "POST",  
						url: "phonechecknew.php",  
						data: "phone="+ phone, 
						success: function(count){ 																				
							if(count == 1)
 							{	
								$("#phonestatus").show();								
								$("#submit").addClass("disabled");
							}  
							else {
								$("#phonestatus").hide();								
								$("#submit").removeClass("disabled");
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
        $('#phone').keypress(function(e) {
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