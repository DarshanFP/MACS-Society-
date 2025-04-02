<?php 
  include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
  include("accountssidepan.php");
	$user = $_SESSION['login_user']; 
	if(isset($_GET["memid"])) {
		$memid = $_GET["memid"];
    $depno = $_GET["depno"];
    
    $close = mysqli_query($connection,"UPDATE acc_deposits SET status = 0 WHERE depositno = '$depno'");    
    
    header("location:acc_mem_det.php?memid=$memid");   
  }

?>