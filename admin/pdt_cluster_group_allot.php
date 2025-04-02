<?php
include("pdt_session.php");
	$_SESSION['curpage']="president_cluster";
if($_SERVER["REQUEST_METHOD"] == "POST"){
		$user = $_SESSION['login_user'];
		$groupid = $_POST['groupid'];
		$clusterid = $_POST['clusterid'];
        $today = date("Y-m-d h:m:s");
        
        mysqli_query($connection,"START TRANSACTION");
		$sql = "UPDATE groups SET ClusterID = '$clusterid' WHERE GroupID = '$groupid' AND ClusterID = '0' "; 
		$result = mysqli_query($connection,$sql);		
  
        $sql1 = mysqli_query($connection,"INSERT INTO groupallot (`GroupID`,`ClusterID`,`DOE`,`Status`) 
                                  VALUES ('$groupid','$clusterid','$today',1)");
        if($result && $sql1){
            mysqli_query($connection,"COMMIT");
        }
        else{
            mysqli_query($connection,"ROLLBACK"); 
        }
		header("location:pdt_cluster_det.php?clusterid=$clusterid");
	}	


 	mysqli_close($connection);


?>