<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_ob";
	include("accountssidepan.php");
  date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());


  if($_SERVER['REQUEST_METHOD'] == "POST") {
      $cashob = $_POST['cashob'];
      $subid = mysqli_query($connection,"SELECT SubID FROM acc_subhead WHERE SubHeadModule = 10");
      $subid = mysqli_fetch_assoc($subid);
      $subid = $subid['SubID'];
      $doe = mysqli_query($connection,"SELECT date FROM master");
      $doe = mysqli_fetch_assoc($doe);
      $doe = $doe['date'];
    
      $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
 	    $transcount = mysqli_num_rows($trans);
      $transcount = 1001 + $transcount;	
 	    $transid = "T".$macsshortname.$transcount;
 	
	    mysqli_query($connection,"start transaction");
	
 	    $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`,`timedate`) VALUES ('$transid',1,'$timedate')");

	    //Cash Book Entry Start			
	    $sql1 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`, `subheadid`, `details`,`ob`, `receiptcash`,`remarks`,`entryempid`,`timedate`)
						VALUES('$doe','$clusterid','','$transid', '$subid', 'cashob',1,'$cashob','cashob','$user','$timedate')"); 
      
    
      $balance = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance + $cashob WHERE ClusterID = '$clusterid'");
	    //Cash Book Entry End    
    
      $ob = mysqli_query($connection,"INSERT INTO acc_subhead_ob (`TransID`,`ClusterID`,`SubID`,`DOE`,`OB`) 
                    VALUES('$transid','$clusterid','$subid','$doe','$cashob')");    
      
    
      if($transinsert && $sql1 && $balance && $ob){
        mysqli_query($connection,"commit");
      }
      else{
        mysqli_query($connection,"rollback");
        if($transinsert)
            echo "Trans Insert Ok";
        if($sql1)
            echo "Cash Book Inert Ok";
        if($balance)
            echo "Balance Update Ok";
        if($ob)
            echo "SubHead Insert Ok";
      }
 	
  }  

  $cashob = mysqli_query($connection, "SELECT sum(OB) FROM acc_subhead_ob, acc_subhead 
                                          WHERE acc_subhead.SubHeadModule = 10 
                                                AND acc_subhead.SubID = acc_subhead_ob.SubID
                                                AND acc_subhead_ob.ClusterID='$clusterid' GROUP BY ClusterID");
  $cashob = mysqli_fetch_assoc($cashob);
  $cashob = $cashob['sum(OB)']  


?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balance : Cash - Set Already / Edited Succesfully
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
                      <a href='accounts_ob.php'><button class="btn btn-primary">
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>
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