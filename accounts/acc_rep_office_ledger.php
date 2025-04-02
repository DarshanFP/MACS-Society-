<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
	include("accountssidepan.php");

    //$sql2="SELECT SubID, SubHead FROM acc_subhead";
	//$result2=mysqli_query($connection,$sql2);

    $sql3="SELECT OfficeID,OfficeName FROM offices WHERE BranchID='$branchid'";
	$result3=mysqli_query($connection,$sql3);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
                        <form method="post" target="_blank">
						<div class="page-header" >              
							<h1>
								Office Ledger 
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                <small>          
                                    <label style="margin-left:10px">Office</label>
                                    <select name="office" id="office" style="font-size:11pt;height:30px;width:160px;" required >	
                                    <?php 
                                        echo "<option></option>";                        
                                        //echo "<option>All</option>";
                                        while ($row3 = mysqli_fetch_assoc($result3)) 												
                                        echo "<option value ='".$row3['OfficeID']."'>".$row3['OfficeName']."</option>";								
                                    ?>
                                    </select>                     
                                    <label style="margin-left:10px">Month</label>
                                    <select name="month" id="month"   style="font-size:11pt;height:30px;width:160px;" required >	
                                    <?php 
                                        echo "<option></option>";                        
                                        echo "<option value='01'>January</option>";
                                        echo "<option value='02'>February</option>";
                                        echo "<option value='03'>March</option>";
                                        echo "<option value='04' >April</option>";
                                        echo "<option value='05'>May</option>";
                                        echo "<option value='06'>June</option>";
                                        echo "<option value='07'>July</option>";
                                        echo "<option value='08'>August</option>";
                                        echo "<option value='09'>September</option>";
                                        echo "<option value='10'>October</option>";
                                        echo "<option value='11'>November</option>";
                                        echo "<option value='12'>December</option>";
                                    ?>
                                    </select>
                                    <!-- <input type="date" name="fdate" id="fdate" style="font-size:11pt;height:30px;width:160px;" >-->
                                    <label style="margin-left:10px">Year</label>
                                    <select name="year" id="year"   style="font-size:11pt;height:30px;width:160px;" required >	
                                        <?php
                                            $current = date("Y");   
                                            for($y=$current,$i=0 ; $i<6; $i++.$y--){
                                                echo "<option>".$y."</option>";
                                            }
                                        ?>
                                    </select>
                                    <!-- <input type="date" name="year" id="year" style="font-size:11pt;height:30px;width:160px;" > -->
                                    <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                                        Retrieve
                                    </button>
                                </small>  
							</h1>
						</div><!-- /.page-header -->
                        
						<div class="row">
							<div class="col-md-12"> <!-- PAGE CONTENT BEGINS -->
                                <div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-12">											

											<div class="space-12"></div>

											

                                            <div class="space-20"></div>
                                            
                                            <div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-bars orange"></i>
														Office Ledger
                                                    </h4>                                                    
                                                    
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead id='theads1'>
																			<tr>														
                                                                                <th style="text-align: center;">MemberID</th>														
                                                                                <th style="text-align: center;">MemberName</th>														                            
                                                                                <th style="text-align: center;">General Savings</th>																				                                    	
                                                                                <th style="text-align: center;">Marriage Savings</th>                            
                                                                                <th style="text-align: center;">General Loans</th>                            
                                                                            </tr>
																		</thead>

																		<tbody id="trows1">
																		
																			
																		</tbody>
                                                                    </table>
                                                                    <button type="submit" formaction="acc_rep_office_ledger_excel.php" class="btn btn-success">Export to Excel</button>
                                                                    <button type="button" class="btn btn-app btn-light btn-xs" onclick="window.print()"><i class="ace-icon fa fa-print bigger-160"></i>Print</button>

                                                                    <a href="accounts_reports.php"><button type="button" id="back" class="btn btn-primary" style="float:right;">
                                                                            <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                                    </button></a>       																				
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </div>
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

<script type="text/javascript">
    $(document).ready(function()
	{ 	
		$('#retrieve').click(function() 
		{   
				       
            var today ='<?php echo date("Y-m-d");?>';
            var office = $("#office").val();
            var month = $("#month").val();
            var year = $("#year").val();
            var tdate = year+'-'+month+'-01';        
            
            if(tdate < today )
            {
                $.ajax({  
                    type: "POST",  
                    url: "acc_rep_office_ledger_view.php",  
                    data: {month: month, year: year, office: office}, 
                    datatype: "html",
                    success: function(response){
                        //alert(response);
                        var data = response.split("|");
                        $("#theads1").html(data[0]);
                        $("#trows1").html(data[1]);							
                        $("#trows1").show();
                    }	 
                });
            }
            else{
                $('#trows1').hide();
                alert("Please select previous months");
            }
		});
	    return false;
	});
</script>
