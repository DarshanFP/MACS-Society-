<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");
	$exppending = mysqli_query($connection,"SELECT acc_cash_dummy_expenses.*, SubHead FROM acc_cash_dummy_expenses, acc_subhead
                                            WHERE  acc_cash_dummy_expenses.subheadid = acc_subhead.SubID
                                            AND acc_cash_dummy_expenses.clusterid = '$clusterid'");
  $expcount = mysqli_num_rows($exppending);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								All Expenses Status 
								<small>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-10 col-xs-12"> <!-- PAGE CONTENT BEGINS -->
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
													<th class="center">Sl.No.</th>
													<th class="center">Exp ID</th>
													<th class="Center">Sub Head</th>
													<th class="center">Purpose</th>
													<th class="center">Amount</th>
													<th class="Center">Status</th>
													
												</tr>
											</thead>

											<tbody>
											<?php if($expcount>0){
												$slno=1;
												while($row = mysqli_fetch_assoc($exppending))
												{ 
                          $status = $row['status'];
                          
													echo "<tr><td class='center'>".$slno."</td>";
													echo "<td align='center'>".$row['PaymentID']."</td>";
													echo "<td align='left'>".$row['SubHead']."</td>";
													echo "<td align='left'>".$row['remarks']."</td>";
													echo "<td align='right'>".number_format($row['paymentcash'],2)."</td>";
													if($status == 0){
                            echo "<td align='left'>To be appproved</td>";
                          }
                          else if($status == 1){
                            echo "<td align='left'>Approved with TransID:".$row['TransID']."</td>";
                          }
                          else if($status == 2){
                            echo "<td align='left'>Cancelled by you</td>";
                          }
                          else if($status == 3){
                            echo "<td align='left'>Rejected by admin</td>";
                          }
                          
                          
													echo	  "</tr>";
													$slno = $slno +1;					
												}				
											}
											?>
												
											</tbody>
										</table>
									</div>
                  
                  <div class="row">   
													<div class="col-md-8">

													</div>										
													<div class="col-md-4">
														<div class="form-group label-floating">												

															<a href="accounts.php"><button type="button" class="btn btn-info btn-fill pull-right"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
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
            
       
			 var $tdElement2 = $row.find("td:eq(1)");
			 
			 
			 var id2 = $tdElement2.text().toLowerCase();
			 var matchedIndex2 = id2.indexOf(value);
			 
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