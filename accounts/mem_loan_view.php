<?php 
	  include("accounts_session.php");		
		
		$loanno = $_POST['loan']; 		
		$memsql = mysqli_query($connection,"SELECT id,members.memid,memname,loanno,SubHead,memgroupid,GroupName FROM members,groups,acc_loans,acc_subhead WHERE members.memid=acc_loans.memid AND members.memgroupid=groups.GroupID AND acc_loans.subheadid=acc_subhead.SubID AND acc_loans.loanno = '$loanno' ORDER BY id DESC LIMIT 0,1");
		$memrow = mysqli_fetch_assoc($memsql);
		$count = mysqli_num_rows($memsql);

 		if($count < 1){
 			echo (json_encode('Account does not exists'));
 		}
 		else{
		  echo (json_encode($memrow));
		}
		
?>

		