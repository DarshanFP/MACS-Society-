<?php	  
	include("pdt_session.php");
	include("pdtsidepan.php");
    if($_SERVER['REQUEST_METHOD']=='POST' || isset($_GET['memid'])){
        if(isset($_GET['memid'])){
            $memid = $_GET['memid']; 
            $back = 0; 
        }
        else{
            $memid = $_POST['memid'];     
            $back = 1;
        }
        if($back==0){
            $_SESSION['curpage']="accounts_member";
        }else{
            $_SESSION['curpage']="accounts_reports";
        }
        
        $month = date('m');
        
        if($month > 4)
        {
            $y = date('Y');
            $nexty = date('Y', strtotime('+1 year'));
            $start = $y."-04-01";
            $end = $nexty."-03-31";
        }
        else
        {
            $y = date('Y', strtotime('-1 year'));
            $nexty = date('Y');
            $start = $y."-04-01";
            $end = $nexty."-03-31";
        }

        $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
        $result1 = mysqli_query($connection, $sql1);
        $row1 = mysqli_fetch_assoc($result1); 
        
        $sql3 ="SELECT month,
                        max(case when subhead = 2 then receipt else 0 end) '2',
                        max(case when subhead = 2 then payment else 0 end) 'General Savings',
                        max(case when subhead = 3 then receipt else 0 end) '3',
                        max(case when subhead = 3 then payment else 0 end) 'Special Savings',
                        max(case when subhead = 4 then receipt else 0 end) '4',
                        max(case when subhead = 4 then payment else 0 end) 'Marriage Savings',
                        max(case when subhead = 5 then receipt else 0 end) '5',
                        max(case when subhead = 5 then payment else 0 end) 'General Loan',
                        max(case when subhead = 6 then receipt else 0 end) '6',
                        max(case when subhead = 6 then payment else 0 end) 'Interest Received on Loans',
                        max(case when subhead = 10 then receipt else 0 end) '10',
                        max(case when subhead = 10 then payment else 0 end) 'Mutual Aid Fund'
                FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment 
                      FROM acc_cashbook,acc_transactions 
                      WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' GROUP BY subheadid,month) AS A GROUP BY month 
                      ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";

        $result3=mysqli_query($connection,$sql3);
        
        $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                 FROM acc_subhead,acc_cashbook,acc_transactions 
                                                 WHERE SubID=subheadid AND SubID IN (2,3,4,5,6,10) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memid = '$memid' GROUP BY subheadid");
        $GSob=0;
        $SSob=0;
        $MSob=0;
        $GLob=0;
        while ($rowobs = mysqli_fetch_assoc($ledgerobs)){

            if($rowobs['subheadid'] == 2){
                $GSob = $rowobs['rcash'] + $rowobs['radj'] - $rowobs['pcash'] - $rowobs['padj'];
            }
            elseif($rowobs['subheadid'] == 3){
                $SSob = $rowobs['rcash'] + $rowobs['radj'] - $rowobs['pcash'] - $rowobs['padj'];
            }
            elseif($rowobs['subheadid'] == 4){
                $MSob = $rowobs['rcash'] + $rowobs['radj'] - $rowobs['pcash'] - $rowobs['padj'];
            }
            elseif($rowobs['subheadid'] == 5){
                $GLob = $rowobs['pcash'] + $rowobs['padj'] - $rowobs['rcash'] - $rowobs['radj'];
            }
        }
    }
?>

