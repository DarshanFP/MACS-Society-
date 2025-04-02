<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president_reports";
	include("pdtsidepan.php");

    $sql3="SELECT ClusterID,ClusterName FROM cluster";
	$result3=mysqli_query($connection,$sql3);
?>

    <div class="main-content">
        <div class="main-content-inner">					
            <div class="page-content">
                <form method="post" target="_blank">
                <div class="page-header" >              
                    <h1>
                        Cluster Ledger 
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        <small>          
                            <label style="margin-left:10px">Cluster</label>
                            <select name="cluster" id="cluster"   style="font-size:11pt;height:30px;width:160px;" readonly>	
                            <?php
                                echo "<option></option>"; 
                                //echo "<option>All</option>";
                                while ($row3 = mysqli_fetch_assoc($result3)){ 												
                                    echo "<option value ='".$row3['ClusterID']."'>".$row3['ClusterName']."</option>";								
                                }
                            ?>
                            </select>                     
                            <label style="margin-left:10px">Month</label>
                            <select name="month" id="month"   style="font-size:11pt;height:30px;width:160px;" required >	
                            <?php 
                                echo "<option></option>";                        
                                echo "<option value='01'>January</option>";
                                echo "<option value='02'>February</option>";
                                echo "<option value='03'>March</option>";
                                echo "<option value='04' >April</option>";
                                echo "<option value='05'>May</option>";
                                echo "<option value='06'>June</option>";
                                echo "<option value='07'>July</option>";
                                echo "<option value='08'>August</option>";
                                echo "<option value='09'>September</option>";
                                echo "<option value='10'>October</option>";
                                echo "<option value='11'>November</option>";
                                echo "<option value='12'>December</option>";
                            ?>
                            </select>
                            <!-- <input type="date" name="fdate" id="fdate" style="font-size:11pt;height:30px;width:160px;" >-->
                            <label style="margin-left:10px">Year</label>
                            <select name="year" id="year"   style="font-size:11pt;height:30px;width:160px;" required >	
                                <?php
                                    $current = date("Y");   
                                    for($y=$current,$i=0 ; $i<6; $i++.$y--){
                                        echo "<option>".$y."</option>";
                                    }
                                ?>
                            </select>
                            <!-- <input type="date" name="year" id="year" style="font-size:11pt;height:30px;width:160px;" > -->
                            <button type="button" class = "btn btn-info btn-fill" id="retrieve">
                                Retrieve
                            </button>
                        </small>  
                    </h1>
                </div><!-- /.page-header -->
                
                <div class="row">
                    <div class="col-md-12"> <!-- PAGE CONTENT BEGINS -->
                        <div>
                            <div id="user-profile-1" class="user-profile row">
                                <div class="col-xs-12 col-sm-12">											

                                    <!--<div class="space-12"></div>-->

                                    
                                    <!--<div class="space-20"></div>-->
                                                                 
                                    <div class="widget-box transparent">
                                        <div class="widget-header widget-header-small">
                                            <h4 class="widget-title blue smaller">
                                                <i class="ace-icon fa fa-rss orange"></i>
                                                Cluster Ledger
                                            </h4>                                                    
                                            
                                    
                                        </div>
                                                        
                                        <div class="widget-body">
                                            <div class="widget-main padding-8">
                                                <div id="profile-feed-1" class="profile-feed">
                                                    <div class="profile-activity clearfix">
                                                        <div>
                                                        <table style="font-size:x-small" id="simple-table" class="table  table-bordered table-hover">
                                                                <thead id='theads1'>
                                                                    <tr>														
                                                                        <th style="text-align: center;" rowspan="2">GroupID</th>														
                                                                        <th style="text-align: center;" rowspan="2">GroupName</th>														                            
                                                                        <th style="text-align: center;" colspan="3">General Savings</th>																				                                    	
                                                                        <th style="text-align: center;" colspan="3">Special Savings</th>																				                                    	
                                                                        <th style="text-align: center;" colspan="3">Marraige Savings</th>																				                                    	
                                                                        <th style="text-align: center;" colspan="4">General Loan</th>                                                                                
                                                                    </tr>
                                                                    <tr>
                                                                        <th style="text-align: center;">Received</th>																				                                    	
                                                                        <th style="text-align: center;">Payment</th>																				                                    	
                                                                        <th style="text-align: center;">Balance</th>																				                                    	
                                                                        <th style="text-align: center;">Received</th>																				                                    	
                                                                        <th style="text-align: center;">Payment</th>																				                                    	
                                                                        <th style="text-align: center;">Balance</th>																				                                    	
                                                                        <th style="text-align: center;">Received</th>																				                                    	
                                                                        <th style="text-align: center;">Payment</th>																				                                    	
                                                                        <th style="text-align: center;">Balance</th>																				                                    	
                                                                        <th style="text-align: center;">Received</th>																				                                    	                                                                                																				                                    	
                                                                        <th style="text-align: center;">Payment</th>																				                                    	
                                                                        <th style="text-align: center;">Balance</th>																				                                    	
                                                                        <th style="text-align: center;">Interest</th>                                                                                
                                                                    </tr>
                                                                </thead>
                                                                	
                                                                <tbody id="trows1">
                                                                   
                                                                </tbody>
                                                            </table>
                                                            <table style="font-size:x-small" id="simple-table" class="table  table-bordered table-hover">
                                                                <thead id='theads1'>
                                                                    <tr>														
                                                                    <th style="text-align: center;" rowspan="2">GroupID</th>														
                                                                        <th style="text-align: center;" rowspan="2">GroupName</th>														
                                                                        <th style="text-align: center;" colspan="2">Mutual Aid</th>
                                                                        <th style="text-align: center;" rowspan="2">Application Charges</th>
                                                                        <th style="text-align: center;" rowspan="2">Documentation Charges</th>
                                                                        <th style="text-align: center;" rowspan="2">Membership Fee</th>
                                                                        <th style="text-align: center;" rowspan="2">Stationary Charges</th>
                                                                    </tr>                                                                  
                                                                    <tr>
                                                                        <th style="text-align: center;">Receipt</th>
                                                                        <th style="text-align: center;">Payment</th>
                                                                    </tr>          
                                                                </thead>

                                                                <tbody id="trows2">
                                                                
                                                                    
                                                                </tbody>
                                                            </table>
                                                            <button type="submit" formaction="pdt_rep_cluster_ledger_excel.php" class="btn btn-success">Export to Excel</button>
                                                            <button type="button" class="btn btn-app btn-light btn-xs" onclick="window.print()"><i class="ace-icon fa fa-print bigger-160"></i>Print</button>
                                                            
                                                            <a href="president_reports.php"><button type="button" id="back" class="btn btn-primary" style="float:right;">
                                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                            </button></a>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="../image/loader.gif" id="gif" style="display:block; margin-left:400px; width: 300px; visibility:hidden;">
                                        
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

