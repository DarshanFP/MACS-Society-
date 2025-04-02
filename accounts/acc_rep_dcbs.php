<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
	include("accountssidepan.php");
    $subid = mysqli_query($connection,"SELECT SubID, SubHead FROM acc_subhead, acc_subhead_module 
                            WHERE acc_subhead.SubHeadModule = acc_subhead_module.ModuleID AND ModuleID IN(2,3,4,5,6)");    
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
                    <form method="post" target="_blank">
                        <div class="page-header" >             
	    						<h1>
								DCB Statement 
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                <small>          
                                <label style="margin-left:10px">DCB Type</label>
                                <select name="depaccno" id="depaccno"   style="font-size:11pt;height:30px;width:160px;" required >	
                                <?php 
                                    echo "<option></option>";                                    
                                    while ($row2 = mysqli_fetch_assoc($subid)) 												
                                    echo "<option value ='".$row2['SubID']."'>".$row2['SubHead']."</option>";								
                                ?>
                                </select> 
                                    <label style="margin-left:10px">From Date</label>
                                    <input type="date" name="fdate" id="fdate" style="font-size:11pt;height:30px;width:160px;" >
                                    <label style="margin-left:10px">To Date</label>
                                    <input type="date" name="tdate" id="tdate" style="font-size:11pt;height:30px;width:160px;" >
                                    <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                                        Retrieve
                                    </button>
                                </small>  
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
                                                    <div class="profile-info-name"> Society Name </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="memid"></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Head Name </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="memname"></span>
                                                    </div>
                                                </div>                        

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Period </div>

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
																				<th class="center">Account No</th>
																				<th class="center">Name / Particulars</th>
                                                                                <th class="center">Opening</th>
                                                                                <th class="center">Receipt</th>
                                                                                <th class="center">Payment</th>
                                                                                <th class="center">Closing</th>                                                                                
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
					</div><!-- /.page-content -->
                    </form>
				</div>
			</div><!-- /.main-content -->
<?php 
	include("footer.php");    
?>

<script type="text/javascript">
    $(document).ready(function()
		{ 	
			$('#retrieve').click(function() 
			{   
			
                $('#gif').css('visibility','visible');
                var today ='<?php echo date("Y-m-d");?>';
                var headid = $("#depaccno").val();
                var fdate = $("#fdate").val();
                var tdate = $("#tdate").val();
               
                if(fdate <= tdate && tdate <= today )
				{
					$.ajax({  
						type: "POST",  
						url: "acc_rep_dcbs_view.php",  
						data: {fdate: fdate, tdate: tdate, headid: headid}, 
						datatype: "html",
						success: function(data){
                            $('#gif').css('visibility','hidden');
                            data = data.split("*");
                            $("#ledview").html(data[0]);               
							$('#trows1').html(data[1]);							
					 		$('#trows1').show();
						  }	 
				    });
				}
				else{
                    $('#gif').css('visibility','hidden');
					$('#trows1').hide();
					alert("Please select correct dates");
				}
			});            
	    return false;
	});
	</script>