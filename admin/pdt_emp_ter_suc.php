<?php
	include("pdt_session.php");
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$empid = $_POST['empid'];
		$today = date("Y-m-d");
		$user = $_SESSION['login_user'];
		
		mysqli_query($connection, "start transaction");
		$sql1 = "UPDATE employee SET empstatus =0 WHERE empid='$empid' AND empstatus = 1";
		$result1 = mysqli_query($connection, $sql1);
		$sql2 = "UPDATE allot SET Status =0 WHERE empid='$empid' AND Status = 1";
		$result2 = mysqli_query($connection, $sql2);
		$sql3 = "UPDATE users SET userstatus =0 WHERE userid='$empid' AND userstatus = 1";
        $result3 = mysqli_query($connection, $sql3);
        if($result1 && $result2 && $result3){
		    mysqli_query($connection, "commit");
        }
        else{
           mysqli_query($connection, "rollback"); 
        }
		header("location:president_employee.php");
	}
	else{
		header("location:president_employee.php");
	}	
?>