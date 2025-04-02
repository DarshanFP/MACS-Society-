<?php
include("pdt_session.php");
	$_SESSION['curpage']="president_cluster";
if($_SERVER["REQUEST_METHOD"] == "GET"){
		$user = $_SESSION['login_user'];
		$groupid = $_GET['groupid'];	
    $clusterid = $_GET['clusterid'];
		
		
		$today = date("Y-m-d h:m:s");
		mysqli_query($connection,"start transaction");
			
		$sql = "UPDATE groupallot SET DOC='$today', Status = 0 WHERE GroupID = '$groupid' AND Status = 1"; 
		$result = mysqli_query($connection,$sql);
		
		$sql1 = "UPDATE groups SET ClusterID='0' WHERE GroupID = '$groupid' AND Status = 1";   
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