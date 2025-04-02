<?php
	include("accounts_session.php");
 		
 	
    
	if($_SERVER["REQUEST_METHOD"] == "GET"){
		$cashbookid = $_GET['id'];		
		
		$sql = "DELETE FROM acc_cashbook_dummy WHERE id = '$cashbookid'";
		$sql = mysqli_query($connection,$sql);		

 		mysqli_close($connection);
		
		header("location:acc_cashbook_adj.php");
	}
	
	?>