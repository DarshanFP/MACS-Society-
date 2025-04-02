<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");	
    

    $macsprop = mysqli_query($connection, "SELECT * FROM macsloanprop");
    $macspropcount = mysqli_num_rows($macsprop);
    $macspropcount = 1001 + $macspropcount;	
    $macspropid = "MP".$macspropcount;        

    mysqli_query($connection, "START TRANSACTION");

    
    $macspropinsert = mysqli_query($connection,"INSERT INTO macsloanprop (`MacsPropID`,`status`) 
                            VALUES ('$macspropid',2)");
    $lonapropupdate = mysqli_query($connection,"UPDATE acc_loan_dummy SET status = 2, MacsPropID = '$macspropid' 
                                    WHERE status = 1");    
    $clustpropupdate = mysqli_query($connection,"UPDATE clusterloanprop SET status = 2, MacsPropID = '$macspropid' 
                                    WHERE status = 1");    

    if($macspropinsert && $lonapropupdate && $clustpropupdate){
        mysqli_query($connection, "COMMIT");
    }
    else{
        mysqli_query($connection, "ROLL BACK");
    }

    
    $loanproposal = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 2 
                                            AND acc_loan_dummy.ClusterID = cluster.ClusterID AND acc_loan_dummy.MacsPropID = '$macspropid' ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
    
    $count4 = mysqli_num_rows($loanproposal);	

    $loanproabstract = mysqli_query($connection,"SELECT A.*, B.status, B.ClusterName FROM 
                                            (SELECT ClusterPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy GROUP BY ClusterPropID) as A, 
                                            (SELECT ClusterName, ClusterPropID, clusterloanprop.status FROM clusterloanprop, cluster 
                                            WHERE clusterloanprop.ClusterID = cluster.ClusterID AND clusterloanprop.status = 2 AND clusterloanprop.MacsPropID='$macspropid') as B 
                                            WHERE A.ClusterPropID = B.ClusterPropID");
    $loanproposalcount = mysqli_num_rows($loanproabstract);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Successfully Initiated with - Proposal ID : <?php echo $macspropid; ?> 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->													
								<div class="row">
                                    <div class="col-xs-8">
                                        <h3> Loan Proposal Abstract </h3>
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
                                                    echo "<td align='right'>".$total."</td>";
                                                    echo "</tr>";
                                                }
                                               ?> 

											<tbody>											
												
											</tbody>
										</table>
                                    </div>
                                    
                                    <div class="col-xs-12">
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <a href='president.php'><button type='button' class='btn btn-info btn-fill'>Back</button></a>;
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

