 <?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
	include("accountssidepan.php");
    $transid = mysqli_query($connection,"SELECT * FROM acc_transactions WHERE TransStatus = 1");    
    $recid = mysqli_query($connection,"SELECT * FROM acc_receipts WHERE RecStatus = 1");    
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
                    <form method="post" target="_blank">
                        <div class="page-header" >      
                            <div class="row">
                                <div class="col-md-3">       
                                    <h1>
                                    Receipt Print 
                                    <i class="ace-icon fa fa-angle-double-right"></i>
                                    <small>
                                </div>
                                <div class="col-md-3">       
                                    <div class="form-group">
                                        <label style="margin-left:10px">Receipt No.</label>
                                        <select name="receiptid" id="receiptid" class="js-example-basic-single form-control"  style="font-size:11pt;height:30px;width:160px;" required >	
                                        <?php 
                                            echo "<option></option>";                                    
                                            while ($row2 = mysqli_fetch_assoc($recid)) 												
                                            echo "<option value ='".$row2['ReceiptID']."'>".$row2['ReceiptID']."</option>";								
                                        ?>
                                        </select> 
                                    </div>
                                </div>                                
<!--                                 <div class="col-md-3">       
                                    <div class="form-group">
                                        <label style="margin-left:10px">Trans No.</label>
                                        <select name="transid" id="transid" class="js-example-basic-single form-control"  style="font-size:11pt;height:30px;width:160px;" required >	
                                        <?php 
                                            echo "<option></option>";                                    
                                            while ($row2 = mysqli_fetch_assoc($transid)) 												
                                            echo "<option value ='".$row2['TransID']."'>".$row2['TransID']."</option>";								
                                        ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-3">       
                                    <div class="form-group">
                                    <label style="margin-left:10px">Member ID.</label>
                                    <select name="memid" id="memid"   class="js-example-basic-single form-control" style="font-size:11pt;height:30px;width:160px;" required >	
                                    
                                    </select>               
                                    </div>
                                </div> -->
                                <div class="col-md-3">       
                                    <div class="form-group">
                                    <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                                        Retrieve
                                    </button>
                                    </small>  
                                    </div>
                                </div>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-10">											
											<div class="space-20"></div>

                                            <div class="profile-user-info profile-user-info-striped" id="ledview">
                                            
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Receipt No </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="recid"></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Trans ID </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="transid"></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Date </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="memid"></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Society Name </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="memid"></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Member ID </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="memname"></span>
                                                    </div>
                                                </div>                        

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Member Name </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="memname"></span>
                                                    </div>
                                                </div>                                                    
                                            </div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														<?php echo $row2['SubHead']; ?> 
													</h4>						                          
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
																			<tr>													
																				<th class="detail-col">S.No.</th>
																				<th class="center">Head of Account</th>
																				<th class="center">Amount</th>                                                                                                                                                                
																			</tr>
																		</thead>

																		<tbody id="trows1">
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
												</div>
                                                <a href="accounts_reports.php"><button type = 'button' class="btn btn-primary" style="float:right;">
                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                </button></a>
                                            </div>
                                            <img src="../image/loader.gif" id="gif" style="display:block; margin-left:400px; width: 300px; visibility:hidden;">

										</div>
									</div>
								</div>
							</div>
						</div>
                        <div>
					</div><!-- /.page-content -->
                    </form>
				</div>
			</div><!-- /.main-content -->
<?php 
	include("footer.php");    
?>

	<!-- select2 datalist with search -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.js-example-basic-single').select2();
    });	
</script>

<script type="text/javascript">
    $(document).ready(function(){
        /* $("#retrieve").prop("disabled",true); 	
			$("#transid").change(function(){
                var transid = $("#transid").val();
                $.ajax({
                    type: "POST",  
						url: "acc_rep_receipt_memid.php",  
						data: {transid: transid}, 
						datatype: "html",
						success: function(data){                            
                            data = data.split("*");
                            
                            $("#memid").html(data[0]);                            
                            if(data[1]>0){
                                $("#retrieve").prop("disabled",false); 	
                            }
                            else{
                                $("#retrieve").prop("disabled",true); 	
                            }
						  }	 
                });
            }); */
            
            $('#retrieve').click(function() 
			{   				       
			    $('#gif').css('visibility','visible');	       
                
                var recid = $("#receiptid").val();                                
                $.ajax({  
                    type: "POST",  
                    url: "acc_rep_receipt_view.php",  
                    data: {recid: recid}, 
                    datatype: "html",
                    success: function(data){
			            $('#gif').css('visibility','hidden');	       
                        data = data.split("*");                        
                        $("#ledview").html(data[0]);               
                        $('#trows1').html(data[1]);							
                        $('#trows1').show();
                        }	 
                });
			});            
	    return false;
	});
	</script>