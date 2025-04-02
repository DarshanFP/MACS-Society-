<?php	    
	include("accounts_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $subid = $_POST['loantype'];
        $memid = $_POST['memid'];
        $sql2=mysqli_query($connection,"SELECT cb FROM acc_loans WHERE memid='$memid' AND subheadid = '$subid' AND status=1 ") or die(mysqli_error($sql2));
		
		while($row2 = mysqli_fetch_assoc($sql2)){																		
			echo  $row2['cb'];
		}
    }
?>