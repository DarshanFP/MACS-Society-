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
                                        (SELECT ClusterPropID, status FROM clusterloanprop) as B 
                                        WHERE A.ClusterPropID = B.ClusterPropID");
    $loanpropabscount = mysqli_num_rows($loanproposalsabs);
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								All Loan Proposal
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">

                            <div class="col-md-8 ">
                                <div class="widget-box transparent">
                                    

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
                                                                            else if($status == 4)
                                                                                echo "<td>Proposals Closed</td>";                                                                                        
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

							
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>						 