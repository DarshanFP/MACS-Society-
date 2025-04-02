<?php 
	include("accounts_session.php");
	$user = $_SESSION['login_user'];
    
	$office = TRIM($_POST['office']);
    $month = TRIM($_POST['month']);
    $year = TRIM($_POST['year']);
    $date = '01';
    $a_date = $year.'-'.$month.'-'.$date;
    $monthlastday = date("Y-m-t", strtotime($a_date)); //it returns the number of days in the month of a given date
 	
    $branch = mysqli_query($connection,"SELECT allot.BranchID, BranchName FROM allot, branch 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.BranchID = branch.BranchID");
    $bran = mysqli_fetch_assoc($branch);

    $branchid = $bran['BranchID'];

    $sql1 = mysqli_query($connection,"SELECT memid,memname FROM members WHERE memofficeid = '$office'");
    while($row1=mysqli_fetch_assoc($sql1)){
        $memid = $row1['memid'];
        $memname = $row1['memname'];
        $sql21 = mysqli_query($connection,"SELECT * FROM acc_sharecapital WHERE memid = '$memid' AND dateofopening <='$monthlastday' ORDER BY id DESC LIMIT 1");
        $row21 = mysqli_fetch_assoc($sql21);
        $sharecapital = $row21['balance'];
        $sql31 = mysqli_query($connection,"SELECT DISTINCT subheadid FROM acc_deposits WHERE memid = '$memid'");
        $deposits = 0;
        while($row31 = mysqli_fetch_assoc($sql31)){
            $sql32 = mysqli_query($connection,"SELECT * FROM acc_deposits WHERE memid = '$memid' AND dateofopening <='$monthlastday' ORDER BY id DESC LIMIT 1");
            $row32 = mysqli_fetch_assoc($sql32);
            $deposits = $deposits + $row32['cb'];
        }
        
        $sql41 = mysqli_query($connection,"SELECT DISTINCT subheadid FROM acc_loans WHERE memid = '$memid'");
        $loans = 0;
        while($row41 = mysqli_fetch_assoc($sql41)){
            $sql42 = mysqli_query($connection,"SELECT * FROM acc_loans WHERE memid = '$memid' AND dateofissue <='$monthlastday' ORDER BY id DESC LIMIT 1");
            $row42 = mysqli_fetch_assoc($sql42);
            $loans = $loans + $row42['cb'];
        }
        
        echo "<tr>";
        echo "<td align='center'>".$memid."</td>";
        echo "<td align='left'>".$memname."</td>";
        echo "<td align='center'>".$sharecapital."</td>";
        echo "<td align='center'>".$deposits."</td>";
        echo "<td align='center'>".$loans."</td>";
        echo "</tr>";
    }
?>