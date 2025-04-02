<?php 
    include("accounts_session.php");
	$_SESSION['curpage']="accounts_loan";
    include("accountssidepan.php");
	$user = $_SESSION['login_user']; 
	if(isset($_GET['clustpropid'])){
        $clustpropid = $_GET['clustpropid'];
                 
        $clustupdate = mysqli_query($connection,"UPDATE clusterloanprop SET status = 4 WHERE ClusterPropID = '$clustpropid'");     
    
        header("location:acc_loan_prop_view.php?clustpropid=$clustpropid");   
    }         
?>