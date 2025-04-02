<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_ob";
	include("pdtsidepan.php");
  date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());


  if($_SERVER['REQUEST_METHOD'] == "POST") {
      $ob = $_POST['ob'];
      $subid = $_POST['subid'];
      $doe = mysqli_query($connection,"SELECT date FROM master");
      $doe = mysqli_fetch_assoc($doe);
      $doe = $doe['date'];
    
      $mainid = mysqli_query($connection,"SELECT MainID, SubHead FROM acc_subhead, acc_majorheads 
                                      WHERE acc_subhead.MajorID = acc_majorheads.MajorID
                                      AND acc_subhead.SubID ='$subid'");
      $mainid = mysqli_fetch_assoc($mainid);
      $subhead = $mainid['SubHead'];
      $mainid = $mainid['MainID'];
    
      $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
 	    $transcount = mysqli_num_rows($trans);
      $transcount = 1001 + $transcount;	
 	    $transid = "TRANS".$transcount;
 	
	    mysqli_query($connection,"start transaction");
	
 	    $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`,`timedate`) VALUES ('$transid',1,'$timedate')");

	    //Cash Book Entry Start			
      if($mainid == 1){
        $sql1 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`, `subheadid`, `details`,`ob`, `receiptadj`,`remarks`,`entryempid`,`timedate`)
						VALUES('$doe','$clusterid','','$transid', '$subid', 'ob',1,'$ob','ob','$user','$timedate')");   
      }
      else if($mainid == 2){
        $sql1 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`, `subheadid`, `details`,`ob`, `paymentadj`,`remarks`,`entryempid`,`timedate`)
						VALUES('$doe','$clusterid','','$transid', '$subid', 'ob',1,'$ob','ob','$user','$timedate')");           
      }
	    
      
	    //Cash Book Entry End    
    
      $ob = mysqli_query($connection,"INSERT INTO acc_subhead_ob (`TransID`,`ClusterID`,`SubID`,`DOE`,`OB`) 
                    VALUES('$transid','$clusterid','$subid','$doe','$ob')");    
      
    
      if($transinsert && $sql1 && $ob){
        mysqli_query($connection,"commit");
      }
      else{
        mysqli_query($connection,"rollback");
      }
 	
  }  

  $cashob = mysqli_query($connection, "SELECT sum(OB) FROM acc_subhead_ob
                                          WHERE acc_subhead_ob.SubID = '$subid'");
  $cashob = mysqli_fetch_assoc($cashob);
  $cashob = $cashob['sum(OB)']  
  
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balance : <?php echo $subhead; ?> - Set Succesfully
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
							
                <div class="form-horizontal" >
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Cash OB </label>

										<div class="col-sm-7">
											<input value ="<?php echo $cashob; ?>" disabled />
										</div>
									</div>
                  
                  <div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">											
                      <a href='president_ob.php'><button class="btn btn-primary">
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>
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