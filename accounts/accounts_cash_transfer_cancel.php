<?php	  
		include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if(isset($_GET['cashid'])){	
	$cashid = $_GET['cashid'];
  $sql4 = mysqli_query($connection,"UPDATE acc_cash_dummy_transfer SET status = 2 WHERE CashTrID = '$cashid' ");
	$result4 = mysqli_fetch_assoc($sql4);
}
header("location:accounts_cash_transfer_all.php");	
?>