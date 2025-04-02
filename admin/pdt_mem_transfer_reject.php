<?php	    
	include("pdt_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $memid = $_POST['memid'];
        
        echo $sql2=mysqli_query($connection,"UPDATE acc_mem_transfer_dummy SET status=0 WHERE memid = '$memid'");
		
    }
?>