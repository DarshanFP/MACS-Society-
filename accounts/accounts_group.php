<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_group";
	include("accountssidepan.php");
	$sql = "SELECT A.ClusterID,A.GroupName, A.GroupID, gs, ps, ms, mid, loans 
                    FROM (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as gs 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 2) AS gsdeposit
						 ON members.memid = gsdeposit.memid GROUP BY GroupID) AS A, 
						 
						 (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as ps 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 3) AS spdeposit
						 ON members.memid = spdeposit.memid GROUP BY GroupID) AS B, 
						 
						 (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as ms 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 4) AS mdeposit
						 ON members.memid = mdeposit.memid GROUP BY GroupID) AS C, 
						 
						 (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as mid 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 10) AS middeposit
						 ON members.memid = middeposit.memid GROUP BY GroupID) AS F, 
						 
                         (SELECT E.ClusterID,GroupName, GroupID ,sum(cb) as loans 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS E 
                         LEFT JOIN members ON E.GroupID = members.memgroupid 
                         LEFT JOIN acc_loans ON members.memid = acc_loans.memid GROUP BY GroupID) AS G 
						 
                         WHERE A.GroupID = B.GroupID AND A.GroupID = C.GroupID AND A.GroupID = F.GroupID AND A.GroupID = G.GroupID";                        
        $result = mysqli_query($connection, $sql);
        $count = mysqli_num_rows($result);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Groups
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-10"> <!-- PAGE CONTENT BEGINS -->
							    <div class="row">
										<div class = "col-md-3">
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
													<th class="detail-col">S.No.</th>
                                                    <th class="center">Group ID</th>
                                                    <th class="center">Group Name</th>													
                                                    <th class="center">Members</th>
                                                    <th class="center">General Savings</th>
                                                    <th class="center">Special Savings</th>
                                                    <th class="center">Marraige Savings</th>
                                                    <th class="center">Mutual Aid</th>
                                                    <th class="center">Total Loans</th>
												</tr>
											</thead>

											<tbody>
											<?php 
                                                                            
                                                if($count>0){
                                                    $slno=1;
                                                    $gsdeposit = 0;
                                                    $psdeposit = 0;
                                                    $msdeposit = 0;
                                                    $middeposit = 0;
                                                    $loan = 0;
                                                    while($row = mysqli_fetch_assoc($result))
                                                    { 	
                                                        $groupid = $row['GroupID'];
                                                        $sql2 = mysqli_query($connection,"SELECT * FROM members WHERE memgroupid = '$groupid' AND memstatus = 1");
                                                        $count2 = mysqli_num_rows($sql2); 
                
                                                        echo "<tr><td class='center'>".$slno."</td>"; 
                                                        echo "<td><a href='acc_group_det.php?groupid=".$groupid."'>".$groupid."</a></td>";					                          
                                                        echo "<td class='center'>".$row['GroupName']."</td>";
                                                        echo "<td class='center'>".$count2."</td>";
                                                        echo "<td align = 'right'>".number_format($row['gs'],2,'.','')."</td>";
                                                        echo "<td align = 'right'>".number_format($row['ps'],2,'.','')."</td>";
                                                        echo "<td align = 'right'>".number_format($row['ms'],2,'.','')."</td>";
                                                        echo "<td align = 'right'>".number_format($row['mid'],2,'.','')."</td>";
                                                        echo "<td align = 'right'>".number_format($row['loans'],2,'.','')."</td></tr>";                                                        
                                                         
                                                        $gsdeposit = $gsdeposit + $row['gs'];
                                                        $psdeposit = $psdeposit + $row['ps'];
                                                        $msdeposit = $msdeposit + $row['ms'];
                                                        $middeposit = $middeposit + $row['mid'];
                                                        $loan = $loan + $row['loans'];
                                                        $slno = $slno +1;					
                                                    }				
                                                    echo "<tr><td colspan='4'>Total</td>";
                                                    echo "<td align ='right'>".number_format($gsdeposit,2,'.','')."</td>";
                                                    echo "<td align ='right'>".number_format($psdeposit,2,'.','')."</td>";
                                                    echo "<td align ='right'>".number_format($msdeposit,2,'.','')."</td>";
                                                    echo "<td align ='right'>".number_format($middeposit,2,'.','')."</td>";
                                                    echo "<td align ='right'>".number_format($loan,2,'.','')."</td></tr>";

                                                }
                                                ?>
												
											</tbody>
										</table>
									</div>
								</div
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