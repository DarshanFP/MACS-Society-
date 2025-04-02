<?php 
    include('accounts_session.php');

unset($_SESSION['backdate']);
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d");
$_SESSION['backdate'] = $today;

 	mysqli_close($connection);


header("location: accounts.php");	
?>