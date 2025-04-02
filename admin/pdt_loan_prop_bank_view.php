<?php	    
	include("pdt_session.php");
	//$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");	
    if(isset($_GET['macspropid'])){
        $macspropid = $_GET['macspropid'];
        $flag = $_GET['flag'];
        if($flag==1){
           $_SESSION['curpage']="president"; 
        }else{
           $_SESSION['curpage']="president_loans"; 
        }

        $mpstatus = mysqli_query($connection,"SELECT status FROM macsloanprop WHERE MacsPropID = '$macspropid'");
        $mpstatus = mysqli_fetch_assoc($mpstatus);
        $mpstatus = $mpstatus['status'];
       

        $acceptedloans = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status in (4,5,6) 
                                            AND acc_loan_dummy.ClusterID = cluster.ClusterID AND acc_loan_dummy.MacsPropID = '$macspropid' ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
        
        $acceptcount = mysqli_num_rows($acceptedloans);
        
        $rejectedloans = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 3 
                                            AND acc_loan_dummy.ClusterID = cluster.ClusterID AND acc_loan_dummy.MacsPropID = '$macspropid' ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
        
        $rejectcount = mysqli_num_rows($rejectedloans);

        $loanproabstract = mysqli_query($connection,"SELECT A.*, B.status, B.ClusterName FROM 
                                            (SELECT ClusterPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy GROUP BY ClusterPropID) as A, 
                                            (SELECT ClusterName, ClusterPropID, clusterloanprop.status FROM clusterloanprop, cluster 
                                            WHERE clusterloanprop.ClusterID = cluster.ClusterID AND clusterloanprop.status in (3,4)  AND clusterloanprop.MacsPropID='$macspropid') as B 
                                            WHERE A.ClusterPropID = B.ClusterPropID");
        $loanproposalcount = mysqli_num_rows($loanproabstract);

        $propstatuscount = 0;

    }
    
    
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Loan Proposal with MACS Proposal ID : <?php echo $macspropid; ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<?php
                                        if($mpstatus == 4)
                                            echo "Closed";
                                    ?>
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
                                                    <th class="center" width="150">Status</th>
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
                                                    if($absrow['status'] == 4){
                                                        echo "<td align='right'>Closed</td>"; 
                                                        $propstatuscount = $propstatuscount + 1;                                                                                                               
                                                    }
                                                    else{
                                                        echo "<td align='right'></td>";                                                    
                                                    }
                                                    
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
                                        <h3 style="color:green;"> Accepted Loans   </h3>
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="center">Sl.No.</th>
													<th class="center">Cluster Name</th>
                                                    <th class="center">Group Name</th>
                                                    <th class="center">Member Name</th>
                                                    <th class="center">Deposit Balance</th>                                                    
                                                    <th class="center">Loan Type</th>
                                                    <th class="center">Previous Loan</th>
                                                    <th class="center">Proposed Loan</th>
                                                    <th class="center">Disbursed Details</th>													
													
												</tr>
                                            </thead>
                                            <tbody>
                                             <?php
                                                $total = 0;
                                                if($acceptcount>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($acceptedloans)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['ClusterName']."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['deposit']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";                                                    
                                                    if($row4['status'] == 5){
                                                        echo "<td aling='center'>Not Disbursed</td>";
                                                    }                                                        
                                                    else if($row4['status'] == 6){
                                                        if(is_null($row4['newloanno']))
                                                            echo "<td aling='center'>Loan Number to be Generated</td>";
                                                        else
                                                            echo "<td aling='center'>".$row4['newloanno']." - ".$row4['TransID']."</td>";
                                                    }                                                            
                                                    else {
                                                        echo "<td aling='center'>No action yet</td>";
                                                    }                                                        
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
                                        <h3 style="color:red;"> Rejected Loans   </h3>
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="center">Sl.No.</th>
													<th class="center">Cluster Name</th>
                                                    <th class="center">Group Name</th>
                                                    <th class="center">Member Name</th>
                                                    <th class="center">Deposit Balance</th>                                                    
                                                    <th class="center">Loan Type</th>
                                                    <th class="center">Previous Loan</th>
                                                    <th class="center">Proposed Loan</th>
												</tr>
                                            </thead>
                                            <tbody>
                                             <?php
                                                $total = 0;
                                                if($rejectcount>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($rejectedloans)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['ClusterName']."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td>".$row4['memname']."</td>";
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
                                        <div class="col-xs-4">
                                            <div class="content table-responsive table-full-width" style="float:left;">
                                            <?php
                                            if($flag==1){    
                                                echo "<a href='pdt_loan_prop_all.php?flag=1'><button type='button' class='btn btn-info btn-fill'><i class='fa fa-arrow-left' style='margin-right:10px;'></i>Back</button></a>";
                                            }else{
                                                echo "<a href='president_loans.php'><button type='button' class='btn btn-info btn-fill'><i class='fa fa-arrow-left' style='margin-right:10px;'></i>Back</button></a>"; 
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="content table-responsive table-full-width" style="float:center;">
                                                <a href='pdt_loan_prop_bank_excel.php?macspropid=<?php  echo $macspropid; ?>'><button type='button' class='btn btn-info btn-fill'><i class="fa fa-university" style="margin-right:10px;"></i>Bank Statement</button></a>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="content table-responsive table-full-width" style="float:right;">
                                                <?php
                                                    if($loanproposalcount == $propstatuscount && $loanproposalcount > 0){
                                                        if($mpstatus !=4){
                                                            echo "<a href='pdt_loan_prop_close.php?macspropid=".$macspropid."'><button type='button' class='btn btn-info btn-fill'><i class='fa fa-check' style='margin-right:10px;'></i>Close Proposal</button></a>";
                                                        }                                                        
                                                    }
                                                    else{
                                                        echo "<button type='button' class='btn btn-info btn-fill' disabled><i class='fa fa-check' style='margin-right:10px;'></i>Close Proposal</button>";
                                                    }
                                                ?>
                                                
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

