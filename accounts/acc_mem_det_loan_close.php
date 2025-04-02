<?php 
  include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
  include("accountssidepan.php");
	$user = $_SESSION['login_user']; 
	if(isset($_GET["memid"])) {
		$memid = $_GET["memid"];
    $loanno = $_GET["loanno"];
    
    $close = mysqli_query($connection,"UPDATE acc_loans SET status = 0 WHERE loanno = '$loanno'");    
    
    header("location:acc_mem_det.php?memid=$memid");   
  }

?>