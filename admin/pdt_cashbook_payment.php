<?php	  
  include("pdt_session.php");
	$_SESSION['curpage']="president_cashbook";
	include("pdtsidepan.php");

  
  $sql = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule = 1 OR SubHeadModule = 7");

  $cashfirstob = 0;
  $cashtrpayment = 0;

        $sql2 = mysqli_query($connection,"SELECT sum(paymentcash) FROM acc_cash_dummy_transfer WHERE clusterid =  '$clusterid' AND status = 0 GROUP BY clusterid");
        $cou2 = mysqli_num_rows($sql2);
        if($cou2 > 0){
            $cashtr = mysqli_fetch_assoc($sql2);
            $cashtrpayment = $cashtr['sum(paymentcash)'];
        }
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
								Cash Book Payment Entry
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-10 col-xs-12"> <!-- PAGE CONTENT BEGINS -->
                
							  <form action="pdt_cb_payment_suc.php" method="post">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label>Select Head </label>
												<select name="subid"  class="js-example-basic-single form-control" required>
													<option></option>
													<?php while ($row = mysqli_fetch_assoc($sql)) 												
														echo "<option value ='".$row['SubID']."'>".$row["SubHead"]."</option>";								
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
												<input type="text" class="form-control" value="<?php echo $cashob; ?>" id="cashbalance" name="balance" readonly >
                                                <span style="color:red">Amount in Cash Transfer <?php echo $cashtrpayment; ?> </span>
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
											<a href="president_cashbook.php"><button type="button" class="btn btn-info btn-fill"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
										</div>										
										<div class="col-md-4">
											<div class="form-group label-floating">												
												
												<button type="submit" id="submit" class="btn btn-info btn-fill pull-right"><i class="fa fa-check" aria-hidden="true"></i>Submit</button>
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
                     
     });
  });
</script>