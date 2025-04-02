<?php
 	include("accounts_session.php");
    include("accountssidepan.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
			$subid = $_POST['subid'];
			$details = $_POST['details'];
			$debit = $_POST['debit'];
			$credit = $_POST['credit'];		
    
            $debit = (float)$debit;
            $credit = (float)$credit;
            echo $today." ";
            echo $clusterid." ";
            echo $subid." ";
            echo $details." ";
            echo $credit." ";
            echo $debit." ";
		
			date_default_timezone_set('Asia/Kolkata');
 			$timedate = date('Y-m-d H:i:s', time());
			
			$today = $_SESSION['backdate'];
		 	if($debit > 0 ||  $credit >0){ 
		 		$sql = mysqli_query($connection,"INSERT INTO acc_cashbook_dummy (date, clusterid, subheadid, details, receiptadj, paymentadj, remarks, entryempid, timedate)
			 												VALUES ('$today','$clusterid','$subid', '$details','$credit', '$debit', '$details','$user','$timedate')") or die(mysqli_error($connection));
			    echo $sql;
            }
						
			mysqli_close($connection);
			header("location:acc_cashbook_adj.php");	
	}
?>