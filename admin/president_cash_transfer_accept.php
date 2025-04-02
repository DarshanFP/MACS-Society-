<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president";
	include("pdtsidepan.php");

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());

if(isset($_GET['cashid'])){	
	$cashid = $_GET['cashid'];
  $sql4 = mysqli_query($connection,"SELECT cluster.ClusterName, acc_cash_dummy_transfer.*
                          FROM cluster, acc_cash_dummy_transfer 
                          WHERE acc_cash_dummy_transfer.groupid = cluster.ClusterID 
                          AND acc_cash_dummy_transfer.CashTrID = '$cashid' 
                          AND acc_cash_dummy_transfer.clusterid = '$clusterid'");
  
	$result4 = mysqli_fetch_assoc($sql4);
  
  
  $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
  $transcount = mysqli_num_rows($trans);
  $transcount = 1001 + $transcount;	
  $transid = "T".$macsshortform.$transcount;
  
  mysqli_query($connection,"START TRANSACTION");

  $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`) 
                                VALUES ('$transid',1)") or die(mysqli_error($connection));
  
  $cluster1 = $result4['clusterid'];
  $cluster2 = $result4['groupid'];
  $subheadid = $result4['subheadid'];
  $details = $result4['details'];
  $receipt = $result4['receiptcash'];              
  $remarks = $cashid;   
  $cluster = $result4['ClusterName'];
  
  $cash2 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`, `subheadid`, `details`, `paymentcash`, `remarks`,`entryempid`)
          VALUES('$today','$cluster2','$cluster1', '$transid', '$subheadid', '$details','$receipt','$remarks','$user')") or die(mysqli_error($connection));	          
  
  $cash1 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`,`groupid`, `TransID`, `subheadid`, `details`, `receiptcash`, `remarks`,`entryempid`)
          VALUES('$today','$cluster1','$cluster2', '$transid', '$subheadid', '$details','$receipt','$remarks','$user')") or die(mysqli_error($connection));	          
  

  
  $cashaccept = mysqli_query($connection,"UPDATE acc_cash_dummy_transfer SET status = 1, TransID = '$transid' WHERE CashTrID = '$cashid' ");	
  

  
  $balance1 = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance + $receipt WHERE ClusterID = '$cluster1'");
  $balance2 = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance - $receipt WHERE ClusterID = '$cluster2'");
  
  if($transinsert && $cash1 && $cash2 && $cashaccept && $balance1 && $balance2) {
		 	mysqli_query($connection,"COMMIT");
	}
  else{
		mysqli_query($connection,"ROLLBACK");
	}
  header("location: president_cash_transfer_accept_suc.php?cashid=".$cashid);
  
}
?>
