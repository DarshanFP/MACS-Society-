<?php	    
	include("accounts_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $memid = $_POST['memid'];
        
        echo $sql2=mysqli_query($connection,"DELETE FROM acc_mem_transfer_dummy WHERE memid = '$memid' AND status=1");
		
    }
?>