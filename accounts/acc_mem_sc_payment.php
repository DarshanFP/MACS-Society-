<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
  
    $sql = mysqli_query($connection,"SELECT balance from acc_sharecapital where memid = '$memid'");
    $row = mysqli_fetch_assoc($sql);
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);  
  
    
  
    $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule = 2");
    $count2 = mysqli_num_rows($sql2);
    
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


  
    	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Share Capital Payment 
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
                          
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
													</div>
												</div>
                        
                        <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
													</div>
												</div>
                        
                        <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
													</div>
												</div>

											</div>

											<div class="space-20"></div>

											<form class="form-horizontal" role="form" method="post" action="acc_mem_sc_payment_suc.php">
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sub Head </label>
                            <div class="col-sm-7">
                             <select name="subhead" id = "group" class="col-xs-10 col-sm-5" autocomplete="off" required>
                              <?php
                                while($row2 = mysqli_fetch_assoc($sql2)){
                                  echo "<option value = ".$row2['SubID'].">".$row2['SubHead']."</option>";
                                }
                              ?>
                            </select>             
                            <input type="hidden"  name="memid"   class="col-xs-10 col-sm-5" value="<?php echo $memid;?>" />                              
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Share Capital Balance </label>

                          <div class="col-sm-4">
                            <input type="text" id="scbalance" name="scbalance" value="<?php echo $row['balance']; ?>"  class="col-xs-10 col-sm-5" readonly />
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cash Balance </label>

                          <div class="col-sm-7">
                            <input type="text" id="cashbalance" name="loan" value="<?php echo $cashob; ?>" class="col-xs-10 col-sm-5" autocomplete="off" readonly />
                            <span style="color:red">Amount in Cash Transfer & Payments  <?php echo $cashtrpayment; ?> </span>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Share Capital Refund </label>

                          <div class="col-sm-7">
                            <input type="text" id="amount" name="amount" placeholder="Amount" class="col-xs-10 col-sm-5" autocomplete="off" required />
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Remarks </label>

                          <div class="col-sm-7">
                            <input type="text" id="remarks" name="remarks" placeholder="Amount" class="col-xs-10 col-sm-5" autocomplete="off" required />
                          </div>
                        </div>

                        <div class="clearfix form-group">
                          <div class="col-md-offset-3 col-md-9">
                            <button id="submit" class="btn btn-success" type="submit">
                              <i class="ace-icon fa fa-check bigger-110"></i>
                              Submit
                            </button>
                            <a href="acc_mem_sc_det.php?memid=<?php echo $memid; ?>"><button class="btn btn-info" type="button">
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
    $('#submit').prop('disabled', true);
    $("#amount").keyup(function(){
        var balance = $('#cashbalance').val();
        var amount = $('#amount').val();  
        var sc = $('#scbalance').val();
        amount = parseInt(amount);
        balance = parseInt(balance);                
        if(amount > balance){
          alert("Refund amount is greater than available cash balance.")
          $('#amount').val("");
          $('#submit').prop('disabled', true);          
        }
      else if(amount > sc){
          alert("Refund amount is greater than available Share Capital.")
          $('#amount').val("");
          $('#submit').prop('disabled', true);          
      }
       else if(amount == 0){
          $('#submit').prop('disabled', true);
        }
        else{
          $('#submit').prop('disabled', false);          
        }
                     
     });
  });
</script>