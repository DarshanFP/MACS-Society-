<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_loans";
    include("accountssidepan.php");
    
    if(isset($_GET['clustpropid'])){
 
        $clustpropid = $_GET['clustpropid'];

         $loanproposalsabs = mysqli_query($connection,"SELECT A.*, B.status FROM 
                                        (SELECT ClusterPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy WHERE ClusterID = '$clusterid' GROUP BY ClusterPropID) as A, 
                                        (SELECT ClusterPropID, status FROM clusterloanprop WHERE status != 4) as B 
                                        WHERE A.ClusterPropID = B.ClusterPropID AND A.ClusterPropID = '$clustpropid'");
        $loanpropabscount = mysqli_num_rows($loanproposalsabs);

        $loanproposal = mysqli_query($connection,"SELECT acc_loan_dummy.*, groups.GroupName, members.memname, acc_subhead.SubHead
                                            FROM acc_loan_dummy, groups, members, acc_subhead  
                                            WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                        AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID 
                                        AND ClusterPropID = '$clustpropid' AND acc_loan_dummy.status IN (1,2,3,4)");
    
        $count4 = mysqli_num_rows($loanproposal);	


        $disbursed = mysqli_query($connection,"SELECT acc_loan_dummy.*, groups.GroupName, members.memname, acc_subhead.SubHead
                                            FROM acc_loan_dummy, groups, members, acc_subhead  
                                            WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                        AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID 
                                        AND ClusterPropID = '$clustpropid' AND acc_loan_dummy.status = 6 ");
    
        $disbursedcount = mysqli_num_rows($disbursed);	

        $notdisbursed = mysqli_query($connection,"SELECT acc_loan_dummy.*, groups.GroupName, members.memname, acc_subhead.SubHead
                                            FROM acc_loan_dummy, groups, members, acc_subhead  
                                            WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                        AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID 
                                        AND ClusterPropID = '$clustpropid' AND acc_loan_dummy.status = 5 ");
    
        $notdisbursedcount = mysqli_num_rows($notdisbursed);	

        $accepted = mysqli_query($connection,"SELECT count(status) AS acceptcount FROM acc_loan_dummy WHERE ClusterPropID = '$clustpropid' AND status = 4");
        $accepted = mysqli_fetch_assoc($accepted);

        $propstatus = mysqli_query($connection, "SELECT status FROM clusterloanprop WHERE ClusterPropID = '$clustpropid'" );
        $loangencount = 0;
        $pendingstatus = 0;

    }    
    
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Proposal ID : <?php echo $clustpropid; ?> Status 
								<small><b>
									<i class="ace-icon fa fa-angle-double-right"></i>
                                    <?php 

                                        $propstatus = mysqli_fetch_assoc($propstatus);
                                        $cpstatus = $propstatus['status'];
                                        if($cpstatus == 1){
                                            echo "Proposals Submitted";
                                        }
                                        else if($cpstatus ==2){
                                            echo "Proposals Accepted";                                            
                                        }
                                        else if($cpstatus == 3){
                                            echo "Proposed Amount Sent to Bank";
                                        }
                                        else if($cpstatus == 4){
                                            echo "Proposal Closed";
                                        }
                                    
                                    ?>
								</b></small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
                            <div class="col-md-10 ">
                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-small">
                                        <h4 class="widget-title blue smaller">
                                            <i class="ace-icon fa fa-bars orange"></i>
                                            Abstract                                             
                                        </h4>													
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <div id="profile-feed-1" class="profile-feed">
                                                <div class="profile-activity clearfix">
                                                    <div>
                                    
                                                        <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align: center;">No of Members</th>
                                                                    <th  style="text-align: center;">Total Proposed Loan</th>
                                                                    <th  style="text-align: center;">Rejected</th>
                                                                    <th  style="text-align: center;">Amount</th>
                                                                    <th  style="text-align: center;">Accepted</th>
                                                                    <th  style="text-align: center;">Amount</th>
                                                                    <th  style="text-align: center;">Disbursed</th>
                                                                    <th  style="text-align: center;">Amount</th>
                                                                    <th  style="text-align: center;">Not Disbursed</th>
                                                                    <th  style="text-align: center;">Amount</th>                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    $slno = 1;
                                                                    if($loanpropabscount > 0){
                                                                        while($loanproprow = mysqli_fetch_assoc($loanproposalsabs)){                                                                                                                                
                                                                            echo "<tr>";                                                                    
                                                                            echo "<td align='center'>".$loanproprow['totloanees']."</td>";
                                                                            echo "<td align='right'>".$loanproprow['totloan']."</td>"; 
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                   
                                                                            echo "<td></td>";                                                                                                                                                                                                                                                                                                                       
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
                                    </div>
                                </div>
                            </div>


							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							    												
								<div class="row">
                                    <h4 class="widget-title blue smaller">Proposed / Rejected Loans</h4>
                                    <div class="col-xs-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th style="text-align:center">Sl.No</th>
                                                <th style="text-align:left">Group Name</th>										
                                                <th style="text-align:center">Member Name</th>
                                                <th style="text-align:center">Loan Type</th>
                                                <th style="text-align:center">Loan Balance</th>
                                                <th style="text-align:center">Proposed Loan</th>
                                                <th style="text-align:center">Total Loan</th>  
                                                <th style="text-align:center">Status</th>  
                                                <th style="text-align:center">Disbursed</th>  
                                  
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                if($count4>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($loanproposal)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td align='center'>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";
                                                    $loantot = $row4['loanbalance'] + $row4['proposedloan'];
                                                    echo "<td align='right'>".$loantot."</td>";                                                    
                                                    $status = $row4['status'];
                                                    if($status == 1)
                                                        echo "<td>Proposal Submitted</td>";                                                    
                                                    else if($status == 2)
                                                        echo "<td>Proposal Initiated</td>";                                                    
                                                    else if($status == 3)
                                                        echo "<td>Loan Rejected</td>";                                                    
                                                    else if($status == 4)
                                                        echo "<td>Loan Accepted</td>";                                                                                                        
                                                    if($status == 4 && $cpstatus == 3){
                                                        echo "<td aling='center'>
											                <a href='acc_loan_prop_dis.php?id=".$row4['id']."&clustpropid=".$clustpropid."'><span class='label label-success arrowed'>Yes</span></a>
											                <a href='acc_loan_prop_notdis.php?id=".$row4['id']."&clustpropid=".$clustpropid."'><span class='label label-danger arrowed-right'>No</span></a></td>";
                                                            $pendingstatus  = $pendingstatus + 1;
                                                    }
                                                    else{
                                                        echo "<td></td>";    
                                                    }                                                    
                                                    echo "</tr>";
                                                    $slno = $slno + 1;
                                                    $total = $total + $row4['proposedloan'];
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                <td></td>
                                                <td style="text-align:center">Total Amount</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align='right'><?php echo number_format($total,2); ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                </tr>	
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="widget-title blue smaller">Disbursed Loans</h4>
                                    <div class="col-xs-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th style="text-align:center">Sl.No</th>
                                                <th style="text-align:left">Group Name</th>										
                                                <th style="text-align:center">Member Name</th>
                                                <th style="text-align:center">Loan Type</th>
                                                <th style="text-align:center">Loan Balance</th>
                                                <th style="text-align:center">Proposed Loan</th>
                                                <th style="text-align:center">Total Loan</th>  
                                                <th style="text-align:center">Like to Change</th>                                                  
                                                <th style="text-align:center">New Loan No</th>                                                  
                                  
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                if($disbursedcount>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($disbursed)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td align='center'>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";
                                                    $loantot = $row4['loanbalance'] + $row4['proposedloan'];
                                                    echo "<td align='right'>".$loantot."</td>";  
                                                    if(is_null($row4['newloanno'])){
                                                        echo "<td aling='center'>
                                                            <a href='acc_loan_prop_notdis.php?id=".$row4['id']."&clustpropid=".$clustpropid."'><span class='label label-danger arrowed-right'>Change</span></a>
                                                            </td>";              
                                                        echo "<td aling='center'>
                                                            <a href='acc_loan_prop_loanno.php?id=".$row4['id']."'><button class'btn-primary'>Generate</button></a>
                                                            </td>";              
                                                    }   
                                                    else{
                                                        echo "<td></td>";
                                                        echo "<td>".$row4['newloanno']." - ".$row4['TransID']."</td>";
                                                        $loangencount = $loangencount + 1;
                                                    }                                                                                                                                                      
                                                                                                                                            

                                                    echo "</tr>";
                                                    $slno = $slno + 1;
                                                    $total = $total + $row4['proposedloan'];
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                <td></td>
                                                <td style="text-align:center">Total Amount</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align='right'><?php echo number_format($total,2); ?></td>
                                                <td></td> 
                                                <td></td>                                                
                                                <td></td>
                                                </tr>	
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="widget-title blue smaller">Not Disbursed Loans</h4>
                                    <div class="col-xs-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th style="text-align:center">Sl.No</th>
                                                <th style="text-align:left">Group Name</th>										
                                                <th style="text-align:center">Member Name</th>
                                                <th style="text-align:center">Loan Type</th>
                                                <th style="text-align:center">Loan Balance</th>
                                                <th style="text-align:center">Proposed Loan</th>
                                                <th style="text-align:center">Total Loan</th>  
                                                <th style="text-align:center">Like to Change</th>                                                  
                                  
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                if($notdisbursedcount>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($notdisbursed)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td align='center'>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";
                                                    $loantot = $row4['loanbalance'] + $row4['proposedloan'];
                                                    echo "<td align='right'>".$loantot."</td>";                                                    
                                                    echo "<td aling='center'>
											                <a href='acc_loan_prop_dis.php?id=".$row4['id']."&clustpropid=".$clustpropid."'><span class='label label-success arrowed'>Change</span></a>
											            </td>";                                                                                                        
                                                    echo "</tr>";
                                                    $slno = $slno + 1;
                                                    $total = $total + $row4['proposedloan'];
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                <td></td>
                                                <td style="text-align:center">Total Amount</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align='right'><?php echo number_format($total,2); ?></td>
                                                <td></td>                                                
                                                <td></td>
                                                </tr>	
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="col-xs-12">
                                        <div class="content table-responsive table-full-width" style="float:left;">
                                            <a href='accounts_loans.php'><button type='button' class='btn btn-primary btn-fill'>
                                            <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back</button></a>
                                        </div>
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <?php 
                                                
                                                if($disbursedcount == $loangencount && $disbursedcount >= 0 && $pendingstatus == 0){
                                                    if($cpstatus !=4){
                                                         echo "<a href='acc_loan_prop_close.php?clustpropid=".$clustpropid."'><button type='button' class='btn btn-info btn-fill'>Close Proposal</button></a>";   
                                                    }                                                    
                                                }
                                                else{
                                                    echo "<button type='button' class='btn btn-primary btn-fill' disabled >Close Proposal</button>";
                                                }
                                            ?>
                                            
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
		$(document).ready(function()
		{ 	
			$('#group').change(function() 
			{   
                $("#member").text('');
                $("#balance").val(0);                
                $("#loanbalance").val(0);
                var group = $("#group").val();
                $.ajax({  
					type: "POST",  
					url: "memberselect.php",  
					data: "group="+ group, 
					success: function(response){
                        //alert(response);
                        $("#member").html(response);								
					} 
				}); 
				return false;
            });
            $('#member').change(function() 
			{   
                $("#balance").val(0);                
                $("#loanbalance").val(0);
                var memid = $("#member").val();
                
                $.ajax({  
					type: "POST",  
					url: "amountselect.php",  
					data: "memid="+ memid, 
					success: function(response){
                        //alert(response);
                        if(response==''){
                            $("#balance").val(0);
                        }
                        else{
                            $("#balance").val(response);						    
                        }
                        
					} 
				}); 
				return false;
            });
            $('#loantype').change(function() 
			{   
                $("#loanbalance").text('');
                var loantype = $("#loantype").val();
                var memid = $("#member").val();
                $.ajax({  
					type: "POST",  
					url: "loanbalanceselect.php",  
					data: {loantype:loantype,memid:memid}, 
					success: function(response){
                        //alert(response);
                        $("#loanbalance").val(response);
                        if(response==''){
                            $("#loanbalance").val(0);
                        }								
					} 
				}); 
				return false;
            });
            $('#proposedloan').keyup(function() 
			{   
                var loanbalance = parseInt($("#loanbalance").val());
                var proposedloan = parseInt($("#proposedloan").val());
                var totalloan = loanbalance + proposedloan;
                $("#totalloan").val(totalloan);	
               	return false;
			});					
		});
</script>					