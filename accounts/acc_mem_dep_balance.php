<?php 
include("accounts_session.php"); 
if($_SERVER['REQUEST_METHOD'] == "POST") {
   $depositno = $_POST['depositno'];
   $subid = $_POST['subid'];
  
   
   $sql = mysqli_query($connection,"SELECT SubHeadModule FROM acc_subhead WHERE SubID = '$subid'");
   $row = mysqli_fetch_assoc($sql);
   
   
   if ($row['SubHeadModule'] == 4){
     $sql2 = mysqli_query($connection,"SELECT cb FROM acc_deposits WHERE depositno = '$depositno'") or die(mysqli_error($connection));
     $count2 = mysqli_num_rows($sql2);
     if($count2>0){
        $row2 = mysqli_fetch_assoc($sql2);        
        echo $row2['cb'];  
     }         
   }  
  else{
      echo 1;
    }
 
}  
?>