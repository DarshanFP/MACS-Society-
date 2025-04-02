<?php	    
	include("accounts_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $transid = TRIM($_POST['transid']);
        
        $sql2=mysqli_query($connection,"UPDATE acc_transactions SET TransRemarks = NULL, CancelStatus = NULL WHERE TransID = '$transid'");
		
    }
?>