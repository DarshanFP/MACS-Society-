<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");	
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $macspropid = $_POST['macspropid'];
        $bankid = $_POST['banksubid'];

        $acceptedloans = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, members.bankname, members.bankifsc, members.bankaccountno, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 4 
                                            AND acc_loan_dummy.ClusterID = cluster.ClusterID AND acc_loan_dummy.MacsPropID = '$macspropid' ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
        
        $acceptcount = mysqli_num_rows($acceptedloans);      
        
        mysqli_query($connection,"start transaction");
        $clustpropupdate = mysqli_query($connection,"UPDATE clusterloanprop SET status = 3 WHERE status = 2 AND  MacsPropID = '$macspropid'");
        $macspropupdate = mysqli_query($connection,"UPDATE macsloanprop SET status = 3 WHERE status = 2  AND MacsPropID = '$macspropid'");    
        $bankidupdate = mysqli_query($connection,"UPDATE acc_loan_dummy SET banksubid = '$bankid' WHERE MacsPropID = '$macspropid'");    
        if($clustpropupdate && $macspropupdate && $bankidupdate){
            mysqli_query($connection,"commit");
        }
        else{
            mysqli_query($connection,"rollback");
        }

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
							

									<div class="col-xs-12">
                                        <h3 style="color:green;"> Accepted Loans   </h3>
										<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="center">Sl.No.</th>
													<th class="center">Cluster Name</th>
                                                    <th class="center">Group Name</th>
                                                    <th class="center">Member Name</th>
                                                    <th class="center">Bank Name</th>                                                    
                                                    <th class="center">Bank IFSC Code</th>
                                                    <th class="center">Bank Account No.</th>
                                                    <th class="center">Proposed Loan</th>                                                    													
													
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
                                                    echo "<td align='center'>".$row4['bankname']."</td>";
                                                    echo "<td align='center'>".$row4['bankifsc']."</td>";
                                                    echo "<td align='right'>".$row4['bankaccountno']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";                                                    
                                                    echo "</tr>";
                                                    $slno = $slno + 1;
                                                    $total = $total + $row4['proposedloan'];

                                                    }
                                                    echo "<tr>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td>Total</td>";
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
                                        <div class="content table-responsive table-full-width" style="float:left;">
                                            <a href='president_loans.php'><button type='button' class='btn btn-info btn-fill'>Back</button></a>
                                        </div>
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <a href='pdt_loan_prop_bank_excel.php?macspropid=<?php echo $macspropid; ?>'><button type='button' class='btn btn-info btn-fill'>Report to Excel</button></a>
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

