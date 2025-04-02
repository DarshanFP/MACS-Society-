<?php 
  include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	$user = $_SESSION['login_user']; 
	if(isset($_GET['id'])) {
		$id = $_GET["id"];
   
    
	
	mysqli_query($connection,"start transaction");	
  
 	 
	//Cash Book Entry Start			
  
  $sql4 = mysqli_query($connection,"DELETE FROM acc_loan_dummy WHERE id = '$id'");
	//Cash Book Entry End
    
  
	
	if($sql4){
		mysqli_query($connection,"commit");
	}
	else{
		mysqli_query($connection,"rollback");
    }
}  
 header("location:accounts_loans.php");   


?>