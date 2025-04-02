<?php 
include("pdt_session.php");
if($_SERVER['REQUEST_METHOD'] == "POST") {
   $clusterid = $_POST['cluster'];       
  
   $sql = mysqli_query($connection,"SELECT GroupID, GroupName FROM groups WHERE ClusterID = '$clusterid'");
   $count = mysqli_num_rows($sql);   
   
   echo "<option>All</option>";
   if($count > 0){              
     while($row = mysqli_fetch_assoc($sql)){
        echo "<option value='".$row['GroupID']."'>".$row['GroupName']."</option>"; 
     }     
    }
  }  
?>