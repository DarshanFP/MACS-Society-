<?php 
    include("accounts_session.php");
	$_SESSION['curpage']="accounts_loan";
    include("accountssidepan.php");
	$user = $_SESSION['login_user']; 
	if($_SERVER['REQUEST_METHOD'] == "POST") {
         echo $memid = $_POST['member'];         
         echo $groupid = $_POST['group'];         
         echo $clusterid = $_POST['clusterid'];         
         echo $deposit = $_POST['balance'];         
         echo $subid = $_POST['loantype'];         
         echo $loanbalance = $_POST['loanbalance'];         
         echo $proposedloan = $_POST['proposedloan'];                 
         echo $loanins = $_POST['loanins'];        
    }
    
        mysqli_query($connection,"start transaction");	
        $sql1 = mysqli_query($connection,"SELECT loanno FROM acc_loans WHERE memid='$memid' AND subheadid='$subid' AND status=1");
        $row1 = mysqli_fetch_assoc($sql1);
        echo $loanno = $row1['loanno'];         
        //Cash Book Entry Start			
    
        $sql4 =  mysqli_query($connection,"INSERT INTO acc_loan_dummy (`memid`, `GroupID`, `ClusterID`, `deposit`,`subid`,`loanno`,`loanbalance`, `proposedloan`, `proposedins`,`status`)
                            VALUES('$memid','$groupid','$clusterid', '$deposit','$subid','$loanno','$loanbalance', '$proposedloan','$loanins',0)");
        
        //Cash Book Entry End
        
        if($sql4){
            mysqli_query($connection,"commit");                
        }
        else{
            mysqli_query($connection,"rollback");
            echo 0;
        }
    
      header("location:accounts_loans.php");   
        
?>