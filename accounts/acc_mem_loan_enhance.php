<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
    if(isset($_GET['memid'])){
        $memid = $_GET['memid'];
        $loanno = $_GET['loanno'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);  

        $bank = mysqli_query($connection, "SELECT * FROM acc_subhead WHERE SubHeadModule = 7");
        $bankcount = mysqli_num_rows($bank);
  
        
  
        $subid = mysqli_query($connection,"SELECT SubHead, SubID FROM acc_loans, acc_subhead 
                                        WHERE loanno = '$loanno' AND acc_loans.subheadid = acc_subhead.SubID");
        $subid = mysqli_fetch_assoc($subid);
        $subheadid = $subid['SubID'];
        $subhead = $subid['SubHead'];

        $loanbal = mysqli_query($connection,"SELECT sum(receiptcash) + sum(receiptadj) as receipt, sum(paymentcash) + sum(paymentadj) as payment, subheadid, accno FROM acc_cashbook, acc_transactions WHERE subheadid= '$subheadid' AND accno = '$loanno' AND memid = '$memid' AND acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransStatus = 1 GROUP BY accno");
        $loanbal = mysqli_fetch_assoc($loanbal);
        $loanbal = $loanbal['payment'] - $loanbal['receipt']; 
    
        $cashfirstob = 0;
        $cashtrpayment = 0;
        $cashexppayment = 0;
        $dummycash = mysqli_query($connection,"SELECT sum(paymentcash) FROM acc_cash_dummy_transfer WHERE clusterid = '$clusterid' AND status = 0 GROUP BY clusterid");
        $dummycashcount = mysqli_num_rows($dummycash);
        if($dummycashcount > 0){
            $cashtr = mysqli_fetch_assoc($dummycash);
            $cashtrpayment = $cashtr['sum(paymentcash)'];
        }
        $dummyexp = mysqli_query($connection,"SELECT sum(paymentcash) FROM acc_cash_dummy_expenses WHERE clusterid = '$clusterid' AND status = 0 GROUP BY clusterid");
        $dummycount = mysqli_num_rows($dummyexp);
        if($dummycount > 0){
            $cashexp = mysqli_fetch_assoc($dummyexp);
            $cashexppayment = $cashexp['sum(paymentcash)'];
        }
        $cashtrpayment = $cashtrpayment + $cashexppayment;

        $sql3 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid =  '$clusterid' AND date <= '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
		$cou3 = mysqli_num_rows($sql3);
		if ($cou3 >0){
			$cash = mysqli_fetch_assoc($sql3);
			$cashreceipt = $cash['sum(receiptcash)'];
			$cashpayment = $cash['sum(paymentcash)'];
			$cashob = $cashfirstob + $cashreceipt - $cashpayment - $cashtrpayment;
		}
		else{
			$cashob = $cashfirstob;
		}

        $others = mysqli_query($connection,"SELECT acc_loan_income.*, SubHead FROM acc_loan_income, acc_subhead
                                    WHERE acc_subhead.SubHeadModule = 11 AND acc_subhead.SubID = acc_loan_income.SubID AND MemID ='$memid'
                                    AND acc_loan_income.status = 0");
        $otherscount = mysqli_num_rows($others);
        $otherscount = 1;


    }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Enhancement of Loan 
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
														Loan Related Charges - <span style="color :red;"><?php if($otherscount == 0) echo "No Documentation Charges Collected from Member"; ?></span>
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
																				<th class="detail-col">S.No.</th>
																				<th class="center">Head</th>
                                                                                <th class="center">Amount</th>													                                                                                				
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            if($otherscount > 0){
                                                                                $slno = 1;                                        
                                                                                while($rowother = mysqli_fetch_assoc($others)){
                                                                                    echo "<tr><td class='center'>".$slno."</td>"; 
                                                                                    echo "<td class='center'>".$rowother['SubHead']."</td>";																				
                                                                                    echo "<td class='center'>".$rowother['Amount']."</td></tr>";
                                                                                    
                                                                                    $slno = $slno + 1;
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


											<div class="space-20"></div>

											<form class="form-horizontal" role="form" method="post" action="acc_mem_loan_enhance_suc.php">
                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Type of Loan </label>
                                                    <div class="col-sm-7">
                                                    <input type="text" id="loantype" name="subheadid" value="<?php echo $subhead; ?>" class="col-xs-10 col-sm-5" autocomplete="off" readonly />
                                                    <input type="hidden"  name="memid"   class="col-xs-10 col-sm-5" value="<?php echo $memid;?>" />                              
                                                    <input type="hidden"  name="loanno"   class="col-xs-10 col-sm-5" value="<?php echo $loanno;?>" />
                                                    <input type="hidden"  name="subheadid"   class="col-xs-10 col-sm-5" value="<?php echo $subheadid;?>" />                                                                                  
                                                </div>
                                                </div>

                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Loan Balance </label>
                                                    <div class="col-sm-7">
                                                    <input type="text" id="loanbal" name="loanbal" value="<?php echo $loanbal; ?>" class="col-xs-10 col-sm-5" autocomplete="off" readonly />
                                                </div>
                                                </div>

                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Payment Type </label>

                                                <div class="col-sm-5">
                                                    <select name="ptype" id="ptype" class = "col-xs-10 col-sm-5" autocomplete="off" required>
                                                        <option value="0"></option>
                                                        <option value="1">Cash</option>
                                                        <option value="2">Bank</option>
                                                    </select>
                                                </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Issue </label>

                                                <div class="col-sm-5">
                                                    <input type="date" id="form-field-3" name="doi" placeholder="mm/dd/yyyy"  class="col-xs-10 col-sm-5" autocomplete="off" required />
                                                </div>
                                                </div>
                                                
                                                <div class="form-group" id="cash">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cash Balance </label>

                                                <div class="col-sm-7">
                                                    <input type="text" id="cashbalance" name="cashbalance" value="<?php echo $cashob; ?>" class="col-xs-10 col-sm-5" autocomplete="off" readonly />
                                                    <input type="hidden" id="count" name="count" value="<?php echo $otherscount; ?>" class="col-xs-10 col-sm-5" autocomplete="off"/>
                                                    <span style="color:red">Amount in Cash Transfer & Payments  <?php echo $cashtrpayment; ?> </span>
                                                </div>
                                                </div>

                                                <div class="form-group" id="bank">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank Account </label>

                                                <div class="col-sm-7">
                                                    <select name="banksubid" id="banksubid">
                                                        <option value="0"></option>
                                                        <?php
                                                            if($bankcount){
                                                                while($bank1 = mysqli_fetch_assoc($bank)){
                                                                    echo "<option value=".$bank1['SubID'].">".$bank1['SubHead']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>                                            
                                                </div>
                                                </div>

                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Additional Loan Required Amount </label>

                                                <div class="col-sm-7">
                                                    <input type="text" id="adlloan" name="adlloanloan" placeholder="Loan Amount" class="col-xs-10 col-sm-5" autocomplete="off" required />
                                                </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Total Loan Amount </label>

                                                <div class="col-sm-7">
                                                    <input type="text" id="loan" name="loan" placeholder="Total Loan Amount" class="col-xs-10 col-sm-5" autocomplete="off" readonly />
                                                </div>
                                                </div>

                                                <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Loan Installment </label>

                                                <div class="col-sm-7">
                                                    <input type="text" id="loanins" name="loanins" placeholder="Loan Installment" class="col-xs-10 col-sm-5" autocomplete="off" required />
                                                </div>
                                                </div>

                                                <div class="clearfix form-group">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button id="submit" class="btn btn-success" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    Submit
                                                    </button>
                                                    <a href="acc_mem_det.php?memid=<?php echo $memid; ?>"><button class="btn btn-info" type="button">
                                                    <i class="ace-icon fa fa-arrow-left bigger-110"></i>
                                                    Back
                                                    </button></a>											
                                                </div>
                                                </div>
                                            </form>
                      
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
  $(document).ready(function(){
    $('#bank').hide();
    $('#cash').hide();
    $('#ptype').change(function(){
        var rectype = $("#ptype").val();    
        if(rectype == 2){
            $('#bank').show();
            $('#cash').hide();
        }
        else if(rectype == 1){
            $('#bank').hide();
            $('#cash').show();
        }
        else{
            $('#bank').hide();
            $('#cash').hide();
        }
    });    

    $('#submit').prop('disabled', true);
    
    $("#adlloan").keyup(function(){
        var balance = $('#cashbalance').val();
        var adlloan = $('#adlloan').val();   
        var count =$('#count').val();        
        var ptype = $('#ptype').val();
        var loanbal = $('#loanbal').val();
        adlloan = parseInt(adlloan);        
        balance = parseInt(balance);        
        loanbal = parseInt(loanbal);
        var totloan = adlloan + loanbal;
        $('#loan').val(totloan);                
        if(ptype == 1){
            if(adlloan > balance){
            alert("Loan amount is greater than available cash balance.")
            $('#loan').val("");
            $('#adlloan').val("");
            $('#submit').prop('disabled', true);          
            }
            else if(loan == 0){
            $('#submit').prop('disabled', true);
            }
            else if(count == 0){
                $('#submit').prop('disabled', true);
            }
            else{
                $('#submit').prop('disabled', false);          
            }
        } 
        else{
            $('#submit').prop('disabled', false);          
        }   
                     
     });
     $("#loanins").keyup(function(){
        var loan = $('#loan').val();  
        var loanins = $('#loanins').val(); 
        loanins = parseInt(loanins);
        if(loan>0 && loanins>0){
            $('#submit').prop('disabled', false); 
        }
        else{
            $('#submit').prop('disabled', true); 
        } 
     });
  });
</script>