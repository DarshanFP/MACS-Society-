<?php	  
		include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if(isset($_GET['expid'])){	
	$expid = $_GET['expid'];
  $sql4 = mysqli_query($connection,"UPDATE acc_cash_dummy_expenses SET status = 2 WHERE PaymentID = '$expid' ");
	$result4 = mysqli_fetch_assoc($sql4);
}
header("location:acc_cb_expenses_all.php");	
?>