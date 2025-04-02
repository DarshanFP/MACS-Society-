<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$memid = $_POST["memid"];
        $subheadid = $_POST['subhead'];
        $dod = $_POST['dod'];
        $deposit = $_POST['deposit'];
        $installment = $_POST['installment'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
   
    $sql = mysqli_query($connection,"SELECT * FROM acc_deposits");
 		$count = mysqli_num_rows($sql);	
		$count = 1001 + $count;
		$depositid = "D".$macsshortname.$count;
    
    $timedate = date('Y-m-d H:i:s', time());
    
    $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubID = '$subheadid'");
    $row2 = mysqli_fetch_assoc($sql2);
    
    $sql3 = mysqli_query($connection,"SELECT GroupID, ClusterID FROM groups, members WHERE memid = '$memid' AND memgroupid = GroupID");
    $row3 = mysqli_fetch_assoc($sql3);
    $groupid = $row3['GroupID'];
    $clusterid = $row3['ClusterID'];
    
	$trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
 	$transcount = mysqli_num_rows($trans);
  $transcount = 1001 + $transcount;	
 	$transid = "T".$macsshortname.$transcount;
 	
	mysqli_query($connection,"start transaction");	
  
 	$transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`,`timedate`) 
                              VALUES ('$transid',1,'$timedate')") or die(mysqli_error($connection));
 	
  
	//Cash Book Entry Start			
  
  $sql4 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptcash`,`remarks`,`entryempid`,`timedate`)
						VALUES('$dod','$clusterid','$groupid', '$transid', '$memid', '$subheadid', '$depositid', 'New Deposit','$deposit','New Deposit','$user','$timedate')") or die(mysqli_error($connection));	
  
  $balance = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance + $deposit WHERE ClusterID = '$clusterid'");
	
	//Cash Book Entry End
    
  //Deposit Ledger Start	
  
  $sql5 = mysqli_query($connection,"INSERT INTO acc_deposits (`memid`, `subheadid`, `depositno`, `dateofopening`, `ob`, `installment`, `cb`, `empid`)
						VALUES('$memid','$subheadid','$depositid', '$dod', '$deposit', '$installment', '$deposit', '$user')") or die(mysqli_error($connection));	
		//Deposit Ledger End  


	
	if($transinsert && $sql4 && $sql5 && $balance){
		mysqli_query($connection,"commit");
	}
	else{
		mysqli_query($connection,"rollback");
	}
}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 New Deposit Successfully initiated with No: <?php echo $depositid; ?> with Trans ID : <?php echo $transid; ?>
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

											<form class="form-horizontal" role="form" >
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Deposit No </label>
                            <div class="col-sm-7">
                             <input type="text" class="col-xs-10 col-sm-5" value =" <?php echo $depositid; ?>" readonly/>  
                          </div>
                        </div>

                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Type of Deposit </label>
                            <div class="col-sm-7">
                             <input type="text"  class="col-xs-10 col-sm-5" value =" <?php echo $row2['SubHead']; ?>" readonly/>  
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Deposit </label>

                          <div class="col-sm-4">
                            <input type="text" value="<?php echo $dod; ?>" class="col-xs-10 col-sm-5" readonly/>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Initial Deposit </label>

                          <div class="col-sm-7">
                            <input type="text" value="<?php echo $deposit; ?>" class="col-xs-10 col-sm-5" readonly />
                          </div>
                        </div>

                        <div class="clearfix form-group">
                          <div class="col-md-offset-3 col-md-9">
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