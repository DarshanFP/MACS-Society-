<?php	    
	include("pdt_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $cluster = $_POST['cluster'];
        $sql2=mysqli_query($connection,"SELECT GroupID,Groupname FROM groups WHERE ClusterID='$cluster'") or die(mysqli_error($sql2));
		echo '<option></option>';
		while($row2 = mysqli_fetch_assoc($sql2)){																		
			echo "<option value = ".$row2['GroupID'].">".$row2['Groupname']."</option>"; 
		}
    }
?>