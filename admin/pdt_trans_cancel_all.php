<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="accounts";
    include("pdtsidepan.php");
    
     $sql5 = mysqli_query($connection,"SELECT DISTINCT acc_cashbook.TransID,acc_cashbook.clusterid,CancelStatus FROM acc_cashbook, acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND CancelStatus IN (1,2,3)");
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Transaction View All
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
                        </div><!-- /.page-header -->
                        
						<div class="row">
                            <div class = "col-md-10">
                                <form  role="form" method="post" action="">
                                    <button class="btn btn-search" type="submit" style="float:right;height:42px;margin-right:2px; margin-top:15px;"><i class='ace-icon fa fa-search bigger-120'></i></button>
                                    <input type="text" id="memsearch" name="memsearch"  style="float:right;height:42px; margin-top:15px;" placeholder="Search" class="col-xs-4 col-sm-2" autocomplete="off" required  />
                                </form>													
						    </div>
                            <div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                            
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-10">											

                                            <!-- <div class="space-20"></div> -->

                                            <div class="content table-responsive table-full-width">
                                                <table class="table" border=1>	
                                                <thead>
                                                    <th align="center">Sl.No</th>
                                                    <th align="center">Cluster Name</th> 
                                                    <th align="center">TransID</th>                                                    
                                                    <th align="center">Cancel Status</th>
                                                    <th align="center"></th>
                                                </thead>	
                                                <tbody id=transrows>
                                                <?php    
                                                    $sno = 1;
                                                    while($row5=mysqli_fetch_assoc($sql5)){
                                                        echo "<tr>";
                                                        echo "<td align='center'>".$sno."</td>";
                                                        $clusterid = $row5['clusterid'];
                                                        $sql1 = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID = '$clusterid'");
                                                        $row1 = mysqli_fetch_assoc($sql1);
                                                        $clustername = $row1['ClusterName'];
                                                        echo "<td align='center'>".$clustername."</td>";
                                                        $transid = $row5['TransID']; 
                                                        echo "<td align='center'>".$row5['TransID']."</td>";

                                                        if($row5['CancelStatus']==1){
                                                            echo "<td class='center'><span class='label label-primary'>Pending</span></td>";
                                                        }else if($row5['CancelStatus']==2){
                                                            echo "<td class='center'><span class='label label-success'>Accepted</span></td>";
                                                        }else if($row5['CancelStatus']==3){                                                                                    
                                                            echo "<td class='center'><span class='label label-danger'>Rejected</span></td>";
                                                        }
                                                        echo "<td align='center'><a href='pdt_trans_view_suc_cancel.php?transid=$transid'><button class'btn-primary'>View</button></a></td>";
                                                        echo "</tr>"; 
                                                        $sno++;     
                                                    }
                                                ?>        
                                                </tbody>
                                                </table>
                                            </div>
                                            <a id="link" href="president.php"><button id="back" class="btn btn-primary" style="float:right;">
                                                <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                            </button></a>                 
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
                   
			 var $tdElement1 = $row.find("td:eq(1)");
			 var $tdElement2 = $row.find("td:eq(2)"); 
			 
			 var id1 = $tdElement1.text().toLowerCase();
             var matchedIndex1 = id1.indexOf(value);
             var id2 = $tdElement2.text().toLowerCase();
			 var matchedIndex2 = id2.indexOf(value);
			 
			 if ( matchedIndex1 == -1 ) {
                 $row.hide();
             }
             else {
                 addHighlighting($tdElement1, value);
			     $row.show();
             }
             if ( matchedIndex2 == -1 ) {
                 $row.hide();
             }
             else {
                 addHighlighting($tdElement2, value);
			     $row.show();
             }
        }
    });
  });
});
</script>
    
