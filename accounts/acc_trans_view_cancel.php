<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_trans";
	$user = $_SESSION['login_user'];

    $sql1 = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $row1 = mysqli_fetch_assoc($sql1);

    $clusterid = $row1['ClusterID'];
	
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $transid = $_POST['transid'];
        $reasons = $_POST['reasons'];
        $sql1 = mysqli_query($connection, "SELECT acc_transactions.* FROM acc_transactions,acc_cashbook WHERE acc_transactions.TransID = acc_cashbook.TransID AND acc_transactions.TransID = '$transid' AND TransStatus=1 AND clusterid = '$clusterid'");
        $count1 = mysqli_num_rows($sql1);
        $row1 = mysqli_fetch_assoc($sql1);
        $transstatus =  $row1['TransStatus'];
        $cancelstatus = $row1['CancelStatus'];
        if($cancelstatus==0 || $cancelstatus==3){
            $sql2 = mysqli_query($connection, "UPDATE acc_transactions SET CancelStatus = 1, TransRemarks = '$reasons' WHERE TransID = '$transid'") or die(mysqli_error($sql1));
        }
        echo $cancelstatus; 
    }
?>

						