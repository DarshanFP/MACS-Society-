<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
	include("accountssidepan.php");
    if(isset($_GET['group'])){	
        $group = $_GET['group'];
        $month = $_GET['month'];
        $year = $_GET['year'];
        $clusterquery = mysqli_query($connection,"SELECT groups.ClusterID, cluster.ClusterName FROM groups, cluster WHERE groups.GroupID = '$group' AND groups.ClusterID = cluster.ClusterID ");
        $clusterfetch = mysqli_fetch_assoc($clusterquery);
        $cluster = $clusterfetch['ClusterID'];
        $clustername = $clusterfetch['ClusterName'];
        $month1 = $year.'-'.$month;       
        $obdate = $year.'-'.$month.'-01';        

               
        $sql = "SELECT A.*, AOB.*, B.*, BOB.*, C.*, COB.*, D.*, DOB.*, F.* FROM 
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as GSReceipt, cashbook.payment as GSPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 2 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as A,                     
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as GSOBReceipt, cashbook.payment as GSOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 2 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as AOB,                     
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as SSReceipt, cashbook.payment as SSPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 3 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as B,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as SSOBReceipt, cashbook.payment as SSOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 3 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as BOB,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as MSReceipt, cashbook.payment as MSPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 4 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as C,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as MSOBReceipt, cashbook.payment as MSOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 4 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as COB,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as GLReceipt, cashbook.payment as GLPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 5 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as D,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as GLOBReceipt, cashbook.payment as GLOBPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 5 and date < '$obdate' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as DOB,                    
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as GLInterest from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 6 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as F
                    

                    WHERE B.GroupID = A.GroupID AND A.GroupID = AOB.GroupID AND B.GroupID = C.GroupID AND B.GroupID = BOB.GroupID AND C.GroupID = COB.GroupID AND C.GroupID = D.GroupID AND D.GroupID = DOB.GroupID AND D.GroupID = F.GroupID";
        $result = mysqli_query($connection, $sql);
        $count = mysqli_num_rows($result);

        $sql1 = "SELECT G.*, H.*, I.*, J.*, K.* FROM
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as AppReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 11 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as G,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as DocReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 12 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as H,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as MFeReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 15 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as I,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as StaReceipt from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 16 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as J,
                    (SELECT groups.GroupID, groups.GroupName, cashbook.receipt as MutReceipt, cashbook.payment as MutPayment from groups LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, GroupID FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.clusterid = '$cluster' and acc_cashbook.subheadid = 10 and LEFT(acc_cashbook.date,7) ='$month1' GROUP BY acc_cashbook.GroupID) as cashbook ON cashbook.GroupID= groups.GroupID WHERE groups.ClusterID = '$cluster' GROUP BY GroupID) as K

                    WHERE G.GroupID = H.GroupID AND H.GroupID = I.GroupID AND I.GroupID = J.GroupID AND J.GroupID = K.GroupID";
        $result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
    }
    
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
                        <form method="post" target="_blank">
						<div class="page-header" >              
                            <h1>
								Cluster Ledger : <?php echo $clustername; ?>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                <small>          
                                    Month : <?php echo $month;?> -  Year <?php echo $year;?>    
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
														<i class="ace-icon fa fa-rss orange"></i>
														Cluster Ledger
                                                    </h4>                                                    
                                                    
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
                                                                <table style="font-size:x-small" id="simple-table" class="table  table-bordered table-hover">
																		<thead id='theads1'>
																			<tr>														
                                                                                <th style="text-align: center;" rowspan="2">GroupID</th>														
                                                                                <th style="text-align: center;" rowspan="2">GroupName</th>														                            
                                                                                <th style="text-align: center;" colspan="3">General Savings</th>																				                                    	
                                                                                <th style="text-align: center;" colspan="3">Special Savings</th>																				                                    	
                                                                                <th style="text-align: center;" colspan="3">Marraige Savings</th>																				                                    	
                                                                                <th style="text-align: center;" colspan="4">General Loan</th>                                                                                
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="text-align: center;">Received</th>																				                                    	
                                                                                <th style="text-align: center;">Payment</th>																				                                    	
                                                                                <th style="text-align: center;">Balance</th>																				                                    	
                                                                                <th style="text-align: center;">Received</th>																				                                    	
                                                                                <th style="text-align: center;">Payment</th>																				                                    	
                                                                                <th style="text-align: center;">Balance</th>																				                                    	
                                                                                <th style="text-align: center;">Received</th>																				                                    	
                                                                                <th style="text-align: center;">Payment</th>																				                                    	
                                                                                <th style="text-align: center;">Balance</th>																				                                    	
                                                                                <th style="text-align: center;">Received</th>																				                                    	                                                                                																				                                    	
                                                                                <th style="text-align: center;">Payment</th>																				                                    	
                                                                                <th style="text-align: center;">Balance</th>																				                                    	
                                                                                <th style="text-align: center;">Interest</th>                                                                                
                                                                            </tr>
																		</thead>

																		<tbody id="trows1">
																		<?php 
                                                                          $totgsreceipt = 0;
                                                                          $totgspayment = 0;
                                                                          $totgsclosing = 0;
                                                                          $totssreceipt = 0;
                                                                          $totsspayment = 0;
                                                                          $totssclosing = 0;
                                                                          $totmsreceipt = 0;
                                                                          $totmspayment = 0;
                                                                          $totmsclosing = 0;
                                                                          $totglreceipt = 0;
                                                                          $totglpayment = 0;
                                                                          $totglclosing = 0;
                                                                          $totglinterest = 0;
                                                                          while ($row = mysqli_fetch_assoc($result)){
                                                                              echo "<tr>";
                                                                              echo "<td align='center'><a href='acc_rep_group_ledger_view_dup.php?group=".$row['GroupID']."&&month=".$month."&&year=".$year."'>".$row['GroupID']."</a></td>";   
                                                                              echo "<td>".$row['GroupName']."</td>";
                                                                              $gsclosing = $row['GSOBReceipt'] - $row['GSOBPayment'] + $row['GSReceipt'] - $row['GSPayment'];
                                                                              echo "<td align='right'>".$row['GSReceipt']."</td>";
                                                                              echo "<td align='right'>".$row['GSPayment']."</td>";
                                                                              echo "<td align='right'>".number_format($gsclosing,2)."</td>";
                                                                              $ssclosing = $row['SSOBReceipt'] - $row['SSOBPayment'] + $row['SSReceipt'] - $row['SSPayment'];
                                                                              echo "<td align='right'>".$row['SSReceipt']."</td>";
                                                                              echo "<td align='right'>".$row['SSPayment']."</td>";
                                                                              echo "<td align='right'>".number_format($ssclosing,2)."</td>";
                                                                              $msclosing = $row['MSOBReceipt'] - $row['MSOBPayment'] + $row['MSReceipt'] - $row['MSPayment'];
                                                                              echo "<td align='right'>".$row['MSReceipt']."</td>";
                                                                              echo "<td align='right'>".$row['MSPayment']."</td>";
                                                                              echo "<td align='right'>".number_format($msclosing,2)."</td>";
                                                                              $glclosing = $row['GLOBPayment'] - $row['GLOBReceipt'] + $row['GLPayment'] - $row['GLReceipt'];
                                                                              echo "<td align='right'>".$row['GLReceipt']."</td>";
                                                                              echo "<td align='right'>".$row['GLPayment']."</td>";
                                                                              echo "<td align='right'>".number_format($glclosing,2)."</td>";
                                                                              echo "<td align='right'>".$row['GLInterest']."</td>";                                    
                                                                              echo "</tr>";
                                                                              $totgsreceipt = $totgsreceipt + $row['GSReceipt'];
                                                                              $totgspayment = $totgspayment + $row['GSPayment'];
                                                                              $totgsclosing = $totgsclosing + $gsclosing;
                                                                              $totssreceipt = $totssreceipt + $row['SSReceipt'];
                                                                              $totsspayment = $totsspayment + $row['SSPayment'];
                                                                              $totssclosing = $totssclosing + $ssclosing;
                                                                              $totmsreceipt = $totmsreceipt + $row['MSReceipt'];
                                                                              $totmspayment = $totmspayment + $row['MSPayment'];
                                                                              $totmsclosing = $totmsclosing + $msclosing;
                                                                              $totglreceipt = $totglreceipt + $row['GLReceipt'];
                                                                              $totglpayment = $totglpayment + $row['GLPayment'];
                                                                              $totglclosing = $totglclosing + $glclosing;
                                                                              $totglinterest = $totglinterest + $row['GLInterest'];
                                                                          }     
                                                                          
                                                                          echo "<tr>";
                                                                          echo "<td align='center'></td>";   
                                                                          echo "<td><b>Total</td>";        
                                                                          echo "<td align='right'><b>".number_format($totgsreceipt,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totgspayment,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totgsclosing,2)."</td>";        
                                                                          echo "<td align='right'><b>".number_format($totssreceipt,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totsspayment,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totssclosing,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totmsreceipt,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totmspayment,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totmsclosing,2)."</td>";        
                                                                          echo "<td align='right'><b>".number_format($totglreceipt,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totglpayment,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totglclosing,2)."</td>";
                                                                          echo "<td align='right'><b>".number_format($totglinterest,2)."</td>";
                                                                          echo "</b></tr>";
                                                                  
                                                                        ?>																			
																		</tbody>
                                                                    </table>
                                                                    <table style="font-size:x-small" id="simple-table" class="table  table-bordered table-hover">
																		<thead id='theads1'>
																			<tr>														
                                                                            <th style="text-align: center;" rowspan="2">GroupID</th>														
                                                                                <th style="text-align: center;" rowspan="2">GroupName</th>														
                                                                                <th style="text-align: center;" colspan="2">Mutual Aid</th>
                                                                                <th style="text-align: center;" rowspan="2">Application Charges</th>
                                                                                <th style="text-align: center;" rowspan="2">Documentation Charges</th>
                                                                                <th style="text-align: center;" rowspan="2">Membership Fee</th>
                                                                                <th style="text-align: center;" rowspan="2">Stationary Charges</th>
                                                                            </tr>                                                                  
                                                                            <tr>
                                                                                <th style="text-align: center;">Receipt</th>
                                                                                <th style="text-align: center;">Payment</th>
                                                                            </tr>          
																		</thead>

																		<tbody id="trows2">
																		<?php 
                                                                         $totmutreceipt = 0;
                                                                         $totmutpayment = 0;
                                                                         $totappreceipt = 0;
                                                                         $totdocreceipt = 0;
                                                                         $totmfereceipt = 0;
                                                                         $totstareceipt = 0;
                                                                         while
                                                                         ($row = mysqli_fetch_assoc($result1)){
                                                                             echo "<tr>";
                                                                             echo "<td align='center'><a href='acc_rep_group_ledger_view_dup.php?group=".$row['GroupID']."&&month=".$month."&&year=".$year."'>".$row['GroupID']."</a></td>";   
                                                                             echo "<td>".$row['GroupName']."</td>";            
                                                                             echo "<td align='right'>".$row['MutReceipt']."</td>";
                                                                             echo "<td align='right'>".$row['MutPayment']."</td>";
                                                                             echo "<td align='right'>".$row['AppReceipt']."</td>";
                                                                             echo "<td align='right'>".$row['DocReceipt']."</td>";
                                                                             echo "<td align='right'>".$row['MFeReceipt']."</td>";
                                                                             echo "<td align='right'>".$row['StaReceipt']."</td>";
                                                                             
                                                                             echo "</tr>";
                                                                             $totmutreceipt = $totmutreceipt + $row['MutReceipt'];
                                                                             $totmutpayment = $totmutpayment + $row['MutPayment'];
                                                                             $totappreceipt = $totappreceipt + $row['AppReceipt'];
                                                                             $totdocreceipt = $totdocreceipt + $row['DocReceipt'];
                                                                             $totmfereceipt = $totmfereceipt + $row['MFeReceipt'];
                                                                             $totstareceipt = $totstareceipt + $row['StaReceipt'];
                                                                         }        
                                                                         echo "<tr>";
                                                                             echo "<td align='center'></td>";   
                                                                             echo "<td><b>Total</td>";            
                                                                             echo "<td align='right'><b>".number_format($totmutreceipt,2)."</td>";
                                                                             echo "<td align='right'><b>".number_format($totmutpayment,2)."</td>";
                                                                             echo "<td align='right'><b>".number_format($totappreceipt,2)."</td>";
                                                                             echo "<td align='right'><b>".number_format($totdocreceipt,2)."</td>";
                                                                             echo "<td align='right'><b>".number_format($totmfereceipt,2)."</td>";
                                                                             echo "<td align='right'><b>".number_format($totstareceipt,2)."</td>";            
                                                                             echo "</tr>";

                                                                        ?>																			
																		</tbody>
                                                                    </table>
                                                                    <button type="submit" formaction="acc_rep_cluster_ledger_excel.php" class="btn btn-success">Export to Excel</button>
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
            $('#trows1').hide();
            $('#trows2').hide();
            $("#retrieve").prop('disabled', true);	       
			//alert('hello!');	       
            var today ='<?php echo date("Y-m-d");?>';
            var cluster = $("#cluster").val();
            var month = $("#month").val();
            var year = $("#year").val();
            var tdate = year+'-'+month+'-01';        
            alert("Long Query Please wait some time");
            if(tdate < today )
            {
                $.ajax({  
                    type: "POST",  
                    url: "acc_rep_cluster_ledger_view.php",  
                    data: {month: month, year: year, cluster: cluster}, 
                    datatype: "html",
                    success: function(response){
                        //alert(response);
                        var data = response.split("|");                        
                        $("#trows1").html(data[1]);							
                        $("#trows1").show();
                        $("#trows2").html(data[2]);							
                        $("#trows2").show();
                        $("#retrieve").prop('disabled', false);
                    }	 
                });
            }
            else{
                $('#trows1').hide();
                $('#trows2').hide();
                alert("Please select previous months");
            }
		});
	    return false;
	});
</script>
