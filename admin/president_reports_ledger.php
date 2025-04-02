<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president_reports_ledger";
	include("pdtsidepan.php");

 $sql3="SELECT ClusterID,ClusterName FROM cluster";
 $result3=mysqli_query($connection,$sql3);    

  $sql2="SELECT SubID, SubHead FROM acc_subhead";
	$result2=mysqli_query($connection,$sql2);
  

?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
            <form method="post" target="_blank">
						<div class="page-header" >
              
							<h1>
								Ledger  
                  <i class="ace-icon fa fa-angle-double-right"></i>
                  <small>          
                    <label style="margin-left:10px">Sub Head</label>
                    <select name="subid" id="subid"   style="font-size:11pt;height:30px;width:160px;" required >	
                      <?php 
                        echo "<option></option>";                        
                        while ($row2 = mysqli_fetch_assoc($result2)) 												
                        echo "<option value ='".$row2['SubID']."'>".$row2['SubHead']."</option>";								
                      ?>
                    </select> 
                    
                    <label style="margin-left:10px">Cluster</label>
                    <select name="cluster" id="cluster"   style="font-size:11pt;height:30px;width:160px;" required >	
                      <?php 
                        echo "<option></option>";                        
                      echo "<option>All</option>";
                        while ($row3 = mysqli_fetch_assoc($result3)) 												
                        echo "<option value ='".$row3['ClusterID']."'>".$row3['ClusterName']."</option>";								
                      ?>
                    </select>                     
                    
                    <label style="margin-left:10px">Group</label>
                    <select name="group" id="group"   style="font-size:11pt;height:30px;width:160px;" required >	
                      <option></option>
                      <option>All</option>
                    </select>                     
                    <label style="margin-left:10px">From Date</label>
                    <input type="date" name="fdate" id="fdate" style="font-size:11pt;height:30px;width:160px;" >
                    <label style="margin-left:10px">To Date</label>
                    <input type="date" name="tdate" id="tdate" style="font-size:11pt;height:30px;width:160px;" >
                    <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                        Retrieve
                    </button>
                  </small>  
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-12"> <!-- PAGE CONTENT BEGINS -->

									<div class="content table-responsive table-full-width">
											<table class="table table-bordered table-hover">	
                        <thead>
                          <tr>														
                            <th style="text-align: center;">TransID</th>														
                            <th style="text-align: center;">Date</th>														                            
                            <th style="text-align: center;">Details</th>																				                                    	
                            <th style="text-align: center;">Opening Balance</th>                            
                            <th style="text-align: center;">Receipt</th>                            
                            <th style="text-align: center;">Payment</th>                            
                            <th style="text-align: center;">Closing Balance</th>                            
                          </tr>
                        </thead>	
												<tbody id="trows1">
															
												</tbody>
											</table>
											<button type="submit" formaction="pdt_rep_led_excel.php" class="btn btn-success">Export to Excel</button>
											<button type="submit" formaction="pdt_rep_led_pdf.php" class="btn btn-success">Export to pdf</button>
										</div>										                 
                
                
              
              </div>
						</div>
            </form>  
              
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			

	<script type="text/javascript">
    $(document).ready(function()
		{ 	
      
      $('#cluster').change(function(){
        var cluster = $('#cluster').val();   
        $('#group').empty();
        $.ajax({
          type: "POST",
          url:"president_reports_led_group.php",
          data : {cluster: cluster},
          datatype:"html",
          success: function(data){                        
            $('#group').append(data);            
          }
        });
      });
      
      
      
			$('#retrieve').click(function() 
			{   
				       
        var today ='<?php echo date("Y-m-d");?>';
        var subid = $("#subid").val();
        var group = $("#group").val();
				var fdate = $("#fdate").val();
        var tdate = $("#tdate").val();
        var cluster = $('#cluster').val();
        
        
				if(tdate <= today )
				{
					
					 	$.ajax({  
						type: "POST",  
						url: "president_rep_led_view.php",  
						data: {subid: subid, fdate: fdate, tdate: tdate, group: group, cluster:cluster }, 
						datatype: "html",
						success: function(data){
							//alert(data);
							$('#trows1').html(data);							
					 		$('#trows1').show();
						  }	 
				  });
				}
				else{
					$('#trows1').hide();
					alert("Please select previous dates");
				}
			});
	    return false;
			});
	</script>
