<?php 
	  include("accounts_session.php");		
		
		$memid = $_POST['memid']; 		
		$sql = "SELECT memname,Groupname,Clustername FROM members,groups,cluster WHERE memid = '$memid' AND members.memgroupid = groups.GroupID AND members.memclusterid = cluster.ClusterID";
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		$count = mysqli_num_rows($result);

 		if($count < 1){
 			echo (json_encode('Account does not exists'));
 		}
 		else{
		  echo (json_encode($row));
		}
		
?>

		