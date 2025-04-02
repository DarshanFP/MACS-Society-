<?php 
	  include("accounts_session.php");		
        $shortname = mysqli_query($connection,"SELECT shortform FROM master");
        $shortnameresult = mysqli_fetch_assoc($shortname);
        $macsshortname = $shortnameresult['shortform']; 
		$memid = $_POST['memid'];
        $memid = "M".$macsshortname.$memid;
		$nomemid = 'Member ID does not exits';
		$sql = "SELECT * FROM members WHERE memid = '$memid'";
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		$count = mysqli_num_rows($result);
        echo $count;
		/*if($count == 1){
			echo $row['memname'];			
		}
		if($count == 0) {
				echo $nomemid;
		}*/			
?>