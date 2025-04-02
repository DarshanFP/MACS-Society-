<?php 
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_loans";
    include("accountssidepan.php");	

	if(isset($_GET['id'])) {
       $id = $_GET['id'];
       $clustpropid = $_GET['clustpropid'];
       $loanaccept = mysqli_query($connection,"UPDATE acc_loan_dummy SET status = 6 WHERE id = '$id'");                 
        
    }
    
            
       header("location:acc_loan_prop_view.php?clustpropid=".$clustpropid);   
        
?>