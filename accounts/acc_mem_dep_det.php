<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
        $depid = $_GET['depid'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);

        $installment = mysqli_query($connection, "SELECT installment FROM acc_deposits WHERE depositno = '$depid'");
        $installment = mysqli_fetch_assoc($installment);
        $installment = $installment['installment'];

        $sql2 = "SELECT DISTINCT SubID,SubHead, SubHeadModule FROM acc_subhead,acc_cashbook WHERE SubID = subheadid AND accno = '$depid' GROUP BY SubID,SubHead, SubHeadModule ORDER BY SubHeadModule";
        $result2 = mysqli_query($connection,$sql2);
        $count2 = mysqli_num_rows($result2);
        //echo $count2;
        $i = 0;
        $cstring = '';
        $sstring = ''; 
        $casestring = '';
        
        
        $x = 'A';
        while($row2 = mysqli_fetch_assoc($result2)){
        
            $subid[$i] = $row2['SubID'];                
            $subhead[$i] = $row2['SubHead'];
            $subheadmodule[$i] = $row2['SubHeadModule'];
            
                        
            if($i < ($count2-1)){
                //$cstring .= "coalesce(".$subhead[$i].", 0) as ".$subhead[$i].",";
                //$sstring .= "sum(".$subhead[$i].") as ".$subhead[$i].",";
                //$casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$subhead[$i].",";
                
                if($subheadmodule[$i] != 4){
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x.",";
                }
                else{ 
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptadj end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x.",";
                }
                $x++;
            }
            else{                    
                if($subheadmodule[$i] != 4){
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x."";
                    $sstring .= "sum(".$x.") as ".$x."";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x."";
                }
                else{ 
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptadj end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x."";
                    $sstring .= "sum(".$x.") as ".$x."";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x."";
                }
                $x++;    
            }              
            $i++;            
        }
  
    $depquery = "SELECT * FROM (SELECT rowid,TransID,date1,".$cstring." 
                FROM (SELECT min(id) AS rowid,TransID,min(date) as date1,".$sstring."
                FROM (SELECT A.*,".$casestring."
                FROM (SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE accno = '$depid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1) AS A) AS B GROUP BY TransID) AS V ORDER BY date1 DESC,RIGHT(TransID,(CHAR_LENGTH(TransID)-3))) sub ORDER BY date1,RIGHT(TransID,(CHAR_LENGTH(TransID)-3)) ASC";
    $depfinal = mysqli_query($connection,$depquery);
    $count = mysqli_num_rows($depfinal);
     
    $sql2 = mysqli_query($connection,"SELECT SubHead FROM acc_deposits, acc_subhead WHERE depositno = '$depid' AND SubID = subheadid");
    $row2 = mysqli_fetch_assoc($sql2);
   }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 <?php echo $row2['SubHead']; ?> Statement 
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
													<div class="profile-info-name"> Deposit ID </div>

													<div class="profile-info-value">
														<span class="editable" id="depid"> <?php echo $depid;?> </span>
                            
                                                    </div>
                                                    <div class="profile-info-name">Bank IFSC</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankifsc'];?> </span>
													</div>
                          
											    </div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Account No.</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaccountno'];?> </span>
													</div>
                                                    
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Address</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaddress'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Monthly Amount</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo number_format($installment,2,'.',''); ?> </span>
													</div>
												</div>

											</div>

											<div class="space-20"></div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														<?php echo $row2['SubHead']; ?> 
													</h4>						
                          <span style="float:right"><a href="acc_mem_dep_payment.php?memid=<?php echo $memid; ?> & depid=<?php echo $depid;?>"><button class="btn btn-primary">
                              Payment
                              </button></a></span>
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
                                                                                <th class="center">Opening</th>
                                                                                <th class="center">Receipt</th>
                                                                                <th class="center">Payment</th>
                                                                                <th class="center">Closing</th>
                                                                                <th class="center">Interest Paid</th>                                        
																			</tr>
																		</thead>

																		<tbody id="trows1">
																		<?php
                                                                            if($count>0){
                                                                                //$obsql = mysqli_query($connection,"SELECT sum(`receiptcash`)+sum(`receiptadj`)-sum(`paymentcash`)-sum(`paymentadj`) AS ob FROM `acc_cashbook` WHERE date <= '$today' AND accno = '$depid' GROUP BY accno");
                                                                                //$obrow = mysqli_fetch_assoc($obsql);
                                                                                $slno = 1;                  
                                                                                //$ob = $obrow['ob'];
                                                                                //$ob = 0;
                                                                                $i = 0;
                                                                                while($row4 = mysqli_fetch_assoc($depfinal)){
                                                                                    if($slno == 1){
                                                                                       $rowid = $row4['rowid'];
                                                                                       $obsql = mysqli_query($connection,"SELECT (sum(`receiptcash`)+sum(`receiptadj`)-sum(`paymentcash`)-sum(`paymentadj`)) AS ob FROM `acc_cashbook` WHERE id < '$rowid' AND accno = '$depid' AND subheadid = 2 GROUP BY accno");
                                                                                       $obrow = mysqli_fetch_assoc($obsql);
                                                                                       $ob = $obrow['ob'];
                                                                                    }
                                                                                                                                                                      
                                                                                    $x = 'A';  
                                                                                    $receipt = $row4[$x];                                                                                                                                                                      
                                                                                    $x++;
                                                                                    $receipt = $receipt + $row4[$x];
                                                                                    $x++;
                                                                                    $payment = $row4[$x];
                                                                                    $x++;
                                                                                    $row4[$x];
                                                                                    $payment = $payment + $row4[$x];
                                                                                    $cb = $ob + $receipt - $payment;                                                                                      
                                                                                    $x++;
                                                                                    $interest = $row4[$x];
                                                                                    $x++;
                                                                                    $interest = $interest + $row4[$x];
                                                                                    
                                                                                    echo "<tr><td class='center'>".$slno."</td>";
                                                                                    echo "<td class='center'>".$row4['TransID']."</td>";
                                                                                    echo "<td class='center'>".$row4['date1']."</td>";
                                                                                    echo "<td align = 'right'>".number_format($ob,2,'.','')."</td>";
                                                                                    echo "<td align = 'right'>".number_format($receipt,2,'.','')."</td>";
                                                                                    echo "<td align = 'right'>".number_format($payment,2,'.','')."</td>";
                                                                                    echo "<td align = 'right'>".number_format($cb,2,'.','')."</td>";
                                                                                    echo "<td align = 'right'>".number_format($interest,2,'.','')."</td></tr>";
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
        var depid = $("#depid").text();
        var today ='<?php echo date("Y-m-d");?>';
        var fromdate = $("#fromdate").val();
        var todate = $("#todate").val();				
        if(fromdate <= today && todate <= today && fromdate <= todate)
        {
            
                $.ajax({  
                type: "POST",  
                url: "acc_mem_dep_view.php",  
                data: {memid : memid, depid : depid, fromdate : fromdate, todate : todate}, 
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