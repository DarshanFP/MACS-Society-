<?php	    
	include("pdt_session.php");
	if($_SERVER['REQUEST_METHOD'] == "POST"){
        $memid = $_POST['memid'];
        
        $sql1=mysqli_query($connection,"SELECT * FROM acc_mem_transfer_dummy WHERE memid = '$memid' AND status=1");
        $row1=mysqli_fetch_assoc($sql1);
        $tgroupid = $row1['TGroupID'];
        mysqli_query($connection,"START TRANSACTION");
        $sql2=mysqli_query($connection,"UPDATE members SET memgroupid='$tgroupid' WHERE memid = '$memid'");
        $sql3=mysqli_query($connection,"UPDATE acc_mem_transfer_dummy SET status=2 WHERE memid = '$memid'");
		if($sql2&&$sql3){
            mysqli_query($connection,"COMMIT");
        }
        else{
            mysqli_query($connection,"ROLLBACK");
        }
    }
?>