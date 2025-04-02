<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_trans";
    include("pdtsidepan.php");
    if(isset($_GET['transid'])){
        $transid = $_GET['transid'];
        //echo $transid;       
        $sql5 = mysqli_query($connection,"SELECT acc_cashbook.*,TransStatus,CancelStatus FROM acc_cashbook,acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND TransStatus = 1 AND CancelStatus = 1 AND acc_transactions.TransID = '$transid'");
    }    
    
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Transaction View
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
													<div class="profile-info-name"><b>TransactionID</b> </div>

													<div class="profile-info-value">
														<span class="editable" id="username">
                              <!-- <form class = "form-group" action="acc_mem_receipt.php" method ="post">-->
                                <input type="text" name="transid" id="transid" >
                                <button type = "button" id = "transview" class = "btn btn-primary">
                                  Submit
                                </button>
                              <!-- </form> -->                            
                                
                            </span>                          
                                
                            
													</div>
                          
												</div>
												

											</div>

											<div class="space-20"></div>

                                            <div class="content table-responsive table-full-width">
                                                <table class="table" border=1>	
                                                <thead>
                                                    <th>Sl.No</th>
                                                    <th>TransID</th>
                                                    <th>Date</th>
                                                    <th>Member Name</th>
                                                    <th>Head of Account</th>										
                                                    <th>Account No</th>	
                                                    <th>Details</th>
                                                    <th>OB</th>
                                                    <th>Receipt Cash</th>
                                                    <th>Receipt Adj</th>
                                                    <th>Payment Cash</th>
                                                    <th>Payment Adj</th>
                                                    <th>Cluster</th>
                                                    <th>Status</th>
                                                </thead>	
                                                <tbody id=transrows>
                                               
                                                </tbody>
                                                </table>
                                            </div>

                                            <!-- <a id="link" href="acc_trans_cancel.php">
                                            <button id="cancel" class="btn btn-primary" style="float:right;">
                                                <i class="fa fa-close" style="margin-right:10px;"></i>Cancel
                                            </button>
                                            </a> -->
                                            <a id="link" href="" onclick="this.href='pdt_trans_accept_reject.php?status=accept&transid='+document.getElementById('transid').value"><button id="cancel" class="btn btn-primary" style="float:right;margin:20px;">
                                                <i class="fa fa-check-square" style="margin-right:10px;"></i>Cancel
                                            </button></a>
                                            
                                            <a href="pdt_trans_view.php"><button class="btn btn-primary" style="float:left;">
                                                <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                            </button></a> 
                                             
                                            <div class="profile-info-value" style="float:right;">
                                                <label class="reasons" style="color:red;"><b>Cancellation Reasons</b></label>
												<span class="editable" id="username">
                                                <input type="text" class="reasons" name="cancelreasons" id="cancelreasons" required />
                                                </span>                          
                                            <div> 
                                            
                                                             
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->


<?php 
	include("footer.php");    
?>
<script>
$(document).ready(function()
{   
    $("#transview").click(function() 
	{   
        var transid = $('#transid').val();
        //alert(transid);
        $.ajax({  
            type: "POST",
            data:"transid="+ transid,
            url: "pdt_trans_view_suc.php",  
			success: function(response){
                //alert(response);
                if(!$.trim(response)){
                   $('#transrows').html('');
                   alert('TransID not exists.');
                }else{ 																				
                    $('#transrows').html(response);
                }
            } 
		}); 
		return false;
    });
});    
</script>    
