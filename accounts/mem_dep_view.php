<?php 
	  include("accounts_session.php");		
		
		$depno = $_POST['depno']; 		
		$memsql = mysqli_query($connection,"SELECT id,members.memid,memname,depositno,SubHead,memgroupid,GroupName FROM members,groups,acc_deposits,acc_subhead WHERE members.memid=acc_deposits.memid AND members.memgroupid=groups.GroupID AND acc_deposits.subheadid=acc_subhead.SubID AND acc_deposits.depositno = '$depno' ORDER BY id DESC LIMIT 0,1");
		$memrow = mysqli_fetch_assoc($memsql);
		$count = mysqli_num_rows($memsql);

 		if($count < 1){
 			echo (json_encode('Account does not exists'));
 		}
 		else{
		  echo (json_encode($memrow));
		}
		
?>

		