<div class="main-content">
    <form method="post" target="_blank">
        <div class="main-content-inner">					
            <div class="page-content">
                <div class="page-header">
                    <h1>
                            Individual Ledger View
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                            <label style="margin-left:10px">Year</label>
                            <select name="year" id="year" style="font-size:11pt;height:30px;width:160px;" required >	
                                <?php
                                    $current = date("Y");
                                    //echo "<option></option>";   
                                    for($y=$current-1,$i=0 ; $i<6; $i++,$y--){
                                        echo "<option>".$y."</option>";
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="memid" value="<?php echo $memid; ?>">
                        </small>                                
                    </h1>
                </div><!-- /.page-header -->
                <div class="row">
                    <div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                        <div>
                            <div id="user-profile-1" class="user-profile row">
                                
                                <div class="col-xs-12 col-sm-12">
                                
                                <div class="space-12"></div>

                                    <div class="profile-user-info profile-user-info-striped">
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Member ID </div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="memid"> <?php echo $memid;?> </span>
                                            </div>
                                            <div class="profile-info-name"> Bank Name </div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['bankname'];?> </span>
                                            </div>
                    
                                        </div>
                                        
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Member Name </div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                            </div>
                                            <div class="profile-info-name">Bank IFSC</div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['bankifsc'];?> </span>
                                            </div>
                                        </div>
                
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Member Group </div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
                                            </div>
                                            <div class="profile-info-name">Bank Account No.</div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['bankaccountno'];?> </span>
                                            </div>
                                        </div>
                
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Mobile No </div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
                                            </div>
                                            <div class="profile-info-name">Bank Address</div>

                                            <div class="profile-info-value">
                                                <span class="editable" id="username"> <?php echo $row1['bankaddress'];?> </span>
                                            </div>
                                        </div>
                                    
                                </div>
                                <div class="space-20"></div>

                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-small">
                                        <h4 class="widget-title blue smaller">
                                            <i class="ace-icon fa fa-rss orange"></i>
                                            Yearly Ledger <span id='ledgeryear'><?php echo ($current-1).'-'.$current; ?><span>
                                        </h4>
                                    </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <div id="profile-feed-1" class="profile-feed">
                                            <div class="profile-activity clearfix">
                                                <div>
                                                    <table style="font-size:x-small" id="simple-table" class="table  table-bordered table-hover">
                                                        <thead>
                                                            <tr>    
                                                                <th rowspan='2' style="text-align:center">Month / Date</th>
                                                                <th colspan='3' style="text-align:center">General Savings</th>
                                                                <th colspan='3' style="text-align:center">Special Savings</th>
                                                                <th colspan='3' style="text-align:center">Marriage Savings</th>
                                                                <th colspan='4' style="text-align:center">Loan</th>
                                                                <th colspan='2' style="text-align:center">Mutual Aid Fund</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Received</th>
                                                                <th>Withdraw</th>
                                                                <th>Balance</th>
                                                                <th>Received</th>
                                                                <th>Withdraw</th>
                                                                <th>Balance</th>
                                                                <th>Received</th>
                                                                <th>Withdraw</th>
                                                                <th>Balance</th>
                                                                <th>Received</th>
                                                                <th>Interest</th>
                                                                <th>Payment</th>
                                                                <th>Balance</th>
                                                                <th>Received</th>
                                                                <th>Payment</th>
                                                            </tr>    
                                                        </thead>                                                                            
                                                        <tbody id='trows'>
                                                        <?php
                                                            while ($row3 = mysqli_fetch_assoc($result3)){
                                                                $GSob = $GSob+$row3['2']-$row3['General Savings'];
                                                                $SSob = $SSob+$row3['3']-$row3['Special Savings'];
                                                                $MSob = $MSob+$row3['4']-$row3['Marriage Savings'];
                                                                $GLob = $GLob+$row3['General Loan']-$row3['5'];
                                                                echo "<tr>";
                                                                echo "<td align='left'>".$row3['month']."</td>";
                                                                echo "<td align='left'>".$row3['2']."</td>";
                                                                echo "<td align='left'>".$row3['General Savings']."</td>";
                                                                echo "<td align='left'>".$GSob."</td>";
                                                                echo "<td align='left'>".$row3['3']."</td>";
                                                                echo "<td align='left'>".$row3['Special Savings']."</td>";
                                                                echo "<td align='left'>".$SSob."</td>";
                                                                echo "<td align='left'>".$row3['4']."</td>";
                                                                echo "<td align='left'>".$row3['Marriage Savings']."</td>";
                                                                echo "<td align='left'>".$MSob."</td>";
                                                                echo "<td align='left'>".$row3['5']."</td>";
                                                                echo "<td align='left'>".$row3['6']."</td>";
                                                                echo "<td align='left'>".$row3['General Loan']."</td>";
                                                                echo "<td align='left'>".$GLob."</td>";
                                                                echo "<td align='left'>".$row3['10']."</td>";
                                                                echo "<td align='left'>".$row3['Mutual Aid Fund']."</td>";
                                                                echo "</tr>";
                                                            }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                    <button type="submit" formaction="pdt_rep_mem_ledger_excel.php" class="btn btn-success">Export to Excel</button>
                                
                                                    <button type="button" class="btn btn-app btn-light btn-xs" onclick="window.print()"><i class="ace-icon fa fa-print bigger-160"></i>Print</button>
                                                    <?php 
                                                        if($back == 1){
                                                            echo '<a href="pdt_reports_ind_led.php"><button type="button" id="back" class="btn btn-primary" style="float:right;">
                                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                                </button></a>';
                                                        }
                                                        else{
                                                            echo '<a href="pdt_mem_det.php?memid='.$memid.'"><button type="button" id="back" class="btn btn-primary" style="float:right;">
                                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                                </button></a>';
                                                        }
                                                    ?>
                                                </div>
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
            </div><!-- /.page-content -->
        </div>
        </form>
    </div><!-- /.main-content -->
<?php 
	include("footer.php");    
?>

<script type="text/javascript">
    $(document).ready(function()
	{ 	
		$('#year').change(function() 
		{   
			$('#gif').css('visibility','visible');
            
            var year = $("#year").val();
            var nextyear = Number(year)+1;
            var memid = $("#memid").text();
            $('#ledgeryear').text(year+'-'+nextyear);       
                $.ajax({  
                    type: "POST",  
                    url: "pdt_rep_mem_ledger_view.php",  
                    data: {year: year, memid: memid}, 
                    datatype: "html",
                    success: function(response){
                        //alert(response);
                        $('#gif').css('visibility','hidden');
                        $("#trows").html(response);
                    }	 
                });
        });
	    return false;
	});
</script>


