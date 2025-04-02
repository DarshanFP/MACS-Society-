<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_loans";
    include("accountssidepan.php");
    $group 	= "Select
				  groups.*,ClusterName
				From
				  groups,cluster 
				Where
				  groups.ClusterID = cluster.ClusterID AND groups.ClusterID = '$clusterid' ORDER BY GroupID";
	$result = mysqli_query($connection,$group);
    $count = mysqli_num_rows($result);


    $loanproposalsabs = mysqli_query($connection,"SELECT A.*, B.status FROM 
                                        (SELECT ClusterPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy WHERE ClusterID = '$clusterid' GROUP BY ClusterPropID) as A, 
                                        (SELECT ClusterPropID, status FROM clusterloanprop WHERE status != 4) as B 
                                        WHERE A.ClusterPropID = B.ClusterPropID");
    $loanpropabscount = mysqli_num_rows($loanproposalsabs);

    
    $loanproposal = mysqli_query($connection,"SELECT acc_loan_dummy.*, groups.GroupName, members.memname, acc_subhead.SubHead
                                            FROM acc_loan_dummy, groups, members, acc_subhead  
                                            WHERE acc_loan_dummy.ClusterID = '$clusterid' and acc_loan_dummy.status = 0  AND acc_loan_dummy.subid = acc_subhead.SubID       
                                        AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID");
    
    $count4 = mysqli_num_rows($loanproposal);
	$loantype = mysqli_query($connection, "SELECT * from acc_subhead WHERE SubHeadModule = 3");
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Loan Proposal
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">

                            <div class="col-md-8 ">
                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-small">
                                        <h4 class="widget-title blue smaller">
                                            <i class="ace-icon fa fa-bars orange"></i>
                                            Pending Loan Proposals 
                                            <small><a href='acc_loans_proposals_all.php'> view all Loan Proposals </a></small>
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
                                                                    <th style="text-align: center;">Sl.No</th>
                                                                    <th style="text-align: center;">Proposal ID</th>
                                                                    <th style="text-align: center;">No of Members</th>
                                                                    <th  style="text-align: center;">Total Proposed Loan</th>
                                                                    <th  style="text-align: center;">Status</th>                                                                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    $slno = 1;
                                                                    if($loanpropabscount > 0){
                                                                        while($loanproprow = mysqli_fetch_assoc($loanproposalsabs)){                                                                                                                                
                                                                            echo "<tr>";
                                                                            echo "<td>".$slno."</td>";
                                                                            echo "<td><a href='acc_loan_prop_view.php?clustpropid=".$loanproprow['ClusterPropID']."'><button class'btn-primary'>".$loanproprow['ClusterPropID']."</button></a></td>";                                                                                    
                                                                            echo "<td align='center'>".$loanproprow['totloanees']."</td>";
                                                                            echo "<td align='right'>".$loanproprow['totloan']."</td>";                                                                                    
                                                                            $status = $loanproprow['status'];
                                                                            if($status == 1)
                                                                                echo "<td>Proposals Submitted</td>";
                                                                            else if($status == 2)
                                                                                echo "<td>Proposal Accepted</td>";
                                                                            else if($status == 3)
                                                                                echo "<td>Proposed Amount Sent to Bank</td>";                                                                                        
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


							<div class="col-xs-10"> <!-- PAGE CONTENT BEGINS -->
							    				
								
								<div class="row">
									<div class="col-xs-12">
                                        <h3 class="widget-title blue smaller">New Proposal</h3>
										<table class="table">	
                                        <thead>                                            
                                            <th>Group Name</th>										
                                            <th>Member Name</th>	
                                            <th>Deposit Balance</th>
                                            <th>Loan Type</th>
                                            <th>Loan Balance</th>
                                            <th>Proposed Loan Amount</th>
                                            <th>Installment</th>                                            
                                            <th></th>
                                        </thead>	
                                        <tbody>
                                            <tr> <form action="acc_loan_prop_add.php" class="form-horizontal" method ="post">                                                
                                                <td>												
                                                    <select class="form-group" id="group" name="group"  style="width:160px; " required >	
                                                    <option value=''> </option>
                                                    <?php 
                                                        while ($row = mysqli_fetch_assoc($result)) 												
                                                        echo "<option value ='".$row['GroupID']."'>".$row['GroupName']."</option>";								
                                                    ?>
                                                    </select>												
                                                </td>
                                                <td id='accdropdown'>                                                    
                                                    <select name="member" id = "member" class = "form-group" style="width:180px;" required>
                                                        
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name ="balance"  id="balance" style="width:100px;" readonly />
                                                    <input type="hidden" name ="clusterid" id="clusterid" value="<?php echo $clusterid;?>" /> </td>
                                                <td><select class="form-group" id="loantype" name="loantype"  style="width:160px; " required >	
                                                    <option value=''> </option>
                                                    <?php 
                                                        while ($row = mysqli_fetch_assoc($loantype)) 												
                                                        echo "<option value ='".$row['SubID']."'>".$row['SubHead']."</option>";								
                                                    ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" name ="loanbalance" id="loanbalance" style="width:100px; " readonly/> </td>
                                                <td><input type="text" name ="proposedloan" id="proposedloan" style="width:100px; " required /> </td>                                                
                                                <td><input type="text" name ="loanins" id="loanins" style="width:100px; " required /> </td>                                                
                                                <td><input type="hidden" name ="clusterid" id="clusterid" value="<?php echo $clusterid;?>" /> </td>
                                                <td><button type="submit" id="but" class="btn btn-primary btn-fill">
                                                <i class="fa fa-check" style="margin-right:10px;"></i>Add</button></td>													
                                                </form>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                    
                                    

                                    <div class="col-xs-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th style="text-align:center">Sl.No</th>
                                                <th style="text-align:left">Group Name</th>										
                                                <th style="text-align:center">Member Name</th>
                                                <th style="text-align:center">Loan Type</th>
                                                <th style="text-align:center">Loan Balance</th>
                                                <th style="text-align:center">Proposed Loan</th>
                                                <th style="text-align:center">Installment</th>
                                                <th style="text-align:center">Total Loan</th>                                                
                                                <th style="text-align:center">Delete</th>                                    	
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
                                                    echo "<td align='right'>".$row4['proposedins']."</td>";
                                                    $loantot = $row4['loanbalance'] + $row4['proposedloan'];
                                                    echo "<td align='right'>".$loantot."</td>";
                                                    
                                                    echo "<td align='center'><a href='acc_loan_prop_del.php?id=".$row4['id']."'><i class='fa fa-close'></i></a></td>";
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
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <?php if($count4 == 0){
                                                                        echo "<button class='btn btn-primary btn-fill' disabled>Proceed</button>";
                                                                    }
                                                                    else {
                                                                        echo "<a href='acc_loan_prop_suc.php?clusterid=".$clusterid."'><button type='submit' class='btn btn-primary btn-fill' id='proceed' onclick='return confirm(&quot Please Check Once, Once submitted cannot be revoked. &quot); return false;'>Submit Proposal</button></a>";                                									
                                                                    }?>
                                            
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