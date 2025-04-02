<?php 
    include("accounts_session.php");
    $_SESSION['curpage']="accounts_member";
    include("accountssidepan.php");
	$user = $_SESSION['login_user']; 
    date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

	if($_SERVER['REQUEST_METHOD'] == "POST") {
      $ob = $_POST['balance'];
      $subid = $_POST['subid'];  
      $memid = $_POST['memid'];
      $installment = $_POST['installment'];
      $doe = $name['date'];
    
      $groupid = mysqli_query($connection,"SELECT memgroupid FROM members WHERE memid='$memid'");
      $groupid = mysqli_fetch_assoc($groupid);
      $groupid = $groupid['memgroupid'];
    
      $mainid = mysqli_query($connection,"SELECT MainID, SubHead, SubHeadModule FROM acc_subhead, acc_majorheads 
                                      WHERE acc_subhead.MajorID = acc_majorheads.MajorID
                                      AND acc_subhead.SubID ='$subid'");
    
      $mainid = mysqli_fetch_assoc($mainid);
      $subhead = $mainid['SubHead'];
      $module = $mainid['SubHeadModule'];
      $mainid = $mainid['MainID'];
      
    
      $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
 	    $transcount = mysqli_num_rows($trans);
      $transcount = 1001 + $transcount;	
 	    $transid = "T".$macsshortname.$transcount;
    
      
 	
	    mysqli_query($connection,"start transaction");
	
 	    $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`,`timedate`) VALUES ('$transid',1,'$timedate')");
    
      if($module == 2){
        $accno = $memid;
        $ledinsert = mysqli_query($connection,"INSERT INTO acc_sharecapital (`memid`,`subheadid`,`dateofopening`,`balance`,`empid`)
                                            VALUES('$memid','$subid','$doe','$ob','$user')");
      }
      else if($module == 3){
        $sql = mysqli_query($connection,"SELECT * FROM acc_loans");
        $count = mysqli_num_rows($sql);	
        $count = 101 + $count;
        $accno = "L".$macsshortname.$count;
        $ledinsert = mysqli_query($connection,"INSERT INTO acc_loans (`memid`, `subheadid`, `loanno`, `dateofissue`, `ob`, `installment`, `cb`, `empid`)
						VALUES('$memid','$subid','$accno', '$doe', '$ob', '$installment', '$ob', '$user')") or die(mysqli_error($connection));	
      }
      else if($module == 4){
        $sql = mysqli_query($connection,"SELECT * FROM acc_deposits");
        $count = mysqli_num_rows($sql);	
        $count = 101 + $count;
        $accno = "D".$macsshortname.$count;
        $ledinsert = mysqli_query($connection,"INSERT INTO acc_deposits (`memid`, `subheadid`, `depositno`, `dateofopening`, `ob`, `installment`, `cb`, `empid`)
						VALUES('$memid','$subid','$accno', '$doe', '$ob', '$installment', '$ob', '$user')") or die(mysqli_error($connection));	
      }

	    //Cash Book Entry Start			
      if($mainid == 1){
        $cashbook = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`,`memid`, `subheadid`,`accno`, `details`,`ob`, `receiptadj`,`remarks`,`entryempid`,`timedate`)
						VALUES('$doe','$clusterid','$groupid','$transid','$memid', '$subid','$accno', 'ob',1,'$ob','ob','$user','$timedate')");   
      }
      else if($mainid == 2){
        $cashbook = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`,`memid`, `subheadid`,`accno`, `details`,`ob`, `paymentadj`,`remarks`,`entryempid`,`timedate`)
						VALUES('$doe','$clusterid','$groupid','$transid','$memid', '$subid','$accno', 'ob',1,'$ob','ob','$user','$timedate')");           
      }
	    
      
	    //Cash Book Entry End    
    
      $subob = mysqli_query($connection,"INSERT INTO acc_subhead_ob (`TransID`,`ClusterID`,`SubID`,`DOE`,`OB`) 
                    VALUES('$transid','$clusterid','$subid','$doe','$ob')");   
    
      $memob = mysqli_query($connection,"INSERT INTO `acc_mem_ob`(`memid`, `subheadid`, `accno`, `dateofopening`, `ob`, `installment`, `empid`) 
                  VALUES ('$memid','$subid','$accno','$doe','$ob','$installment','$user')");
      
      if($module == 2 || $module == 3  || $module == 4){
          
        if($transinsert && $ledinsert && $cashbook && $subob && $memob){
            mysqli_query($connection,"commit");
            }
        else{
            mysqli_query($connection,"rollback");
        }
      }  
      else{
          
        if($transinsert && $cashbook && $subob && $memob){
            mysqli_query($connection,"commit");
            }
        else{
            mysqli_query($connection,"rollback");
        }
      }
          

     header("location:accounts_ob_mem_det.php?memid=$memid");   
  }

?>