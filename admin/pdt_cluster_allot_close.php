<?php
include("pdt_session.php");
	$_SESSION['curpage']="president_cluster";
if($_SERVER["REQUEST_METHOD"] == "GET"){
		$user = $_SESSION['login_user'];
		$empid = $_GET['empid'];
		$clusterid = $_GET['clusterid'];
		
		
		$today = date("Y-m-d");
		mysqli_query($connection,"start transaction");
			
		$sql = "UPDATE allot SET DOC='$today', Status = 0 WHERE EmpID = '$empid' AND ClusterID = '$clusterid' AND Status = 1"; 
		$result = mysqli_query($connection,$sql);
		
		$sql1 = "INSERT INTO `allot` ( `EmpID`,`ClusterID`, `DOE`,`Status`) 
		         VALUES ('$empid','','$today',0)";
		$result1 = mysqli_query($connection,$sql1);
		
		if($result && $result1){
			mysqli_query($connection,"commit");
		}
		else{
			mysqli_query($connection,"rollback");
		}
		 	header("location:pdt_cluster_det.php?clusterid=$clusterid");	
		
	}	

 	mysqli_close($connection);


?>