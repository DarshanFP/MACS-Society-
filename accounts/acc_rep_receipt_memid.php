<?php	    
	include("accounts_session.php");
	
    $user = $_SESSION['login_user'];
    $transid = $_POST['transid'];      

    $memquery = mysqli_query($connection, "SELECT memid FROM acc_cashbook WHERE TransID = '$transid' AND memid IS NOT NULL");
    $count = mysqli_num_rows($memquery);
    

	if($count>0){        
        while($row4 = mysqli_fetch_assoc($memquery)){                
            echo "<option value=".$row4['memid'].">".$row4['memid']."</option>";
        }
        echo "*";
        echo $count;

    }                                                                   
																		
?>