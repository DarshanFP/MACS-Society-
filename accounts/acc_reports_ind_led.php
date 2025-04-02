<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
    include("accountssidepan.php"); 
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Individual Ledger View
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>                                
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-12">											

											<div class="space-12"></div>

											<div class="profile-user-info profile-user-info-striped">
												
												<div class="profile-info-row">
													<div class="profile-info-name"><b>Member ID</b> </div>

													<div class="profile-info-value">
														<span class="editable" id="username">
                              <form class = "form-group" action="acc_rep_mem_ledger.php" method ="post" id="submit-form">
                                <input type="text" name="memid" id="memid">
                                
                                <button type = "submit" id = "transview" class = "btn btn-primary">
                                  Submit
                                </button>
                                
                              </form>                            
                                
                            </span>                          
                                
                            
													</div>
                          
												</div>
												

											</div>

											<div class="space-20"></div>

                                            
                                           
										</div>
									</div>
								</div>
							</div>
						</div>
                        <img src="../image/loader.gif" id="gif" style="display:block; margin-left:400px; width: 300px; visibility:hidden;">
                        
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->


<?php 
	include("footer.php");    
?>
<script>
    $("#submit-form").submit(function()
    {
        $('#gif').css('visibility','visible');
    });
</script>
<!-- <script>
$(document).ready(function()
{   
    $("#cancel").prop('disabled', true);
    $(".reasons").prop('disabled', true);
    $('#cancel0').hide();
    $('#cancel1').hide();
    $("#transview").prop('disabled', false); 
    $("#transview").click(function() 
	{   
        var transid = $('#transid').val();
        //$("#link").attr('href','acc_trans_cancel.php?transid='+transid);
        //alert(transid);
        $.ajax({  
            type: "POST",
            data:"transid="+ transid,
            url: "acc_trans_view_suc.php",  
			success: function(response){
                //alert(response);
                if(!$.trim(response)){
                   $('#transrows').html('');
                   alert('TransID not exists or belongs to other cluster.');
                   $("#cancel").prop('disabled', true);
                }else{ 																				
                    $('#transrows').html(response);
                    
                    $("#cancel").prop('disabled', false);
                    $(".reasons").prop('disabled', false); 
                }
            } 
		}); 
		return false;
    });

    $("#cancel").click(function() 
	{   
        var transid = $('#transid').val();
        var reasons = $('#cancelreasons').val();
        //alert(reasons); 
        $.ajax({  
            type: "POST",
            //data:"transid="+ transid,
            data:{transid : transid, reasons: reasons},
            url: "acc_trans_view_cancel.php",  
			success: function(response){
                //alert(response);
                if(response == 0 || response == 3){
                   $('#cancel0').show();
                   $("#transview").prop('disabled', true);
                   $("#cancel").prop('disabled', true);
                   $(".reasons").prop('disabled', true);
                }else if(response == 1){ 																				
                    $('#cancel1').show();
                    $("#transview").prop('disabled', true);
                    $("#cancel").prop('disabled', true);
                    $(".reasons").prop('disabled', true);
                }
            } 
		}); 
		return false;
    });
});    
</script>     -->