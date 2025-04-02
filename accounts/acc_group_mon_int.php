<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_group";
	include("accountssidepan.php");
    $today = date("Y-m-d");
    if(isset($_GET['groupid'])){
		$groupid = $_GET['groupid'];
        $clustersql = mysqli_query($connection,"SELECT ClusterID FROM groups WHERE GroupID = '$groupid'");
        $cluster = mysqli_fetch_assoc($clustersql);
        $clusterid = $cluster['ClusterID'];

        $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
 	    $transcount = mysqli_num_rows($trans);
        $transcount = 1001 + $transcount;	
 	    $transid = "T".$macsshortname.$transcount;
 	    $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`,`timedate`) 
                              VALUES ('$transid',1,'$timedate')");

		$sql = "SELECT A.*, B.*, C.*, D.*, E.* FROM (SELECT members.*, sum(acc_loans.cb) as loans
                FROM members 
                    LEFT JOIN acc_loans 
                        ON members.memid = acc_loans.memid 
                        WHERE memgroupid = '$groupid' GROUP BY members.memid) AS A,
                (SELECT members.memid, sum(sd.cb) as sdeposits 
                FROM members 
                    LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 2) AS sd  
                        ON members.memid = sd.memid 
                        WHERE memgroupid = '$groupid' GROUP BY members.memid) AS B,
				(SELECT members.memid, sum(spd.cb) as spdeposits 
                FROM members 
                    LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 3) AS spd  
                        ON members.memid = spd.memid 
                        WHERE memgroupid = '$groupid' GROUP BY members.memid) AS C,
				(SELECT members.memid, sum(md.cb) as mdeposits 
                FROM members 
                    LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 4) AS md  
                        ON members.memid = md.memid 
                        WHERE memgroupid = '$groupid' GROUP BY members.memid) AS D,
				(SELECT members.memid, sum(mid.cb) as middeposits 
                FROM members 
                    LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 10) AS mid  
                        ON members.memid = mid.memid 
                        WHERE memgroupid = '$groupid' GROUP BY members.memid) AS E
                WHERE A.memid = B.memid AND A.memid = C.memid AND A.memid = D.memid AND A.memid = E.memid";
		$result = mysqli_query($connection, $sql) or die(mysqli_error($sql));
    $count = mysqli_num_rows($result);

    $sql1 = "SELECT * from groups where GroupID ='$groupid'";
		$result1 = 	mysqli_query($connection, $sql1) or die(mysqli_error($sql1));
    $row1 = mysqli_fetch_assoc($result1);

    $intquery = "SELECT `subheadid`,`details`,`roi`,`status` from acc_rateofinterest where `subheadid` IN (2,3,4) AND `status` = 1";
	$interest = mysqli_query($connection, $intquery) or die(mysqli_error($intquery));
        while($rowint = mysqli_fetch_assoc($interest)){
            if($rowint['subheadid'] == 2)
                $gsint = $rowint['roi'];
            if($rowint['subheadid'] == 3)
                $ssint = $rowint['roi'];
            if($rowint['subheadid'] == 4)
                $msint = $rowint['roi'];        
        }
	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Group Details
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
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
													<div class="profile-info-name"> Group ID </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $groupid;?> </span>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Group Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
													</div>
												</div>
											</div>

											<div class="space-20"></div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Monthly Interest for the Month of ____ Members under <?php echo $row1['GroupName']; ?> &nbsp;Group
													</h4>													
                                                </div>
                        
                        <div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							    <div class="row">
										<div class = "col-md-3">
									 		<p style="padding:12px">                        
                      </p>		
										</div>
										<div class = "col-md-9">
										 	<form  role="form" method="post" action="">
												<button class="btn btn-search" type="submit" style="float:right;height:42px;margin-right:2px; margin-top:15px;"><i class='ace-icon fa fa-search bigger-120'></i></button>
												<input type="text" id="memsearch" name="memsearch"  style="float:right;height:42px; margin-top:15px;" placeholder="Search" class="col-xs-4 col-sm-2" autocomplete="off" required  />
											</form>													
										</div>
								</div>								
								
								<div class="row">
									<div class="col-xs-12">
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col">Member ID</th>
													<th class="center">Member Name</th>													
                                                    <th class="center">General Savings</th>
                                                    <th class="center">Interest on General Savings</th>
                                                    <th class="center">Special Savings</th>
                                                    <th class="center">Interest on Special Savings</th>
                                                    <th class="center">Marraige Deposit</th>
                                                    <th class="center">Interest on Marraige Deposit</th>                                                    
												</tr>
											</thead>

											<tbody>
											<?php if($count>0){
												$slno=1;
                                                $totalgs = 0;
                                                $totalps = 0;
                                                $totalms = 0;
                                                $totalgsint = 0;
                                                $totalpsint = 0;
                                                $totalmsint = 0;
                                                $totalaid = 0;
                                                $totalloans = 0;
												while($row = mysqli_fetch_assoc($result))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";
													echo "<td class='center'><a href='acc_mem_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";
													$memid = $row['memid'];
                                                    echo "<td>".$row['memname']."</td>";													
                                                    echo "<td align='right'>".number_format($row['sdeposits'],2,'.','')."</td>";
                                                        $gsinterest = $row['sdeposits']*($gsint/1200);
                                                    echo "<td align='right'>".number_format($gsinterest,2,'.','')."</td>";
                                                    echo "<td align='right'>".number_format($row['spdeposits'],2,'.','')."</td>";
                                                        $ssinterest = $row['spdeposits']*($ssint/1200);
                                                    echo "<td align='right'>".number_format($ssinterest,2,'.','')."</td>";
                                                    echo "<td align='right'>".number_format($row['mdeposits'],2,'.','')."</td>";
                                                        $msinterest = $row['mdeposits']*($msint/1200);
                                                    echo "<td align='right'>".number_format($msinterest,2,'.','')."</td>";                                                   
// 																		  
													echo	  "</tr>";
													$slno = $slno +1;
                                                    $totalgs = $totalgs + $row['sdeposits'];
                                                    $totalps = $totalps + $row['spdeposits'];
                                                    $totalms = $totalms + $row['mdeposits'];
                                                    $totalgsint = $totalgsint + $gsinterest;
                                                    $totalpsint = $totalpsint + $ssinterest;
                                                    $totalmsint = $totalmsint + $msinterest;
                                                    //if($row['sdeposits'] != 0){
                                                        //$gsintinsert = mysqli_query($connection,"INSERT INTO acc_deposit_interest (date,clusterid,groupid,TransID,memid,subheadid,accno,details,receiptadj,remarks,timedate)
                                                                    //VALUES ('$today','$clusterid','$groupid','$transid','$memid','2','','Monthly Interest','$gsinterest','Auto Adjustment','')";
                                                    //}
												}	
                                                echo "<tr>
                                                    <td colspan='3' align='center'><b>Total</b></td>
                                                    <td align='right'><b>".number_format($totalgs,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalgsint,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalps,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalpsint,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalms,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalmsint,2, '.', '')."</b></td>

                                                    <td></td></tr>";
											}
											?>
												
											</tbody>
										</table>
									</div>
                                </div>
							</div>
						</div>

												<!--<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
																
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
																			<tr>													
																				<th class="detail-col">S.No.</th>
																				<th class="center">Member ID</th>
																				<th class="center">Member Name</th>													
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            
                                                                            // if($count>0){
                                                                            //     $slno=1;
                                                                            //     while($row = mysqli_fetch_assoc($result))
                                                                            //     { 	
                                            
                                                                            //         echo "<tr><td class='center'>".$slno."</td>"; 
                                                                            //         echo "<td class='center'><a href='acc_mem_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";																				
                                                                            //         echo "<td class='center'>".$row['memname']."</td>";
                                                                            //         $slno = $slno +1;					
                                                                            //     }				
																		    // }
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
												</div> -->
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

<script>
function removeHighlighting(highlightedElements){
       highlightedElements.each(function(){
        var element = $(this);
        element.replaceWith(element.html());
	     })
}

function addHighlighting(element, textToHighlight){
     var text = element.text();
		 var regEx = new RegExp(textToHighlight, "ig");
     var highlightedText = '<span style="background-color: yellow;">' + textToHighlight + '</span>';
     var newText = text.replace(regEx, highlightedText);
     element.html(newText);
}
$(document).ready(function(){
$("#memsearch").on("keyup", function() {
     var value = $(this).val().toLowerCase();
    
     removeHighlighting($("table tr span"));

     $("table tr").each(function(index) {
         if (index != 0) {
             $row = $(this);
            
       
			 var $tdElement2 = $row.find("td:eq(2)");
			 var $tdElement6 = $row.find("td:eq(6)");
			 
			 var id2 = $tdElement2.text().toLowerCase();
			 var matchedIndex2 = id2.indexOf(value);
			 var id6 = $tdElement6.text().toLowerCase();
			 var matchedIndex6 = id6.indexOf(value);
			       if ( matchedIndex2 == -1 && matchedIndex6 == -1) {
                 $row.hide();
             }
             else {
                 
				         addHighlighting($tdElement2, value);
				         addHighlighting($tdElement6, value);
                 $row.show();
            }
        }
    });
  });
});
</script>