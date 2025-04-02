<?php 
	include("pdt_session.php");
	$_SESSION['curpage']="president";
    include("pdtsidepan.php");	

	if(isset($_GET['id'])) {
       $id = $_GET['id'];
       $macspropid = $_GET['macspropid'];
       $loanaccept = mysqli_query($connection,"UPDATE acc_loan_dummy SET status = 3 WHERE id = '$id'");                 
        
    }
    
            
       header("location:pdt_loan_prop_view.php?macspropid=".$macspropid);   
        
?>