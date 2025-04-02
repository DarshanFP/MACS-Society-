<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_cashtr";
	include("accountssidepan.php");

  $today = $_SESSION['backdate'];
  $sql = mysqli_query($connection,"SELECT * FROM cluster WHERE ClusterID!='$clusterid'");

  $cashfirstob = 0;
  $cashtrpayment = 0;
  $cashexppayment = 0;
        $sql2 = mysqli_query($connection,"SELECT sum(paymentcash) FROM acc_cash_dummy_transfer WHERE clusterid = '$clusterid' AND status = 0 GROUP BY clusterid");
        $cou2 = mysqli_num_rows($sql2);
        if($cou2 > 0){
            $cashtr = mysqli_fetch_assoc($sql2);
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

?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Cash Transfer
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                
							  <form action="accounts_cash_transfer.php" method="post">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label>Select Cluster </label>
												<select name="cluster"  class="js-example-basic-single form-control" required>
													<option></option>
													<?php while ($row = mysqli_fetch_assoc($sql)) 												
														echo "<option value ='".$row['ClusterID']."'>".$row["ClusterName"]."</option>";								
													 ?>
												</select>
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label>Details </label>
												<input type="text" class="form-control" name="details" required >
											</div>
										</div>	
                    
                                        <div class="col-md-3">
											<div class="form-group">
												<label>Cash Balance </label>
												<input type="text" class="form-control" value="<?php echo $cashob; ?>" id="cashbalance" name="cashbalance" readonly >
                                                <span style="color:red">Amount in Cash Transfer & Payments <?php echo $cashtrpayment; ?> </span>
                                            </div>
										</div>	
                    
                                        <div class="col-md-3">
											<div class="form-group">
												<label>Amount </label>
												<input type="text" class="form-control" name="amount" id="amount" required >
												
											</div>
										</div>
									</div>

									<div class="row">   
										<div class="col-md-8">
											
										</div>										
										<div class="col-md-4">
											<div class="form-group label-floating">												
												
												<button type="submit" id="submit" class="btn btn-info btn-fill pull-right"><i class="fa fa-check" aria-hidden="true" style="padding-right:5px"></i>Submit</button>
											</div>
										</div>                            
									</div>
								</form>                 
							
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
      if($.isNumeric(amount)){
        amount = parseInt(amount);
        balance = parseInt(balance);                
        if(amount > balance){
          alert("Amount is greater than available cash balance.")
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
        alert("Enter Numeric Value Only.")
        $('#amount').val("");
        $('#submit').prop('disabled', true);         
      }
                     
     });
  });
</script>