<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
	include("accountssidepan.php");

    $sql2="SELECT GroupID,GroupName FROM groups WHERE ClusterID='$clusterid'";
	$result2=mysqli_query($connection,$sql2);

?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
            <form method="post" target="_blank">
						<div class="page-header" >
              
							<h1>
								Reports - Cash Book 
                  <i class="ace-icon fa fa-angle-double-right"></i>
                  <small>          
                    <label style="margin-left:10px">Groups</label>
                    <select name="group" id="group"   style="font-size:11pt;height:30px;width:160px;" required >	
                      <?php 
                        echo "<option></option>";
                        echo "<option>All</option>";
                        while ($row2 = mysqli_fetch_assoc($result2)) 												
                        echo "<option value ='".$row2['GroupID']."'>".$row2['GroupName']."</option>";								
                      ?>
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
                            <th rowspan="2" style="text-align: center;">Date</th>														
                            <th rowspan="2" style="text-align: center;">Transaction ID</th>														
                            <th rowspan="2" style="text-align: center;">Member ID</th>														                            
                            <th rowspan="2" style="text-align: center;">Details</th>																				                                    	
                            <th colspan="2" style="text-align: center;">Cash</th>
                            <th colspan="2" style="text-align: center;">Adjustment</th>														
                          </tr>
                            <th style="text-align: center;">Receipt</th>
                            <th style="text-align: center;">Payment</th>
                            <th style="text-align: center;">Receipt</th>
                            <th style="text-align: center;">Payment</th>
                          <tr>
                          </tr>
                        </thead>	
                        <tbody id="trows1">
                                        
                        </tbody>
                        </table>
                        <button type="submit" formaction="acc_rep_cashbook_excel.php" class="btn btn-success">Export to Excel</button>
                        <!-- <button type="submit" formaction="acc_rep_cashbook_pdf.php" class="btn btn-success">Export to pdf</button> -->
                    </div>										                 
                </div>
			</div>
            </form>  
            <img src="../image/loader.gif" id="gif" style="display:block; margin-left:400px; width: 300px; visibility:hidden;">
                                
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			

	<script type="text/javascript">
    $(document).ready(function()
		{ 	
			$('#retrieve').click(function() 
			{   
				$('#gif').css('visibility','visible');
                var group = $("#group").val();
                var today ='<?php echo date("Y-m-d");?>';
				var fdate = $("#fdate").val();
                var tdate = $("#tdate").val();
        
        
				if(tdate <= today && fdate <=tdate)
				{
                        //alert("hello");
					 	$.ajax({  
						type: "POST",  
						url: "acc_rep_cb_view.php",  
						data: {group: group, fdate: fdate, tdate: tdate}, 
						datatype: "html",
						success: function(data){
                            $('#gif').css('visibility','hidden');
							//alert(data);
							$('#trows1').html(data);							
					 		$('#trows1').show();
						  }	 
				  });
				}
				else{
                    $('#gif').css('visibility','hidden');
					$('#trows1').hide();
					alert("Please select proper date range");
				}
			});
	    return false;
			});
	</script>
