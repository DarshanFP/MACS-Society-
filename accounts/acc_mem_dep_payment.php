<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
if(isset($_GET['memid'])){
	$memid = $_GET['memid'];
    $depid = $_GET['depid'];
  
    $sql = mysqli_query($connection,"SELECT subheadid, cb from acc_deposits where depositno = '$depid'");
    $row = mysqli_fetch_assoc($sql);
    $subid = $row['subheadid'];
  
    $sql4 = mysqli_query($connection,"SELECT SubHead FROM acc_subhead WHERE SubID = '$subid'");
    $row4 = mysqli_fetch_assoc($sql4);
  
    $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
    $result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);  
    
    $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE (SubID = '$subid' OR SubHeadModule = 9) ORDER BY SubHeadModule ASC");
    $count2 = mysqli_num_rows($sql2);
    $sql12 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE (SubID = '$subid' OR SubHeadModule = 9) ORDER BY SubHeadModule ASC");
    
    
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
								 <?php echo $row4['SubHead']; ?> Payment 
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
													<div class="profile-info-name"> Deposit No </div>

													<div class="profile-info-value">
														<span class="editable"> <?php echo $depid;?> </span>
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

						
                        
                        <form class="form-horizontal" role="form" method="post" action="acc_mem_dep_payment_suc.php">

                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Mode of Payment</label>
                            <div class="col-sm-7">
                             <select name="mode" id = "mode" class="col-xs-6 col-sm-3" autocomplete="off" required>
                                <option value = 0></option>   
                                <option value = 1>Cash</option>
                                <option value = 2>Adjustment</option>
                             </select>             
                            </div>
                        </div>
                        <div class="form-group">      
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1" id="sublabel" > Sub Head </label>
                            <div class="col-sm-7">
                             <select name="subhead" id = "subhead" class="col-xs-10 col-sm-5" autocomplete="off" required>
                             <option></option>
                              <?php
                                while($row2 = mysqli_fetch_assoc($sql2)){
                                  echo "<option value = ".$row2['SubID'].">".$row2['SubHead']."</option>";
                                }
                              ?>
                            </select>             
                            <input type="hidden"  name="memid"   class="col-xs-10 col-sm-5" value="<?php echo $memid;?>" />
                            <input type="hidden"  name="depositno" id="depositno"  class="col-xs-10 col-sm-5" value="<?php echo $depid;?>" />  
                          </div>
                        </div>
                        
                        <div class="form-group" id="balance" name="balance">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Deposit Balance </label>

                          <div class="col-sm-4">
                            <input type="text" id="depbalance" name="depbalance" value="<?php echo $row['cb']; ?>"  class="col-xs-10 col-sm-5" readonly />
                          </div>
                        </div>
                        
                        <div class="form-group" id="cash" name="cash">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cash Balance </label>

                          <div class="col-sm-7">
                            <input type="text" id="cashbalance" name="loan" value="<?php echo $cashob; ?>" class="col-xs-10 col-sm-5" autocomplete="off" readonly />
                            <span style="color:red">Amount in Cash Transfer & Payments  <?php echo $cashtrpayment; ?> </span>
                          </div>
                        </div>
                        
                        <div class="form-group" id="refund" name="refund">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Deposit Refund </label>

                          <div class="col-sm-7">
                            <input type="text" id="amount" name="amount" placeholder="Amount" class="col-xs-10 col-sm-5" autocomplete="off" required />
                          </div>
                        </div>
                        
                        <div class="form-group" id="notes" name="notes">
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
                            <a href="acc_mem_dep_det.php?depid=<?php echo $depid; ?>&memid=<?php echo $memid; ?>"><button id="back" class="btn btn-info" type="button">
                              <i class="ace-icon fa fa-arrow-left bigger-110"></i>
                              Back
                            </button></a>											
                          </div>
                        </div>
                      </form>	

                <!-- Adjustment Block -->        
                <div class="form-group" style="margin-left:45px;" id = "adjustment" name = "adjustment">                
                <div class="content table-responsive table-full-width" >
                    <table class="table ">	
                    <thead>
                      <th>Sl.No</th>
                      <th>Select Head</th>
                      <!-- <th>Select Sub Head</th> -->
                      <th>Details</th>
                      <th style="text-align:center;">Amount</th>                      
                      <th></th>
                    </thead>	
                    <tbody>
                      <tr> <form action="acc_cb_adj_add.php" method ="post">
                        <td text-align="center"> 1 </td>
                        <td>												
                          <select name="subid2" id="subhead2" class="js-example-basic-single form-control" style="width:200px;" >
                            <option></option>
                            <?php while ($row12 = mysqli_fetch_assoc($sql12))
                              echo "<option value ='".$row12['SubID']."'>".$row12["SubHead"]."</option>";								
                            ?>
                          </select>												
                        </td>		
                        <!-- <td><select name="accno" id="accnoselect" class="js-example-basic-single form-control" style="width:150px;" disabled  >	 -->

                          </select></td>
                        <td><input type="text" class="form-control" name ="details"  id="details" autocomplete="off" required></td>											                        
                        <td><input type="text" style="text-align:center" class="form-control" name ="debit"  id="debit" autocomplete="off" ></td>

                        <td><button type="submit" class="btn btn-info btn-fill">Add</button></td>													
                        </form>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="header">                                
								  <h4 class="title">Passed Entries</h4>                                									
				</div>             
          
                <div class="content table-responsive table-full-width">
                    <table class="table">
                        <thead>
                        <th>Sl.No</th>
                        <th>Head Name</th>																													
                        <th>Details</th>
                        <th style="text-align:right">Receipt</th>
                        <th style="text-align:right">Payment</th>
                        <th style="text-align:center">Delete</th>                                    	
                        </thead>
                        <tbody>
                        <?php 
                            $debit = 0;
                              $credit = 0;
                            if($count2>0){
                              $slno=1;				
                              while($row2 = mysqli_fetch_assoc($sql2))
                              { 

                                echo "<tr><td>".$slno."</td>";
                                echo "<td>".$row2['SubHead']."</td>";
                                echo "<td>".$row2['details']."</td>";                                
                                echo "<td style='text-align:right'>".$row2['receiptadj']."</td>";
                                echo "<td style='text-align:right'>".$row2['paymentadj']."</td>";

                                echo "<td align='center'>
                                      <a href='pdt_cb_adj_del.php?id=".$row2['id']."'><i class='fa fa-close'></i></a>							  
                                    </td></tr>";

                                $slno = $slno + 1;
                                $debit = $debit + $row2['paymentadj'];
                                $credit = $credit + $row2['receiptadj'];	

                              }				
                            }
                            ?> 
                            <tr>
                              <td></td>
                              <td><b>Total Amount</b></td>
                              <td></td>
                              <td style='text-align:right'><b><?php echo $debit; ?></b></td>
                              <td style='text-align:right'><b><?php echo $credit; ?></b></td>
                              <td></td>
                            </tr>

                            <tr>
                              <td><a href="acc_mem_dep_det.php?depid=<?php echo $depid; ?>&memid=<?php echo $memid; ?>"><button id="back" class="btn btn-info" type="button">
                              <i class="ace-icon fa fa-arrow-left bigger-110"></i>
                              Back
                            </button></a></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="float:right;"><?php
                                if($debit == $credit && $debit!= 0 ){
                                  echo "<a href='pdt_cb_adj_suc.php'><button type='submit' class='btn btn-info btn-fill' id='proceed'>Proceed</button></a>";                                									
                                }
                                else {

                                  echo "<button class='btn btn-info btn-fill' disabled>Proceed</button>";
                                } ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                <!-- End of Adjustment Block -->                
                        
	
												
                      
                      
                      
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
    $('#balance').hide();
    $('#cash').hide();
    $('#refund').hide();
    $('#notes').hide();
    $('#sublabel').hide();
    $('#subhead').hide();  
    $('#submit').hide();  
    $('#back').hide();  
    $('#adjustment').hide();
    $('#subhead').change(function(){
      var depositno = $('#depositno').val();
      var subid = $('#subhead').val();      
      $.ajax({
        url:"acc_mem_dep_balance.php",
        method :"POST",
        data : {depositno:depositno, subid:subid},
        success : function(data){
          
          if(data == 1)
          $('#depbalance').val('');
          else
          $('#depbalance').val(data);
        },
       });
      
    });

    $('#mode').change(function(){
        
      var mode = $('#mode').val();
      if(mode == 0){
        $('#balance').hide();
        $('#cash').hide();
        $('#refund').hide();
        $('#notes').hide();
        $('#sublabel').hide();
        $('#subhead').hide();  
        $('#adjustment').hide();
      }
      if(mode == 1){
        $('#balance').show();
        $('#cash').show();
        $('#refund').show();
        $('#notes').show();
        $('#sublabel').show();
        $('#subhead').show();  
        $('#adjustment').hide();
        $('#submit').show();  
        $('#back').show();  
      }
      if(mode == 2){
        $('#balance').hide();
        $('#cash').hide();
        $('#refund').hide();
        $('#notes').hide();
        $('#sublabel').hide();
        $('#subhead').hide(); 
        $('#submit').hide();  
        $('#back').hide();   
        $('#adjustment').show();
      }
    });
    
    
    
    $('#submit').prop('disabled', true);
    $("#amount").keyup(function(){
        var balance = $('#cashbalance').val();
        var amount = $('#amount').val();  
        var dep = $('#depbalance').val();
        amount = parseInt(amount);
        balance = parseInt(balance);     
        
        
      if(dep==''){
        if(amount > balance){
          alert("Refund amount is greater than available cash balance.")
          $('#amount').val("");
          $('#submit').prop('disabled', true);          
        }       
       else if(amount == 0){
          $('#submit').prop('disabled', true);
        }
        else{
          $('#submit').prop('disabled', false);          
        }
      }
      else{
        if(amount > balance){
          alert("Refund amount is greater than available cash balance.")
          $('#amount').val("");
          $('#submit').prop('disabled', true);          
        }
       else if(amount > dep){
          alert("Refund amount is greater than available Deposit.")
          $('#amount').val("");
          $('#submit').prop('disabled', true);          
        }
       else if(amount == 0){
          $('#submit').prop('disabled', true);
        }
        else{
          $('#submit').prop('disabled', false);          
        }
      }
        
                     
     });
  });
</script>