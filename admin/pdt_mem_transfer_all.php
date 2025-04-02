<?php	    
	include("pdt_session.php");
    $user = $_SESSION['login_user'];
    include("pdtsidepan.php"); 
 		
    // $sql1 = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
    //                                 WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    // $row1 = mysqli_fetch_assoc($sql1);

    // $clusterid = $row1['ClusterID'];
?>    
    <div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								All Member Transfers 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
                  				</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							    <div class="row">
										<div class = "col-md-11">
										 	<form  role="form" method="post" action="">
												<button class="btn btn-search" type="submit" style="float:right;height:42px;margin-right:2px; margin-top:15px;"><i class='ace-icon fa fa-search bigger-120'></i></button>
												<input type="text" id="memsearch" name="memsearch"  style="float:right;height:42px; margin-top:15px;" placeholder="Search" class="col-xs-4 col-sm-2" autocomplete="off" required  />
											</form>													
										</div>
								</div>								
								
								<div class="row">
                                    <div class="col-xs-11">
                                    
        
           
        
        <table style="font-size:13px" id="memtransfers" class="table  table-bordered table-hover">
        <thead>
            <tr>														
                <th style='text-align: center;'>S.No.</th>
                <th style='text-align: center;'>Cluster Name</th>
                <th style='text-align: center;'>Member ID</th>
                <th style='text-align: center;'>Member Name</th>
                <th style='text-align: center;'>Present Group</th>
                <th style='text-align: center;'>Transfer to</th>
                <th style='text-align: center;'>Status</th>
            </tr>
        </thead>
        <tbody>
        
        <?php

            $memtransfer = mysqli_query($connection,"SELECT * FROM acc_mem_transfer_dummy");
            $memtranscount = mysqli_num_rows($memtransfer);
         
            $slno = 0;
            if($memtranscount > 0){
                while($rowtransfer = mysqli_fetch_assoc($memtransfer)){                                                                                                                                
                    echo "<tr>";
                    $sno = $slno+1;
                    echo "<td align='center'>".$sno."</td>";
                    $clusterid = $rowtransfer['ClusterID'];
                    $cluster = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID='$clusterid'");
                    $clustername = mysqli_fetch_assoc($cluster);
                    echo "<td align='center'>".$clustername['ClusterName']."</td>";
                    echo "<td align='center' id='memid".$slno."' name='memid'>".$rowtransfer['memid']."</td>";                                                                                    
                    echo "<td align='center'>".$rowtransfer['memname']."</td>";
                    $pgroupid = $rowtransfer['PGroupID'];
                    $pgroup = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID='$pgroupid'");
                    $pgroupname = mysqli_fetch_assoc($pgroup);
                    echo "<td align='center'>".$pgroupname['GroupName']."</td>";
                    $tgroupid = $rowtransfer['TGroupID'];
                    $tgroup = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID='$tgroupid'");
                    $tgroupname = mysqli_fetch_assoc($tgroup);
                    echo "<td align='center'>".$tgroupname['GroupName']."</td>";
                    if($rowtransfer['status']==0){
                        echo "<td class='center'><span class='label label-danger'>Rejected</span></td>";
                    }else if($rowtransfer['status']==1){
                        echo "<td class='center'><span class='label label-primary'>Pending</span></td>";
                    }else{                                                                                    
                        echo "<td class='center'><span class='label label-success'>Accepted</span></td>";
                    }
                    echo "</tr>";
                    $slno = $slno +1;
                }
            }
        echo "</tbody></table>";
        ?>
        <a href="president.php"><button class="btn btn-primary" style="float:right;">
            <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
        </button></a>
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
            
             var $tdElement1 = $row.find("td:eq(1)");   
			 var $tdElement2 = $row.find("td:eq(2)");
			 var $tdElement6 = $row.find("td:eq(6)");
			 
             var id1 = $tdElement1.text().toLowerCase();
			 var matchedIndex1 = id1.indexOf(value);
             var id2 = $tdElement2.text().toLowerCase();
			 var matchedIndex2 = id2.indexOf(value);
			 var id6 = $tdElement6.text().toLowerCase();
			 var matchedIndex6 = id6.indexOf(value);
			       if ( matchedIndex1 == -1 && matchedIndex2 == -1 && matchedIndex6 == -1) {
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