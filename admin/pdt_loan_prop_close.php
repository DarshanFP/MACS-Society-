<?php 
    include("pdt_session.php");
	$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");
	$user = $_SESSION['login_user']; 
	if(isset($_GET['macspropid'])){
        $macspropid = $_GET['macspropid'];
                 
        $macsupdate = mysqli_query($connection,"UPDATE macsloanprop SET status = 4 WHERE MacsPropID = '$macspropid'");     
    
        header("location:pdt_loan_prop_bank_view.php?macspropid=$macspropid");   
    }         
?>