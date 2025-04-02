<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_employee";
	include("pdtsidepan.php"); 
	$sql 	= "Select
				  employee.empid,
				  employee.empname,
				  employee.empmobile,
				  employee.empaddress,
          employee.empstatus
				From
				  employee 
				Where
				  employee.empstatus = 1";
	$result = mysqli_query($connection,$sql) or die(mysqli_error());
	$count = mysqli_num_rows($result);		
?>
            
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Employees
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div class="row">
									 <div class = "col-md-3">	
									  <p style="padding:12px"><a href="pdt_emp_new.php"><button class="btn btn-success">Add New Employee</button></a></p>
									 </div>
									 <div class = "col-md-9">	
									  <form class="form-horizontal" role="form" method="post" action="">
									   <button class="btn btn-search" type="submit" style="float:right;height:42pxmargin-right:2px; margin-top:15px;"><i class='ace-icon fa fa-search bigger-120'></i></button>
									   <input type="text" id="empsearch" name="empsearch"  style="float:right;height:42px;margin-top:15px;" placeholder="Search" class="col-xs-4 col-sm-2" autocomplete="off" required  />
									  </form>
									 </div>
								</div>								
								
								<div class="row">
									<div class="col-xs-12">
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col">Employee ID</th>
													<th class="center">Employee Name</th>													
													<th class="center">Mobile No.</th>
													<th class="center">Address</th>													
													<th class="center">Status</th>	
													<th class="center" width="50px">Terminate</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count>0){
												$slno=1;
												while($row = mysqli_fetch_assoc($result))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";	
													
                          echo "<td><a href='pdt_emp_profile.php?empid=".$row['empid']."'>".$row['empid']."</a></td>";													
													echo "<td>".$row['empname']."</td>";					
													echo "<td class='center'>".$row['empmobile']."</td>";
													echo "<td>".$row['empaddress']."</td>";	
                          if($row['empstatus'] == 1)
                            echo "<td>Working</td>";
                          else
                            echo "<td>Terminated</td>";
                            
													echo "<td class='center'>
															  <a href='pdt_emp_ter.php?empid=".$row['empid']."'>
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
$("#empsearch").on("keyup", function() {
     var value = $(this).val().toLowerCase();
    
     removeHighlighting($("table tr span"));

     $("table tr").each(function(index) {
         if (index != 0) {
             $row = $(this);
            
       
			 var $tdElement2 = $row.find("td:eq(2)");
			 var $tdElement4 = $row.find("td:eq(4)");
			 
			 var id2 = $tdElement2.text().toLowerCase();
			 var matchedIndex2 = id2.indexOf(value);
			 var id4 = $tdElement4.text().toLowerCase();
			 var matchedIndex4 = id4.indexOf(value);
			       if (matchedIndex2 == -1 && matchedIndex4 == -1) {
                 $row.hide();
             }
             else {
       
				         addHighlighting($tdElement2, value);
				         addHighlighting($tdElement4, value);
                 $row.show();
            }
        }
    });
  });
});
</script>