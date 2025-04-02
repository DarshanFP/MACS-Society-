<?php     
  include("pdt_session.php");
	$_SESSION['curpage']="president_report";
	include("pdtsidepan.php");
		
    $sql3="SELECT ClusterID,ClusterName FROM cluster";
	  $result3=mysqli_query($connection,$sql3);    

?>

    <div class="main-content">
        <div class="main-content-inner">					
            <div class="page-content">
            <form method="post" target="_blank">
                <div class="page-header">
                <h1> Cash Book
                    <label style="margin-left:10px">Cluster</label>
                        <select name="cluster" id="cluster" style="font-size:11pt;height:30px;width:200px;"  required >	
                            <?php 
                                echo "<option></option>";                            
                                echo "<option>All</option>";                            
                                while ($row3 = mysqli_fetch_assoc($result3)) 												
                                echo "<option value ='".$row3['ClusterID']."'>".$row3['ClusterName']."</option>";								
                            ?>
                        </select>
                    <label style="margin-left:10px">From Date</label>
                    <input type="date" name="fdate" id="fdate" style="font-size:11pt;height:30px;width:160px;" >
                    <label style="margin-left:10px">To Date</label>
                    <input type="date" name="tdate" id="tdate" style="font-size:11pt;height:30px;width:160px;" >
                    <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                        Retrieve
                    </button>
                </h1>
                </div><!-- /.page-header -->
                <div class="row">
                    <div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                        <div class="widget-box transparent">
                            <div class="widget-header widget-header-small">
                                <h4 class="widget-title blue smaller">
                                    <i class="ace-icon fa fa-bars orange"></i>
                                    Cash Book
                                </h4>                                                    
                            </div>

                            <div class="widget-body">
                                <div class="widget-main padding-8">
                                    <div id="profile-feed-1" class="profile-feed">
                                        <div class="profile-activity clearfix">
                                            <div>
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
                                                <button type="submit" formaction="pdt_rep_cb_excel.php" class = "btn btn-info btn-fill" style="height:50px;width:140px">
                                                Export to Excel
                                                </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <img src="../image/loader.gif" id="gif" style="display:block; margin-left:400px; width: 300px; visibility:hidden;">
                            </div>
                            <!-- <button type="submit" formaction="president_reports_cb_pdf.php" class = "btn btn-info btn-fill" style="height:50px;width:140px">
                            Export to PDF
                            </button> -->
                            </div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
            
          
<script type="text/javascript">
    $(document).ready(function()
		{ 	
			$('#retrieve').click(function() 
			{   
				$('#gif').css('visibility','visible');
                
                var cluster = $("#cluster").val();
                var today ='<?php echo date("Y-m-d");?>';
				var fdate = $("#fdate").val();
                var tdate = $("#tdate").val();
							
				if(fdate <= today && tdate <= today)
				{
					
					 	$.ajax({  
						type: "POST",  
						url: "pdt_rep_cb_view.php",  
						data: {cluster: cluster,fdate: fdate,tdate: tdate}, 
						datatype: "html",
						success: function(data){
							//alert(data);
                            $('#gif').css('visibility','hidden');
							$('#trows1').html(data);							
					 		$('#trows1').show();
						  }	 
				  });
				}
				else{
                    $('#gif').css('visibility','hidden');
					$('#trows1').hide();
					alert("Please select previous dates");
				}
			});
	    return false;
			});
	</script>
  <script>
		$(document).ready(function(){
 		  $('.js-example-basic-single').select2();
		});	
  </script>          

<?php 
	include("footer.php");    
?>	
					
