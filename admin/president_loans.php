<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");	
    
    $initiatedpropabs = mysqli_query($connection,"SELECT A.*, B.status FROM 
                                        (SELECT MacsPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy GROUP BY MacsPropID) as A, 
                                        (SELECT MacsPropID, macsloanprop.status FROM macsloanprop 
                                        WHERE macsloanprop.status IN (2,3)) as B 
                                        WHERE A.MacsPropID = B.MacsPropID");
    $initiatecount = mysqli_num_rows($initiatedpropabs);


    $loanproposal = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, acc_subhead.SubHead
                                            FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                            WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                        AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 1 
                                        AND acc_loan_dummy.ClusterID = cluster.ClusterID ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
    
    $count4 = mysqli_num_rows($loanproposal);	

    $loanproabstract = mysqli_query($connection,"SELECT A.*, B.status, B.ClusterName FROM 
                                        (SELECT ClusterPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy GROUP BY ClusterPropID) as A, 
                                        (SELECT ClusterName, ClusterPropID, clusterloanprop.status FROM clusterloanprop, cluster 
                                        WHERE clusterloanprop.ClusterID = cluster.ClusterID AND clusterloanprop.status = 1) as B 
                                        WHERE A.ClusterPropID = B.ClusterPropID");
    $loanproposalcount = mysqli_num_rows($loanproabstract);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Loan Disbursement Module
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
                                            <i class="ace-icon fa fa-rss orange"></i>
                                            Pending Loan Proposals 
                                            <small><a href='pdt_loan_prop_all.php'> view all Loan Proposals </a></small>
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
                                                                <th style="text-align: center;">MACS Prop ID</th>
                                                                <th style="text-align: center;">No of Members</th>
                                                                <th  style="text-align: center;">Total Proposed Loan</th>
                                                                <th  style="text-align: center;">Status</th>                                                            
                                                            </tr>
                                                            </thead>	
                                                            <tbody>
                                                                <?php 
                                                                    $slno = 1;
                                                                    if($initiatecount > 0){
                                                                        while($loanproprow = mysqli_fetch_assoc($initiatedpropabs)){                                                                                                                                
                                                                            $status = $loanproprow['status'];
                                                                            echo "<tr>";
                                                                            echo "<td>".$slno."</td>";
                                                                            if($status == 2)
                                                                                echo "<td><a href='pdt_loan_prop_view.php?macspropid=".$loanproprow['MacsPropID']."'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                            else if($status == 3)
                                                                                echo "<td><a href='pdt_loan_prop_bank_view.php?macspropid=".$loanproprow['MacsPropID']."'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                            echo "<td align='center'>".$loanproprow['totloanees']."</td>";
                                                                            echo "<td align='right'>".$loanproprow['totloan']."</td>";                                                                                                                                                               
                                                                            if($status == 2)
                                                                                echo "<td>Proposals Initiated</td>";
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



							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div class="row">
                                    <div class="col-xs-8">
                                        <h3> Submitted Loan Proposal Abstract </h3>
										<table  id="simple-table" class="table  table-bordered table-hover">
                                            <thead>
												<tr>													
													<th class="center" width="100">Sl.No.</th>
													<th class="center" width="200">Cluster Name</th>
                                                    <th class="center" width="250">Cluster Prop ID</th>
                                                    <th class="center" width="250">No of Proposed Loans</th>
                                                    <th class="center" width="150">Total</th>
												</tr>
                                            </thead>
                                            <tbody>
                                             <?php
                                                $total = 0;
                                                if($loanproposalcount>0){
                                                    $slno = 1;
                                                    
                                                    while($absrow = mysqli_fetch_assoc($loanproabstract)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$absrow['ClusterName']."</td>";
                                                    echo "<td align ='center'>".$absrow['ClusterPropID']."</td>";
                                                    echo "<td align='center'>".$absrow['totloanees']."</td>";
                                                    echo "<td align='right'>".$absrow['totloan']."</td>";                                                    
                                                    echo "</tr>";
                                                    $total = $total + $absrow['totloan'];
                                                    $slno = $slno + 1;
                                                    }
                                                    echo "<tr>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td align='right'>".$total."</td>";
                                                    echo "</tr>";
                                                }
                                               ?> 																				
												
											</tbody>
                                        </table>
                                    </div>

									<div class="col-xs-12">
                                        <h3> Submitted Loan Proposal Details  </h3>
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="center" width="100">Sl.No.</th>
													<th class="center" width="200">Cluster Name</th>
                                                    <th class="center" width="250">Group Name</th>
                                                    <th class="center" width="250">Member Name</th>
                                                    <th class="center" width="150">Deposit Balance</th>
                                                    <th class="center" width="250">Loan Type</th>
                                                    <th class="center" width="150">Previous Loan</th>
                                                    <th class="center" width="150">Proposed Loan</th>													
													
												</tr>
                                            </thead>
                                            <tbody>
                                             <?php
                                                $total = 0;
                                                if($count4>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($loanproposal)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['ClusterName']."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td align='center'>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['deposit']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";                                                    
                                                    echo "</tr>";
                                                    $slno = $slno + 1;
                                                    $total = $total + $row4['proposedloan'];
                                                    }
                                                    echo "<tr>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td align = 'right'>".$total."</td>";
                                                    echo "</tr>";
                                                }
                                               ?> 																				
												
											</tbody>
										</table>
                                    </div>
                                    
                                    <div class="col-xs-12">
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <a href='pdt_loans_initiate.php'><button type='button' class='btn btn-info btn-fill'>Initiate</button></a>
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

