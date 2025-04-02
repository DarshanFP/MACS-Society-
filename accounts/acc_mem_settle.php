<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
    include("accountssidepan.php");
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
        $cashob = $cashfirstob + $cashreceipt - $cashpayment -$cashtrpayment;
    }
    else{
        $cashob = $cashfirstob;
    }


    if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);

        $settledel = mysqli_query($connection,"DELETE FROM mem_settle WHERE MemID = '$memid' AND Status = 0");
    
        $deathreliefsubid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule	 = 12");
        $drfetchsubid = mysqli_fetch_assoc($deathreliefsubid);
        $drsubid = $drfetchsubid['SubID'];


        $today = date("d");
        $scinsert = mysqli_query($connection,"INSERT INTO mem_settle (MemID, SubHeadID, AccNo, Payment) 
                                                SELECT memid,subheadid,memid,balance FROM acc_sharecapital WHERE memid = '$memid'");

        $depinsert = mysqli_query($connection,"INSERT INTO mem_settle (MemID, SubHeadID,AccNo, Payment, PaymentInt)
                                                SELECT acc_deposits.memid, acc_deposits.subheadid,acc_deposits.depositno, acc_deposits.cb, (acc_deposits.cb*roi/100/365*day(curdate())) as interest FROM acc_deposits,  acc_rateofinterest 
                                                WHERE memid = '$memid' AND acc_deposits.status = 1 AND acc_deposits.subheadid = acc_rateofinterest.subheadid AND acc_rateofinterest.status = 1");

        
        $othinsert = mysqli_query($connection, "INSERT INTO mem_settle (MemID, SubHeadID, AccNo, Payment) 
                                            SELECT MemID, SubID, MemID, Amount FROM acc_loan_income WHERE MemID ='$memid'
                                            AND acc_loan_income.status = 0 ");

        $deathrelief = mysqli_query($connection, "INSERT INTO mem_settle (MemID, SubHeadID, AccNo, Payment) VALUES ('$memid','$drsubid','',0)");
        
        $loaninsert = mysqli_query($connection,"INSERT INTO mem_settle (MemID, SubHeadID,AccNo, Receipt)
                                                SELECT memid, subheadid,loanno,cb  FROM acc_loans WHERE  memid='$memid' AND acc_loans.status = 1");
        
        $settle = mysqli_query($connection, "SELECT mem_settle.*, SubHead, SubHeadModule FROM mem_settle, acc_subhead WHERE mem_settle.SubHeadID = acc_subhead.SubID AND MemID = '$memid' AND mem_settle.Status = 0 ");
        $countsettle = mysqli_num_rows($settle);
        
        $bank = mysqli_query($connection, "SELECT * FROM acc_subhead WHERE SubHeadModule = 7");
        $bankcount = mysqli_num_rows($bank);
    
        }
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $interest = $_POST['interest'];
            $mutual = $_POST['mutual'];
            $id = $_POST['id'];
            
            $member = mysqli_query($connection,"SELECT MemID FROM mem_settle WHERE Id = '$id'");
            $member = mysqli_fetch_assoc($member);
            $memid = $member['MemID'];

            $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		    $result1 = mysqli_query($connection, $sql1) or die(mysqli_error($sql1));
            $count1 = mysqli_num_rows($result1);
            $row1 = mysqli_fetch_assoc($result1);
            if(isset($_POST['interest']))
                $intupdate = mysqli_query($connection,"UPDATE mem_settle SET ReceiptInt = '$interest' WHERE Id ='$id'");
            else if(isset($_POST['mutual']))
                $intupdate = mysqli_query($connection,"UPDATE mem_settle SET Payment = '$mutual' WHERE Id ='$id'");

            $settle = mysqli_query($connection, "SELECT mem_settle.*, SubHead, SubHeadModule FROM mem_settle, acc_subhead WHERE mem_settle.SubHeadID = acc_subhead.SubID AND MemID = '$memid' AND mem_settle.Status = 0 ");
            $countsettle = mysqli_num_rows($settle);
        
            $bank = mysqli_query($connection, "SELECT * FROM acc_subhead WHERE SubHeadModule = 7");
            $bankcount = mysqli_num_rows($bank);       

        }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header col-sm-10"" >
							<h1>
								 Final Settlement
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
                                                                                        if($row2['SubHeadModule'] == 12){
                                                                                            echo "<td align = 'right'><button id='mutual".$row2['Id']."' class='primary' data-toggle='modal' data-target='#documentmodelmutual'>".number_format($row2['Payment'],2,'.','')."</button></td>";
                                                                                        }
                                                                                        else{
                                                                                            echo "<td align='right'>".number_format($row2['Payment'],2,'.','')."</td>";
                                                                                        }                                                                                        
                                                                                        echo "<td align = 'right'>".number_format($row2['PaymentInt'],2,'.','')."</td>";
                                                                                        $paytotal = $row2['Payment'] + $row2['PaymentInt'];
                                                                                        echo "<td align ='right'>".number_format($paytotal,2,'.','')."</td>";
                                                                                        echo "<td align = 'right'>".number_format($row2['Receipt'],2,'.','')."</td>";                                                                                        
                                                                                        if($row2['SubHeadModule'] == 3){
                                                                                            echo "<td align = 'right'><button id='loanint".$row2['Id']."' class='primary' data-toggle='modal' data-target='#documentmodel'>".number_format($row2['ReceiptInt'],2,'.','')."</button></td>";
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
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														

                                                            <form class="form-horizontal" role="form" method = "post" action="acc_mem_settle_suc.php">
                                                                <div class="form-group">
                                                                    <div class="space-10"></div>
                                                                    <div class="col-sm-12">
                                                                        <?php 
                                                                            $amount = $totaldep - $totalloan;
                                                                            if($amount>0){
                                                                                $amountpaid = floor($amount);
                                                                                echo "<span style='color:green;'>Amount to be paid to member :".number_format($amountpaid,2,'.','')."</span>";
                                                                            }
                                                                            else{                                                                                
                                                                                $amountreceived = abs($amount);
                                                                                $amountreceived = ceil($amountreceived);
                                                                                
                                                                                echo "<span style='color:red;'>Amount to be paid by member :".number_format(abs($amountreceived),2,'.','')."</span>";
                                                                            }
                                                                        ?>
                                                                        
                                                                    </div>
                                                                    <div class="space-20"></div>
                                                                    <?php 
                                                                        if($amount > 0){
                                                                            echo '<label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Payment Type </label>';
                                                                            echo '<div class="col-sm-1">
                                                                                    <select name="ptype" id="ptype">
                                                                                        <option value="0"></option>
                                                                                        <option value="1">Cash</option>
                                                                                        <option value="2">Cheque</option>
                                                                                    </select>
                                                                                    <input type="hidden" id="flag" name="flag" value="2">
                                                                                    <input type="hidden" name="memid" value="'.$memid.'">
                                                                                    <input type="hidden" name="totdep" value="'.$totaldep.'">
                                                                                    <input type="hidden" name="totloan" value="'.$totalloan.'">
                                                                                    </div>';                                                                        
                                                                        }
                                                                        else{
                                                                            echo '<label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Amount Received </label>';    
                                                                            $amount = abs($amount);
                                                                            $amount = ceil($amount);                                                                            
                                                                            echo '<div class="col-sm-2">
                                                                                    <input type="text" id="amountreceived" name="amountreceived" value='.number_format($amount,2,'.','').'>
                                                                                    <input type="hidden" id="actualamount" value='.number_format($amount,2,'.','').'>
                                                                                    <input type="hidden" id="flag" name="flag" value="1">
                                                                                    <input type="hidden" name="memid" value="'.$memid.'">
                                                                                    <input type="hidden" name="totdep" value="'.$totaldep.'">
                                                                                    <input type="hidden" name="totloan" value="'.$totalloan.'">
                                                                                </div>';
                                                                        }
                                                                    ?> 
                                                                    <span id="bank">

                                                                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Bank Account </label>
                                                                        <div class="col-sm-2">
                                                                            <select name="banksubid" id="banksubid">
                                                                                <option value="0"></option>
                                                                                <?php
                                                                                    if($bankcount){
                                                                                        while($bankrow = mysqli_fetch_assoc($bank)){
                                                                                            echo "<option value=".$bankrow['SubID'].">".$bankrow['SubHead']."</option>";
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                            </select>

                                                                        </div>

                                                                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Cheque No </label>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control" id="cheque" name="cheque">
                                                                        </div>
                                                                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Amount Paid </label>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control" value="<?php echo number_format($amountpaid,2,'.',''); ?>" id="diffamount" name="diffamount" readonly >
                                                                        </div>
                                                                    </span>

                                                                    <span id="cash">
                                                                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Cash Balance </label>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control" value="<?php echo $cashob; ?>" id="cashbalance" name="cashbalance" readonly >
                                                                            <span style="color:red">Amount in Cash Transfer & Payments : Rs.<?php echo number_format($cashtrpayment,2,'.',''); ?> </span>
                                                                        </div>
                                                                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> Amount Paid </label>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control" value="<?php echo number_format($amountpaid,2,'.',''); ?>" id="amountpaid" name="amountpaid" readonly >
                                                                        </div>
                                                                    </span>
                                                                    
                                                                    <div class="space-20"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="content table-responsive table-full-width" style="float:left;">                        
                                                                        <a href='acc_mem_det.php?memid=<?php echo $memid; ?>'><button type='button' class='btn btn-info btn-fill'>
                                                                        <i class="ace-icon fa fa-arrow-left bigger-110"></i>Back</button></a>												
                                                                    </div>
                                            

                                                                    <div class="content table-responsive table-full-width" style="float:right;">                                                                
                                                                        <button id="button" type="submit" class='btn btn-info btn-fill'>
                                                                        <i class="ace-icon fa fa-check bigger-110"></i>Proceed</button>
                                                                    </div>
                                                                </div>
                                                            </form>
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

            <div id="documentmodelmutual" class="modal fade" role="dialog">
               
                         
            </div>

<?php 
	include("footer.php");    
?>			
<script>
$(document).ready(function(){
    $('#bank').hide();
    $('#cash').hide();
    $('#button').prop('disabled',true);
    var flag = $('#flag').val();
       
    if(flag == 1){
        var actualamount = $('#actualamount').val();
        var amountreceived = $('#amountreceived').val();
        if(actualamount == amountreceived)
            $('#button').prop('disabled',false);
        else
            $('#button').prop('disabled',true); 

        $('#amountreceived').on("keyup",function(){            
            amountreceived = $('#amountreceived').val();
            actualamount = $('#actualamount').val();
            if(actualamount == amountreceived)
                $('#button').prop('disabled',false);
            else
                $('#button').prop('disabled',true); 
        });        
    }
    else if(flag == 2){
        $('#ptype').on("change",function(){        
            var type = $('#ptype').val();
            if(type == 1){
                $('#cash').show();
                $('#bank').hide();
                var cashbalance = $('#cashbalance').val();
                cashbalance = parseInt(cashbalance);
                var amountpaid = $('#amountpaid').val();
                amountpaid = parseInt(amountpaid);                
                if(cashbalance >= amountpaid){
                    $('#button').prop('disabled',false);
                }
                else{
                    $('#button').prop('disabled',true);
                    alert("Available Balance less than Amount to be paid");
                }
            }            
            else if(type == 2){
                $('#bank').show();        
                $('#cash').hide();
                $('#banksubid').on("change",function(){                    
                    var bankid = $('#banksubid').val();
                    if(bankid == 0){
                        $('#button').prop('disabled',true);         
                    }
                    else{
                        $('#button').prop('disabled',false);         
                    }
                });
            }
                
            else{
                $('#bank').hide();
                $('#cash').hide();
                $('#button').prop('disabled',true); 
            }
        });
    }
    
    $('[id^=loanint]').on("click", function() {
        var id = $(this).attr('id');
        id = id.replace('loanint','');        
        $.ajax({  
            type: "POST",  
            url: "acc_mem_settle_int.php",  
            data: {id:id}, 
            success: function(response){ 																				
                $('#documentmodel').html(response);	
            } 
        });
    });


    $('[id^=mutual]').on("click", function() {
        var id = $(this).attr('id');
        id = id.replace('mutual','');        
        $.ajax({  
            type: "POST",  
            url: "acc_mem_settle_mutual.php",  
            data: {id:id}, 
            success: function(response){ 																				
                $('#documentmodelmutual').html(response);	
            } 
        });
    });
    
});
</script>