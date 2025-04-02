<?php
  include("pdt_session.php");
	$_SESSION['curpage']="president_cashbook";
	include("pdtsidepan.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
			$subid = $_POST['subid'];
			$details = $_POST['details'];
			$debit = $_POST['debit'];
			$credit = $_POST['credit'];		
    
      $debit = (float)$debit;
      $credit = (float)$credit;
      
		
			date_default_timezone_set('Asia/Kolkata');
 			$timedate = date('Y-m-d H:i:s', time());			
			
		 	if($debit > 0 ||  $credit >0){ 
		 		$sql = mysqli_query($connection,"INSERT INTO acc_cashbook_dummy (date, clusterid, subheadid, details, receiptadj, paymentadj, remarks, entryempid, timedate)
			 												VALUES ('$today','$clusterid','$subid', '$details','$credit', '$debit', '$details','$user','$timedate')") or die(mysqli_error($connection));
			echo $sql;
      }
			
			
			mysqli_close($connection);
			header("location:pdt_cashbook_adj.php");	
		
		
	
	}

 	



	?>