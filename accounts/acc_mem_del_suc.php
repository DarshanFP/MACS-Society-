<?php
	include("accounts_session.php");
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$memid = $_POST['memid'];
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
		
		mysqli_query($connection, "start transaction");
		$sql1 = "UPDATE members SET memstatus =0 WHERE memid='$memid' AND memstatus = 1";
		$result1 = mysqli_query($connection, $sql1);
		$sql2 = "UPDATE memmonitoring SET memmonstatus =0, mementrydate='$today', memempid = '$user' WHERE memid='$memid' AND memmonstatus = 1";
		$result2 = mysqli_query($connection, $sql2);
        if($result1 && $result2){
            mysqli_query($connection, "commit");
        }
        else{
            mysqli_query($connection, "rollback");
        }
		header("location:accounts_member.php");
	}
	else{
		header("location:accounts_member.php");
	}	
	?>