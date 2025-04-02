<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
	$sql 	= "Select
				  members.memid,
				  members.memname,
				  members.memaddress,
                  members.memphone,
                  members.bankname,
                  members.bankaccountno,
				  groups.GroupName,				  
				  cluster.ClusterName
				From
				  members,groups,cluster
				WHERE 
			      memgroupid = GroupID AND groups.ClusterID = cluster.ClusterID AND groups.ClusterID = '$clusterid' 		
				ORDER BY members.memid";
	$result = mysqli_query($connection,$sql);
	$count = mysqli_num_rows($result);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Members 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
                  <a href="acc_mem_rec.php"><button class="btn btn-success">Receipt</button></a>									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							    <div class="row">
										<div class = "col-md-2">
									 		<p style="padding:12px">
                                                <a href="acc_mem_new.php"><button class="btn btn-success">Add New Member</button></a>
                                            </p>		
                                        </div>
                                        <div class = "col-md-2">
									 		<p style="padding:12px">
                                                <a href="acc_mem_transfer.php"><button class="btn btn-success">Member Transfer</button></a>
                                            </p>		
										</div>
										<div class = "col-md-8">
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
													<th class="center">Address</th>
                                                    <th class="center">Mobile No.</th>
                                                    <th class="center">Bank Name</th>
                                                    <th class="center">Bank Account No.</th>
													<th class="center">GroupName</th>
													<!--<th class="center">ClusterName</th>-->
													<th class="center" width="50px">Edit</th>
                                                    <!-- <th class="center" width="50px">Delete</th> -->
												</tr>
											</thead>

											<tbody>
											<?php if($count>0){
												$slno=1;
												while($row = mysqli_fetch_array($result))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";
													echo "<td class='center'><a href='acc_mem_det.php?memid=".$row['memid']."'>".$row['memid']."</a></td>";
													
													echo "<td>".$row['memname']."</td>";
													echo "<td class='left'>".$row['memaddress']."</td>";
                                                    echo "<td>".$row['memphone']."</td>";
                                                    echo "<td>".$row['bankname']."</td>";
                                                    echo "<td>".$row['bankaccountno']."</td>";
													echo "<td class='center'>".$row['GroupName']."</td>";
													//echo "<td class='center'>".$row['ClusterName']."</td>";
													echo "<td class='center'>
															  <a href='acc_mem_edit.php?memid=".$row['memid']."'>
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
												}				
											}
											?>
												
											</tbody>
										</table>
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