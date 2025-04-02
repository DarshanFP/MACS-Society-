<?php
include("pdt_session.php");
	$_SESSION['curpage']="president_cluster";
if($_SERVER["REQUEST_METHOD"] == "POST"){
		$user = $_SESSION['login_user'];
		$empid = $_POST['empid'];
		$clusterid = $_POST['clusterid'];
		$today = date("Y-m-d");
		$sql = "UPDATE allot SET ClusterID = '$clusterid', DOE='$today', Status = 1 WHERE EmpID = '$empid' AND ClusterID = '' AND Status = 0"; 
		$result = mysqli_query($connection,$sql);		
		header("location:pdt_cluster_det.php?clusterid=$clusterid");
	}	


 	mysqli_close($connection);


?>