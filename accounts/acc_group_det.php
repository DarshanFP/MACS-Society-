<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_group";
	include("accountssidepan.php");
if(isset($_GET['groupid'])){
		$groupid = $_GET['groupid'];
		$sql = "SELECT A.*, B.*, C.*, D.* FROM 
                        (SELECT members.memid, members.memname, cashbook.receipt as GSReceipt, cashbook.payment as GSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$groupid' and acc_cashbook.subheadid = 2 and acc_cashbook.date <='$today' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$groupid' GROUP BY memid) as A, 
                        (SELECT members.memid, members.memname, cashbook.receipt as SSReceipt, cashbook.payment as SSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$groupid' and acc_cashbook.subheadid = 3 and acc_cashbook.date <='$today' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$groupid' GROUP BY memid) as B,
                        (SELECT members.memid, members.memname, cashbook.receipt as MSReceipt, cashbook.payment as MSPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$groupid' and acc_cashbook.subheadid = 4 and acc_cashbook.date <='$today' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$groupid' GROUP BY memid) as C,
                        (SELECT members.memid, members.memname, cashbook.receipt as GLReceipt, cashbook.payment as GLPayment from members LEFT JOIN (SELECT sum(receiptcash)+sum(receiptadj) as receipt, sum(paymentcash)+sum(paymentadj) as payment, memid FROM acc_cashbook, acc_transactions WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.groupid = '$groupid' and acc_cashbook.subheadid = 5 and acc_cashbook.date <='$today' GROUP BY acc_cashbook.memid) as cashbook ON cashbook.memid= members.memid WHERE members.memgroupid = '$groupid' GROUP BY memid) as D
                        WHERE B.memid = A.memid AND B.memid = C.memid AND C.memid = D.memid";
		$result = mysqli_query($connection, $sql);
    $count = mysqli_num_rows($result);

    $sql1 = "SELECT * from groups where GroupID ='$groupid'";
		$result1 = 	mysqli_query($connection, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
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
														Members under <?php echo $row1['GroupName']; ?> &nbsp;Group
													</h4>													
                                                </div>
                        
                        <div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							    <div class="row">
										<div class = "col-md-3">
									 		<p style="padding:12px">
                        <a href='acc_mem_new.php?groupid=<?php echo $groupid;?>'><button class="btn btn-success">Add New Member</button></a>
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
                                                    <th class="center">Sepcial Savings</th>
                                                    <th class="center">Marraige Deposit</th>                                                                                                  
                                                    <th class="center">Loans</th>
													<th class="center" width="50px">Edit</th>
                                                    <!--<th class="center" width="50px">Delete</th> -->
												</tr>
											</thead>

											<tbody>
											<?php if($count>0){
												$slno=1;
                                                $totalgs = 0;
                                                $totalps = 0;
                                                $totalms = 0;
                                                $totalaid = 0;
                                                $totalloans = 0;
												while($row = mysqli_fetch_assoc($result))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";
													echo "<td class='center'><a href='acc_mem_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";
													echo "<td>".$row['memname']."</td>";										
                                                    $gsavings = $row['GSReceipt'] - $row['GSPayment'];
                                                    $splsavings = $row['SSReceipt'] - $row['SSPayment'];
                                                    $marsavings = $row['MSReceipt']	- $row['MSPayment'];
                                                    $genloan = $row['GLPayment'] - $row['GLReceipt'];		
                                                    echo "<td align='right'>".number_format($gsavings,2,'.','')."</td>";
                                                    echo "<td align='right'>".number_format($splsavings,2,'.','')."</td>";
                                                    echo "<td align='right'>".number_format($marsavings,2,'.','')."</td>";                                                    
                                                    echo "<td align='right'>".number_format($genloan,2, '.', '')."</td>";
													
													echo "<td class='center'>
															  <a href='acc_mem_edit.php?memid=".$row['memid']."&groupid=".$groupid."'>
															  <button class='btn btn-xs btn-info'>
																<i class='ace-icon fa fa-pencil bigger-120'></i>
															  </button>
															  </a>							  
														  </td>";
// 													echo "<td class='center'>
// 															  <a href='acc_mem_del.php?memid=".$row['memid']."'>
// 															  <button class='btn btn-xs btn-danger'>
// 																<i class='ace-icon fa fa-trash-o bigger-120'></i>
// 															  </button>
// 															  </a></td>							  
													echo	  "</tr>";
													$slno = $slno +1;
                                                    $totalgs = $totalgs + $gsavings;
                                                    $totalps = $totalps + $splsavings;
                                                    $totalms = $totalms + $marsavings;                                                    
                                                    $totalloans = $totalloans + $genloan;					
												}	
                                                echo "<tr>
                                                    <td colspan='3' align='center'><b>Total</b></td>
                                                    <td align='right'><b>".number_format($totalgs,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalps,2, '.', '')."</b></td>
                                                    <td align='right'><b>".number_format($totalms,2, '.', '')."</b></td>                                                    
                                                    <td align='right'><b>".number_format($totalloans,2, '.', '')."</b></td>
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