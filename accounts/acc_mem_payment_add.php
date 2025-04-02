<?php 
  include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
  include("accountssidepan.php");
	$user = $_SESSION['login_user']; 
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$memid = $_POST["memid"];
    $amount = $_POST['amount'];
    $subheadid = $_POST['subid'];
    $sql2 = mysqli_query($connection,"SELECT SubHeadModule FROM acc_subhead WHERE SubID = '$subheadid'");
    $row2 = mysqli_fetch_assoc($sql2);
    if($row2['SubHeadModule'] == 9){
      $accno = $_POST['depositno'];  
    }
    else{
      $accno = $_POST['accno'];  
    }
    
    $sql3 = mysqli_query($connection,"SELECT GroupID, ClusterID FROM groups, members WHERE memid = '$memid' AND memgroupid = GroupID");
    $row3 = mysqli_fetch_assoc($sql3);
    $groupid = $row3['GroupID'];
    $clusterid = $row3['ClusterID'];
    
	
	mysqli_query($connection,"start transaction");	
  
 	 
	//Cash Book Entry Start			
  
  $sql4 = mysqli_query($connection,"INSERT INTO acc_payment_dummy (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `paymentadj`,`remarks`,`entryempid`)
						VALUES('$today','$clusterid','$groupid', 0, '$memid','$subheadid', '$accno', 'Cheque Payment','$amount','Payment','$user')") or die(mysqli_error($connection));	
	
	//Cash Book Entry End
    
  
	
	if($sql4){
		mysqli_query($connection,"commit");
	}
	else{
		mysqli_query($connection,"rollback");
	}
 header("location:acc_mem_payment.php?memid=$memid");   
}

?>