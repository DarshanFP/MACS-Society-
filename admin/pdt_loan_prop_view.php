<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");	
    if(isset($_GET['macspropid'])){
        $macspropid = $_GET['macspropid'];

        $loanproposal = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 2 
                                            AND acc_loan_dummy.ClusterID = cluster.ClusterID AND acc_loan_dummy.MacsPropID = '$macspropid' ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
        
        $count4 = mysqli_num_rows($loanproposal);	

        $acceptedloans = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 4 
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
                                            WHERE clusterloanprop.ClusterID = cluster.ClusterID AND clusterloanprop.status = 2 AND clusterloanprop.MacsPropID='$macspropid') as B 
                                            WHERE A.ClusterPropID = B.ClusterPropID");
        $loanproposalcount = mysqli_num_rows($loanproabstract);

        $bankaccount = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule = 7");
        $bankcount = mysqli_num_rows($bankaccount);

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
                                        <h3> Loan Proposal Details  </h3>
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
                                                    <th class="center">Accept / Reject</th>													
													
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
                                                    echo "<td>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['deposit']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";                                                    
                                                    echo "<td aling='center'>
											                <a href='pdt_loan_prop_accpt.php?id=".$row4['id']."&macspropid=".$macspropid."'><span class='label label-success arrowed'>Accept</span></a>
											                <a href='pdt_loan_prop_reject.php?id=".$row4['id']."&macspropid=".$macspropid."'><span class='label label-danger arrowed-right'>Reject</span></a></td>";
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
                                                    <th class="center">Like to Change</th>													
													
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
                                                    echo "<td aling='center'>											                
											                <a href='pdt_loan_prop_reject.php?id=".$row4['id']."&macspropid=".$macspropid."'><span class='label label-danger arrowed-right'>Change</span></a></td>";
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
                                                    <th class="center">Like to Change</th>													
													
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
                                                    echo "<td aling='center'>											                
											                <a href='pdt_loan_prop_accpt.php?id=".$row4['id']."&macspropid=".$macspropid."'><span class='label label-success arrowed'>Change</span></a></td>";
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
                                    <form class="form-horizontal" role="form" method="post" action="pdt_loan_prop_bank.php">
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Select Bank Account</label>

										<div class="col-sm-9">
											<select name="banksubid" id="form-field-2" required>
                                                <option></option>
                                                <?php 
												    if($bankcount > 0){
                                                        while($bank = mysqli_fetch_assoc($bankaccount)){
                                                            echo "<option value=".$bank['SubID'].">".$bank['SubHead']."</option>";
                                                        }
                                                    }
                                                ?>
											</select>	
                                            <input type="hidden" name ="macspropid"	value="<?php echo $macspropid; ?>">
										</div>
                                    </div>



                                    <div class="col-xs-12">
                                        <div class="content table-responsive table-full-width" style="float:left;">
                                            <a href='president_loans.php'><button type='button' class='btn btn-info btn-fill'>Back</button></a>
                                        </div>
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <button type='submit' class='btn btn-info btn-fill' <?php if($count4>0) echo "disabled"; ?>>Submit Proposal to Bank</button>
                                        </div>
                                    </div>
                                    </form>
								</div>
								
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>