<script type="text/javascript">
    $(document).ready(function()
	{ 	
		$('#retrieve').click(function() 
		{   
            
		    $('#gif').css('visibility','visible');
	        
            $('#trows1').hide();
            $('#trows2').hide();
            $("#retrieve").prop('disabled', true);	       
				       
            var today ='<?php echo date("Y-m-d");?>';
            var cluster = $("#cluster").val();
            var month = $("#month").val();
            var year = $("#year").val();
            var tdate = year+'-'+month+'-01';        
            //alert("Long Query Please wait some time");
            if(tdate < today )
            {
                $.ajax({  
                    type: "POST",  
                    url: "pdt_rep_cluster_ledger_view.php",  
                    data: {month: month, year: year, cluster: cluster}, 
                    datatype: "html",
                    success: function(response){
                        //alert(response);
                        $('#gif').css('visibility','hidden');
                        var data = response.split("|");                        
                        $("#trows1").html(data[1]);							
                        $("#trows1").show();
                        $("#trows2").html(data[2]);							
                        $("#trows2").show();
                        $("#retrieve").prop('disabled', false);
                    },
                });
            }
            else{
                $('#gif').css('visibility','hidden');
                $('#trows1').hide();
                $('#trows2').hide();
                alert("Please select previous months");
                $("#retrieve").prop('disabled', false);
            }
		});
	    return false;
	});
</script>
