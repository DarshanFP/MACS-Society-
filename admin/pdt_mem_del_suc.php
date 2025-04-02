<?php
	include("pdt_session.php");
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$memid = $_POST['memid'];
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
		
		
		$sql1 = "UPDATE members SET memstatus =0 WHERE memid='$memid' AND memstatus = 1";
		$result1 = mysqli_query($connection, $sql1) or die(mysqli_error());
		
		
		header("location:president_member.php");
	}
	else{
		header("location:president_member.php");
	}	
	?>