<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_cluster";
	include("accountssidepan.php");
	$sql 	= "Select * FROM cluster ORDER BY ClusterID";
	$result = mysqli_query($connection,$sql);
	$count = mysqli_num_rows($result);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Cluster
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-10"> <!-- PAGE CONTENT BEGINS -->
							    <div class="row">
										<div class = "col-md-3">
									 		<p style="padding:12px"><a href="acc_cluster_new.php"><button class="btn btn-success">Add New Cluster</button></a></p>		
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
													<th class="detail-col">ClusterID</th>
													<th class="center">ClusterName</th>
													<th class="center">Address</th>
													<th class="center">Phone</th>
													<th class="center">MACS</th>
													<th class="center" width="50px">Edit</th>
													<th class="center" width="50px">Delete</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count>0){
												$slno=1;
												while($row = mysqli_fetch_assoc($result))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>"; ?>	
													
													<td class='center' onclick = "window.open('/accounts/acc_cluster_profile.php?ClusterID=<?php echo $row['ClusterID']; ?>', '_blank', 'resizable=yes, location=no, scrollbars=yes, width=500, height=400, top=150, left=500'); return false;" > <a href=""> <?php echo $row['ClusterID'] ?> </a></td>					
													
													<?php echo "<td>".$row['ClusterName']."</td>";
													echo "<td class='center'>".$row['Address']."</td>";
													echo "<td>".$row['Mobile']."</td>";
													echo "<td class='center'>".$row['MACSID']."</td>";
													echo "<td class='center'>
															  <a href='acc_cluster_edit.php?ClusterID=".$row['ClusterID']."'>
															  <button class='btn btn-xs btn-info'>
																<i class='ace-icon fa fa-pencil bigger-120'></i>
															  </button>
															  </a>							  
														  </td>";
													echo "<td class='center'>
															  <a href='acc_cluster_del.php?ClusterID=".$row['ClusterID']."'>
															  <button class='btn btn-xs btn-danger'>
																<i class='ace-icon fa fa-trash-o bigger-120'></i>
															  </button>
															  </a>							  
														  </td></tr>";
													$slno = $slno +1;					
												}				
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
            
       var $tdElement1 = $row.find("td:eq(1)");
			 var $tdElement2 = $row.find("td:eq(2)");
			 var $tdElement6 = $row.find("td:eq(6)");
			 var id1 = $tdElement1.text().toLowerCase();
			 var matchedIndex1 = id1.indexOf(value);
			 var id2 = $tdElement2.text().toLowerCase();
			 var matchedIndex2 = id2.indexOf(value);
			 var id6 = $tdElement6.text().toLowerCase();
			 var matchedIndex6 = id6.indexOf(value);
			       if (matchedIndex1 == -1 && matchedIndex2 == -1 && matchedIndex6 == -1) {
                 $row.hide();
             }
             else {
                 addHighlighting($tdElement1, value);
				         addHighlighting($tdElement2, value);
				         addHighlighting($tdElement6, value);
                 $row.show();
            }
        }
    });
  });
});
</script>					