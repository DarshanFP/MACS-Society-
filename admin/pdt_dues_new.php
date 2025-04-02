<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_dues";
	include("pdtsidepan.php");
	$id = $_GET['id'];
  if($id == 1){
    $subheadid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule = 5");
    
  }
  else{
    $subheadid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule = 6");
  }
  $subheadidcount = mysqli_num_rows($subheadid);
  $subheadid = mysqli_fetch_assoc($subheadid);
  $subheadid = $subheadid['SubID'];
  
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
                <?php 
                  if($id == 1)
                    echo "Add Due to Head: ";
                  else
                    echo "Add Due by Head: ";
                
                  if($subheadidcount == 0)
                    echo "First Add Due to / Due by Sub Heads in Head of Accounts";
                ?>								
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_dues_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Head Name</label>
										
										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="head" placeholder="Enter Head Name" class="col-xs-10 col-sm-5" autocomplete="off" required />
                      <input type="hidden" name="id" value="<?php echo $id; ?>" />
										</div>
										
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Details </label>

										<div class="col-sm-7">
											<textarea type="text" id="form-field-3" name="details" placeholder="Enter details"  class="col-xs-7 col-sm-5" autocomplete="off" ></textarea>
										</div>
									</div>
                  
                  <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Remarks </label>

										<div class="col-sm-7">
											<textarea type="text" id="form-field-3" name="remarks" placeholder="Enter Remarks"  class="col-xs-7 col-sm-5" autocomplete="off" ></textarea>
										</div>
									</div>
                  
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
                      <?php 
                        if($subheadidcount > 0){
                          echo "<button id='submit' class='btn btn-success' type='submit'>
                                  <i class='ace-icon fa fa-check bigger-110'></i>
                                  Submit
                                </button>";    
                        }
                        else{
                          echo "<button id='submit' class='btn btn-success' type='submit' disabled>
                                  <i class='ace-icon fa fa-check bigger-110'></i>
                                  Submit
                                </button>";    
                        }
                      
                      ?>
											
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
<script>
	$(document).ready(function(){		
		var major = $('#major').val();
		var ledger = $('#ledger').val();
		if(major == '' || ledger == ''){
			$('#submit').prop("disabled", true);
		}
		$('#major').change(function(){
			major = $('#major').val();
			ledger = $('#ledger').val();
			if(major == '' || ledger == ''){
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