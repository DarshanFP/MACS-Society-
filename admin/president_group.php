<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_group";
	include("pdtsidepan.php");
	$sql 	= "SELECT groups.*, cluster.ClusterName FROM groups, cluster WHERE groups.ClusterID = cluster.ClusterID";
	//if($_SERVER["REQUEST_METHOD"] == "POST") {
		//$name = $_POST['ddosearch'];	
		//$sql .= " WHERE ddoname LIKE '%$name%' OR ddoid LIKE '%$name%' OR  ddophone LIKE '%$name%' OR  ddooffice LIKE '%$name%'";					
	//}	
 	
	$result = mysqli_query($connection,$sql) or die(mysqli_error());
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
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class = "col-md-3">	
									  <p style="padding:12px"><a href="pdt_group_new.php"><button class="btn btn-success">Add New Group</button></a></p>
									 </div>
									 <div class = "col-md-9">	
									  <form class="form-horizontal" role="form" method="post" action="">
									   <button class="btn btn-search" type="submit" style="float:right;height:42pxmargin-right:2px; margin-top:15px;"><i class='ace-icon fa fa-search bigger-120'></i></button>
									   <input type="text" id="ddosearch" name="ddosearch"  style="float:right;height:42px;margin-top:15px;" placeholder="Search" class="col-xs-4 col-sm-2" autocomplete="off" required  />
									  </form>
									 </div>
								</div>								
								
								<div class="row">
									<div class="col-xs-12">
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="center" width="100">Sl.No.</th>
													<th class="center" width="200">Group ID</th>
													<th class="center" width="250">Group Name</th>
													<th class="center" >Address</th>
													<th class="center" width="200">Phone No.</th>		
                          <th class="center" >Cluster</th>
                          
												</tr>
											</thead>

											<tbody>
											<?php if($count>0){
												$slno=1;
												while($row = mysqli_fetch_assoc($result))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";
												  echo "<td><a href='pdt_group_det.php?groupid=".$row['GroupID']."'>".$row['GroupID']."</a></td>";					                          
													echo "<td>".$row['GroupName']."</td>";
													echo "<td>".$row['Address']."</td>";
													echo "<td>".$row['Mobile']."</td>";
                          echo "<td>".$row['ClusterName']."</td>";
													echo "</tr>";
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
$("#ddosearch").on("keyup", function() {
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