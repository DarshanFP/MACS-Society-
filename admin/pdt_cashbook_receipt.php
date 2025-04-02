<?php	  
  include("pdt_session.php");
	$_SESSION['curpage']="president_cashbook";
	include("pdtsidepan.php");

  
  $sql = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule = 1 OR SubHeadModule = 7");
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Cash Book Receipt Entry
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-8"> <!-- PAGE CONTENT BEGINS -->
                
							  <form action="pdt_cb_receipt_suc.php" method="post">
									<div class="row">
										<div class="col-md-4">
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
										
										<div class="col-md-4">
											<div class="form-group">
												<label>Details </label>
												<input type="text" class="form-control" name="details" required >
											</div>
										</div>	
                    
                    <div class="col-md-4">
											<div class="form-group">
												<label>Amount </label>
												<input type="text" class="form-control" name="amount" required >
												
											</div>
										</div>
									</div>

									<div class="row">   
										<div class="col-md-8">
											<a href="president_cashbook.php"><button type="button" class="btn btn-info btn-fill"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
										</div>										
										<div class="col-md-4">
											<div class="form-group label-floating">												
												
												<button type="submit" class="btn btn-info btn-fill pull-right"><i class="fa fa-check" aria-hidden="true"></i>Submit</button>
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