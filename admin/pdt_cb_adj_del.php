<?php
	include("pdt_session.php");
 		
 	
    
	if($_SERVER["REQUEST_METHOD"] == "GET"){
		$cashbookid = $_GET['id'];		
		
		$sql = "DELETE FROM acc_cashbook_dummy WHERE id = '$cashbookid'";
		$sql = mysqli_query($connection,$sql);		

 		mysqli_close($connection);
		
		header("location:pdt_cashbook_adj.php");
	}
	
	?>