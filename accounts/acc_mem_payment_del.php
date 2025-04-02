<?php 
  include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	$user = $_SESSION['login_user']; 
	if(isset($_GET['id'])) {
		$id = $_GET["id"];
    $sql3 = mysqli_query($connection,"SELECT memid FROM acc_payment_dummy WHERE id = '$id'");
    $row3 = mysqli_fetch_assoc($sql3);
    $memid = $row3['memid'];
    
	
	mysqli_query($connection,"start transaction");	
  
 	 
	//Cash Book Entry Start			
  
  $sql4 = mysqli_query($connection,"DELETE FROM acc_payment_dummy WHERE id = '$id'");
	//Cash Book Entry End
    
  
	
	if($sql4){
		mysqli_query($connection,"commit");
	}
	else{
		mysqli_query($connection,"rollback");
	}
 header("location:acc_mem_payment.php?memid=$memid");   
}

?>