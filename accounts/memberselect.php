<?php	    
	include("accounts_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $group = $_POST['group'];
        
        $sql2=mysqli_query($connection,"SELECT memid,memname FROM members WHERE memgroupid='$group' AND memid NOT IN (SELECT memid FROM acc_loan_dummy)") or die(mysqli_error($sql2));
		echo '<option></option>';
		while($row2 = mysqli_fetch_assoc($sql2)){																		
			echo "<option value = ".$row2['memid'].">".$row2['memname']."</option>"; 
		}
    }
?>