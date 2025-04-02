<?php 
	include("pdt_session.php");
	$user = $_SESSION['login_user'];
    if($_SERVER['REQUEST_METHOD']=='POST'){	
        $cluster = $_POST['cluster'];
        
        $sql3="SELECT GroupID,GroupName FROM groups WHERE ClusterID='$cluster'";
	    $result3=mysqli_query($connection,$sql3);
        echo "<option></option>";
        if($cluster == 'All')  echo "<option>All</option>";
        else{
            while ($row3 = mysqli_fetch_assoc($result3)){
                echo "<option value ='".$row3['GroupID']."'>".$row3['GroupName']."</option>";
            }
        }
    }
?>