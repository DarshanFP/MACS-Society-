<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
    include("accountssidepan.php");
    $user = $_SESSION['login_user']; 
    
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $memid = $_POST['memid'];        
        $flag = $_POST['flag'];        
        $ptype = $_POST['ptype'];
        $banksubid = $_POST['banksubid'];        
        $cheque = $_POST['cheque'];
        $amountreceived = $_POST['amountreceived'];        
        $amountpaid = $_POST['amountpaid'];        
        $totdep = $_POST['totdep'];        
        $totloan = $_POST['totloan'];        
        $sql3 = mysqli_query($connection,"SELECT GroupID, ClusterID FROM groups, members WHERE memid = '$memid' AND memgroupid = GroupID");
        $row3 = mysqli_fetch_assoc($sql3);
        $groupid = $row3['GroupID'];
        $clusterid = $row3['ClusterID'];         

        $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
        $transcount = mysqli_num_rows($trans);
        $transcount = 1001 + $transcount;	
        $transid = "T".$macsshortname.$transcount;

        $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`) VALUES ('$transid',1)");

        if($flag == 1){
            $cashreceipt = mysqli_query($connection,"UPDATE mem_settle SET Details = 'Final Settle', Status = 1, CurDate = '$today', ClusterID = '$clusterid', GroupID = '$groupid', EntryEmpID = '$user', TransID = '$transid' WHERE MemID ='$memid' AND Status = 0");
            $depositupdate = mysqli_query($connection,"UPDATE acc_deposits SET cb = 0, status = 0 WHERE memid = '$memid' AND status = 1 ");
            $scupdate = mysqli_query($connection,"UPDATE acc_sharecapital SET balance = 0, status = 0 WHERE memid = '$memid' AND status = 1 ");
            $loanupdate = mysqli_query($connection,"UPDATE acc_loans SET cb = 0, status = 0 WHERE memid = '$memid' AND status = 1 ");            
            $othincomeupdate = mysqli_query($connection,"UPDATE acc_loan_income SET remarks = 'repaid', status = 2 WHERE memid = '$memid' AND status = 0 ");

            $depositinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, SubHeadID, AccNo, Details, Payment, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND Payment IS NOT NULL");
            $depositintinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, 9, AccNo, Details, PaymentInt, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND PaymentInt IS NOT NULL");

            $loanssettle = mysqli_query($connection,"SELECT * FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND Receipt IS NOT NULL");
            $loansettlecount = mysqli_num_rows($loanssettle);
            if($loansettlecount > 0){
                while($row = mysqli_fetch_assoc($loanssettle)){
                    $receipt = $row['Receipt'];
                    $recint = $row['ReceiptInt'];
                    $today = $row['CurDate']; 
                    $clusterid = $row['ClusterID']; 
                    $groupid = $row['GroupID']; 
                    $transid = $row['TransID']; 
                    $memid = $row['MemID']; 
                    $subid = $row['SubHeadID']; 
                    $accno = $row['AccNo']; 
                    $details = $row['Details']; 
                    $empid = $row['EntryEmpID']; 
                    if($totdep>=$receipt){
                        $loaninsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                                VALUES ('$today','$clusterid','$groupid','$transid','$memid','$subid','$accno','$details','$receipt','$empid')");
                        $totdep = $totdep - $receipt;                                                
                    }
                    else{
                        if($totdep!=0)
                            $loaninsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                                VALUES ('$today','$clusterid','$groupid','$transid','$memid','$subid','$accno','$details','$totdep','$empid')");
                        $receipt = $receipt - $totdep;
                        $loaninsertcash = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptcash`,`entryempid`)
                                                VALUES ('$today','$clusterid','$groupid','$transid','$memid','$subid','$accno','$details','$receipt','$empid')");
                        $totdep = 0;

                    }
                    if($totdep>=$recint){
                        $loanintinsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                                VALUES ('$today','$clusterid','$groupid','$transid','$memid','6','$accno','$details','$recint','$empid')");
                        $totdep = $totdep - $recint;                                                
                    }
                    else{
                        if($totdep!=0)
                            $loanintinsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                                VALUES ('$today','$clusterid','$groupid','$transid','$memid','6','$accno','$details','$totdep','$empid')");
                        $recint = $recint - $totdep;
                        $loanintinsertcash = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptcash`,`entryempid`)
                                                VALUES ('$today','$clusterid','$groupid','$transid','$memid','6','$accno','$details','$recint','$empid')");
                        $totdep = 0;
                    }
                }
            }
            



        }
        else if($flag == 2){
            $depositupdate = mysqli_query($connection,"UPDATE acc_deposits SET cb = 0, status = 0 WHERE memid = '$memid' AND status = 1 ");
            $scupdate = mysqli_query($connection,"UPDATE acc_sharecapital SET balance = 0, status = 0 WHERE memid = '$memid' AND status = 1 ");
            $loanupdate = mysqli_query($connection,"UPDATE acc_loans SET cb = 0, status = 0 WHERE memid = '$memid' AND status = 1 ");            
            $othincomeupdate = mysqli_query($connection,"UPDATE acc_loan_income SET remarks = 'repaid', status = 2 WHERE memid = '$memid' AND status = 0 ");
            if($ptype == 1){
                $cashpayment = mysqli_query($connection,"UPDATE mem_settle SET Details = 'Amount Paid', PaymentType ='Cash', Status = 1, CurDate = '$today', ClusterID = '$clusterid', GroupID = '$groupid', EntryEmpID = '$user', TransID = '$transid' WHERE MemID ='$memid' AND Status = 0");                
                $loaninsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, SubHeadID, AccNo, Details, Receipt, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND Receipt IS NOT NULL");
                $loanintinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, 6, AccNo, Details, ReceiptInt, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND ReceiptInt IS NOT NULL");
                $depositsettle = mysqli_query($connection,"SELECT * FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND Payment IS NOT NULL");
                $depositsettlecount = mysqli_num_rows($depositsettle);
                if($depositsettlecount > 0){
                    while($row = mysqli_fetch_assoc($depositsettle)){
                        $payment = $row['Payment'];
                        $payint = $row['PaymentInt'];
                        $today = $row['CurDate']; 
                        $clusterid = $row['ClusterID']; 
                        $groupid = $row['GroupID']; 
                        $transid = $row['TransID']; 
                        $memid = $row['MemID']; 
                        $subid = $row['SubHeadID']; 
                        $accno = $row['AccNo']; 
                        $details = $row['Details']; 
                        $empid = $row['EntryEmpID']; 
                        if($totloan>=$payment){
                            $depositinsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                                    VALUES ('$today','$clusterid','$groupid','$transid','$memid','$subid','$accno','$details','$payment','$empid')");
                            $totloan = $totloan - $payment;                                                
                        }
                        else{
                            if($totloan!=0)
                                $depositinsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                                    VALUES ('$today','$clusterid','$groupid','$transid','$memid','$subid','$accno','$details','$totloan','$empid')");
                            $payment = $payment - $totloan;
                            $depositinsertcash = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentcash`,`entryempid`)
                                                    VALUES ('$today','$clusterid','$groupid','$transid','$memid','$subid','$accno','$details','$payment','$empid')");
                            $totloan = 0;
                        }
                        if($totloan>=$payint){
                            $depositintinsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                                    VALUES ('$today','$clusterid','$groupid','$transid','$memid','9','$accno','$details','$payint','$empid')");
                            $totloan = $totloan - $payint;                                                
                        }
                        else{
                            if($totloan!=0)
                                $depositintinsertadj = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                                    VALUES ('$today','$clusterid','$groupid','$transid','$memid','9','$accno','$details','$totloan','$empid')");
                            $payint = $payint - $totloan;
                            $depositintinsertcash = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentcash`,`entryempid`)
                                                    VALUES ('$today','$clusterid','$groupid','$transid','$memid','9','$accno','$details','$payint','$empid')");
                            $totdep = 0;
                        }
                    }
                }
            }                
            else if($ptype == 2){
                $amount = $totdep - $totloan;
                $chequeupdate = mysqli_query($connection,"UPDATE mem_settle SET Details = 'Amount Paid', PaymentType ='Cheque', BankSubID = '$banksubid', ChequeNo = '$cheque', Status = 1, CurDate = '$today', ClusterID = '$clusterid', GroupID = '$groupid', EntryEmpID = '$user', TransID = '$transid' WHERE MemID ='$memid' AND Status = 0");                 
                $loaninsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, SubHeadID, AccNo, Details, Receipt, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND Receipt IS NOT NULL");
                $loanintinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, 6, AccNo, Details, ReceiptInt, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND ReceiptInt IS NOT NULL");
                $bankinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `details`, `receiptadj`,`entryempid`)
                                            VALUES ('$today','$clusterid','$groupid','$transid','$memid','$banksubid','Bank Receipt','$amount','$user')");
                $depositinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, SubHeadID, AccNo, Details, Payment, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND Payment IS NOT NULL");
                $depositintinsert = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`entryempid`)
                                    SELECT CurDate, ClusterID,GroupID, TransID, MemID, 9, AccNo, Details, PaymentInt, EntryEmpID FROM mem_settle WHERE MemID = '$memid' AND Status = 1 AND PaymentInt IS NOT NULL");
            }                
        }
        
        $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
        $result1 = mysqli_query($connection, $sql1) or die(mysqli_error($sql1));
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);
        
        $settle = mysqli_query($connection, "SELECT mem_settle.*, SubHead, SubHeadModule FROM mem_settle, acc_subhead WHERE mem_settle.SubHeadID = acc_subhead.SubID AND MemID = '$memid' AND mem_settle.Status = 1 ");
        $countsettle = mysqli_num_rows($settle);
        

    }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header col-sm-10"" >
							<h1>
								 Final Settlement Successful with Trans ID : <?php echo $transid; ?>
								<small>
								<i class="ace-icon fa fa-angle-double-right"></i>                                
                                
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-10">											

											<div class="space-12"></div>

											<div class="profile-user-info profile-user-info-striped">
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member ID </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $memid;?> </span>
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
														Final Settlement 
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
																				<th rowspan = "2" class="detail-col">S.No.</th>
																				<th rowspan = "2" class="center">Head</th>
                                                                                <th colspan = "3" class="center">Deposits/Others</th>
                                                                                <th colspan = "3" class="center">Loans</th>																																	                                        
																			</tr>
                                                                            <tr>                  
                                                                                <th class="center">Amount</th>
                                                                                <th class="center">Interest</th>	
                                                                                <th class="center">Total</th>	
                                                                                <th class="center">Amount</th>
                                                                                <th class="center">Interest</th>
                                                                                <th class="center">Total</th>		
                                                                            </tr>
																		</thead>

																		<tbody>
																		<?php                                                                             
                                                                                
                                                                                $recsum = 0;
                                                                                $recintsum = 0;
                                                                                $paysum = 0;
                                                                                $payintsum = 0;                                                                                                                  
																				
                                                                                $depsum = $depsum + $row['balance'];
                                                                                if($countsettle>0){
                                                                                    $slno= 1;
                                                                                    $sno = 0;
                                                                                    while($row2 = mysqli_fetch_assoc($settle))
                                                                                    { 	$rectotal = 0;
                                                                                        $paytotal = 0;
                                                                                        echo "<tr><td class='center'>".$slno."</td>"; 
                                                                                        echo "<td id='subhead".$sno."'>".$row2['SubHead']."</td>";																				                                                                                        
                                                                                        echo "<td align='right'>".number_format($row2['Payment'],2,'.','')."</td>";                                                                                        
                                                                                        echo "<td align = 'right'>".number_format($row2['PaymentInt'],2,'.','')."</td>";
                                                                                        $paytotal = $row2['Payment'] + $row2['PaymentInt'];
                                                                                        echo "<td align ='right'>".number_format($paytotal,2,'.','')."</td>";
                                                                                        echo "<td align = 'right'>".number_format($row2['Receipt'],2,'.','')."</td>";                                                                                        
                                                                                        if($row2['SubHeadModule'] == 3){
                                                                                            echo "<td align = 'right'>".number_format($row2['ReceiptInt'],2,'.','')."</td>";
                                                                                        }
                                                                                        else{
                                                                                            echo "<td align = 'right'>".number_format($row2['ReceiptInt'],2,'.','')."</td>";
                                                                                        }
                                                                                        
                                                                                        $rectotal = $row2['Receipt'] + $row2['ReceiptInt'];
                                                                                        echo "<td align ='right'>".number_format($rectotal,2,'.','')."</td></tr>";                                                                                        
                                                                                        $slno = $slno +1;					
                                                                                        $recsum = $recsum + $row2['Receipt'];
                                                                                        $recintsum = $recintsum + $row2['ReceiptInt'];
                                                                                        $paysum = $paysum + $row2['Payment'];
                                                                                        $payintsum = $payintsum + $row2['PaymentInt'];
                                                                                        $sno = $sno + 1;
                                                                                    }				
                                                                                }                                                                                                                                                                echo "<tr><td colspan = '2'>Total</td>";
                                                                                echo "<td align='right'>".number_format($paysum,2,'.','')."</td>";
                                                                                echo "<td align='right'>".number_format($payintsum,2,'.','')."</td>";
                                                                                $totaldep = $paysum + $payintsum;
                                                                                echo "<td align='right'>".number_format($totaldep,2,'.','')."</td>";
                                                                                echo "<td align='right'>".number_format($recsum,2,'.','')."</td>";
                                                                                echo "<td align='right'>".number_format($recintsum,2,'.','')."</td>";
                                                                                $totalloan = $recsum + $recintsum;
                                                                                echo "<td align='right'>".number_format($totalloan,2,'.','')."</td></tr>";
                                                                                $balance = $totalloan - $totaldep;
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														

                                                            
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <?php 
                                                                        if($flag == 1){
                                                                            echo "<span style='color:green;'>Amount Received Rs.".number_format($balance,2,'.','')."</span>";
                                                                        }
                                                                        else{
                                                                            echo "<span style='color:red;'>Amount Paid Rs.".number_format(abs($balance),2,'.','')."</span>";
                                                                        }
                                                                    ?>
                                                                    
                                                                </div>
                                                                <div class="content table-responsive table-full-width" style="float:right;">                                                                
                                                                    <a href='acc_mem_det.php?memid=<?php echo $memid; ?>'><button type='button' class='btn btn-info btn-fill'>
                                                                    <i class="ace-icon fa fa-arrow-left bigger-110"></i>Back</button></a>									
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
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
            <div id="documentmodel" class="modal fade" role="dialog">
               
                         
            </div>

<?php 
	include("footer.php");    
?>			