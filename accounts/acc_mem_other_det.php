<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
  if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
        $subid = $_GET['subid'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);

        $subhead  = mysqli_query($connection,"SELECT SubHead FROM acc_subhead WHERE SubID = '$subid'");
        $subname = mysqli_fetch_assoc($subhead);
        
  
    $finalquery = "SELECT date, acc_cashbook.TransID, receiptcash + receiptadj as receipt, paymentcash+paymentadj as payment 
                        FROM acc_cashbook, acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = '$subid' and memid = '$memid'";    
    $finalq = mysqli_query($connection,$finalquery);
    $count = mysqli_num_rows($finalq);
    
   }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 <?php echo $shead; ?> Statement 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
                                <label>From Date</label>
                                <input type="date" name="fromdate" id="fromdate" style="font-size:11pt;height:30px;width:160px;">	
                                <label>To Date</label>
                                <input type="date" name="todate" id="todate" style="font-size:11pt;height:30px;width:160px;">	
                                <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                                    Retrieve
                                </button>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-10">											

                                            <div class="space-12"></div>
                                            

											<div class="profile-user-info profile-user-info-striped">
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member ID </div>

													<div class="profile-info-value">
														<span class="editable" id="memid"> <?php echo $memid;?> </span>
                                                    </div>
                                                    <div class="profile-info-name"> Bank Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankname'];?> </span>
                                                    </div>
                          
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Member Name </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank IFSC</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankifsc'];?> </span>
													</div>
                          
											    </div>
												
												<div class="profile-info-row">
                                                    <div class="profile-info-name"> Member Group </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Account No.</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaccountno'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Mobile No </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Address</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaddress'];?> </span>
													</div>
												</div>                        
    

											</div>

											<div class="space-20"></div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														<?php echo $subname['SubHead']; ?> 
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
																				<th class="center">Trans ID</th>
																				<th class="center">Date</th>
                                                                                <th class="center">Receipt</th>
                                                                                <th class="center">Payment</th>                                                                                                                                                                
                                        
																			</tr>
																		</thead>

																		<tbody id="trows1">
																		<?php 
                                                                            if($count>0){
                                                                                $slno = 1;                  
                                                                                $i = 0;
                                                                                while($row4 = mysqli_fetch_assoc($finalq)){                                                                                   
                                                                                    echo "<tr><td class='center'>".$slno."</td>";
                                                                                    echo "<td class='center'>".$row4['TransID']."</td>";
                                                                                    echo "<td class='center'>".$row4['date']."</td>";                                                                                    
                                                                                    echo "<td align = 'right'>".number_format($row4['receipt'],2,'.','')."</td>";
                                                                                    echo "<td align = 'right'>".number_format($row4['payment'],2,'.','')."</td>";
                                                                                    
                                                                                    $slno = $slno + 1;
                                                                                    $ob = $cb;
                                                                                    
                                                                                }
                                                                            }                                        
                                                                            
																				
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
                          <a href="acc_mem_det.php?memid=<?php echo $memid; ?>"><button class="btn btn-primary" style="float:right;">
                            <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                          </button></a>
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
        var memid = $("#memid").text();
        var loanno = $("#loanno").text();
        var today ='<?php echo date("Y-m-d");?>';
        var fromdate = $("#fromdate").val();
        var todate = $("#todate").val();				
        if(fromdate <= today && todate <= today && fromdate <= todate)
        {
            
                $.ajax({  
                type: "POST",  
                url: "acc_mem_loan_view.php",  
                data: {memid : memid, loanno : loanno, fromdate : fromdate, todate : todate}, 
                datatype: "html",
                success: function(data){
                    //alert(data);
                    $('#trows1').html(data);							
                    $('#trows1').show();
                    }	 
            });
        }
        else{
            $('#trows1').hide();
            alert("Please select correct dates");
        }
    });
    return false;
});
</script>