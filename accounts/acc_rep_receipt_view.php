<?php	    
	include("accounts_session.php");
	
    $user = $_SESSION['login_user'];
    $recid = $_POST['recid'];
    //$memid = $_POST['memid'];    

    $name = mysqli_query($connection,"SELECT * FROM master");
    $name = mysqli_fetch_assoc($name);  
    $macsshortname = $name['shortform'];
    $fullname = $name['name'];       

    
    $datequery = mysqli_query($connection,"SELECT date, TransID, ReceiptID, memid FROM acc_cashbook WHERE ReceiptID = '$recid' Group BY ReceiptID");
    $datefetch = mysqli_fetch_assoc($datequery);
    $date = $datefetch['date'];
    $transid = $datefetch['TransID'];
    $recid = $datefetch['ReceiptID'];
    $memid = $datefetch['memid'];

    $namequery = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$memid'");
    $namefetch = mysqli_fetch_assoc($namequery);
    $name = $namefetch['memname']; 


     echo '<div class="profile-info-row">
            <div class="profile-info-name"> Receipt ID </div>

            <div class="profile-info-value">
                <span class="editable" id="recid">'.$recid.'</span>
            </div>
        </div>
     
        <div class="profile-info-row">
            <div class="profile-info-name"> Trans ID </div>

            <div class="profile-info-value">
                <span class="editable" id="memid">'.$transid.'</span>
            </div>
        </div>

        <div class="profile-info-row">
            <div class="profile-info-name"> Date </div>

            <div class="profile-info-value">
                <span class="editable" id="memid">'.$date.'</span>
            </div>
        </div>
     
        <div class="profile-info-row">
            <div class="profile-info-name"> Society Name </div>

            <div class="profile-info-value">
                <span class="editable" id="memid">'.$fullname.'</span>
            </div>
        </div>
        <div class="profile-info-row">
            <div class="profile-info-name"> Member ID </div>

            <div class="profile-info-value">
                <span class="editable" id="memname">'.$memid.'</span>
            </div>
        </div>                      
        <div class="profile-info-row">
            <div class="profile-info-name"> Member Name </div>

            <div class="profile-info-value">
                <span class="editable" id="memname">'.$name.'</span>
            </div>
        </div>';                           
    echo "*";
    
    
    $receiptquery = mysqli_query($connection,"SELECT SubHead, accno, receiptcash as rcash, receiptadj AS radj FROM acc_cashbook, acc_subhead WHERE subheadid = SubID AND ReceiptID = '$recid'");     
    $count = mysqli_num_rows($receiptquery);   
      

	if($count>0){
        $slno = 1;   
        $obtotal = 0;        
        $receiptcash = 0;
        $receiptadj = 0;
        $cbtotal = 0;                              
        while($row4 = mysqli_fetch_assoc($receiptquery)){                
            
            echo "<tr><td class='center'>".$slno."</td>";            
            echo "<td align='left'>".$row4['SubHead']."</td>";
            $receipt = $row4['rcash'] + $row4['radj'];
            echo "<td align='right'>".number_format($receipt,2,'.','')."</td>";
            
            $obtotal = $obtotal + $receipt;            
            $receiptcash = $receiptcash + $row4['rcash'];
            $receiptadj = $receiptadj + $row4['radj'];
            $slno = $slno + 1;                                                                                            
        }
        echo "<tr class='center'><td colspan='2'><b>Total</b></td>";
        echo "<td align='right'><b>".number_format($obtotal,2,'.','')."</b></td></tr>";
        if($receiptadj == 0)
            echo "<tr><td td colspan='3'>Received sum of Rs.".number_format($obtotal,2,'.','')." through cash</td></tr>";
        else
            echo "<tr><td td colspan='3'>Received sum of Rs.".number_format($obtotal,2,'.','')." through bank adjustment</td></tr>";
    }                                                                  
																		
?>