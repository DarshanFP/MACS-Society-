<?php	    
	include("accounts_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $memid = $_POST['memid'];
        
        $sql2=mysqli_query($connection,"SELECT sum(cb) FROM acc_deposits WHERE memid='$memid' AND status=1 GROUP BY memid") or die(mysqli_error($sql2));
		
		while($row2 = mysqli_fetch_assoc($sql2)){																		
			echo  $row2['sum(cb)'];
		}
    }
?>