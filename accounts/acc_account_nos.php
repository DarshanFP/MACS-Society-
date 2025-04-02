<?php 
include("accounts_session.php"); 
if($_SERVER['REQUEST_METHOD'] == "POST") {
   $subid = $_POST['subid'];
   $memid = $_POST['memid'];
   $status = $_POST['status'];
  
   $sql = mysqli_query($connection,"SELECT SubHeadModule FROM acc_subhead WHERE SubID = '$subid'");
   $row = mysqli_fetch_assoc($sql);
   if($row['SubHeadModule'] == 2){
     $sql6 = mysqli_query($connection,"SELECT acc_sharecapital.memid, sum(receiptcash) as creceipt,sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                    FROM acc_sharecapital, acc_cashbook, acc_transactions WHERE acc_sharecapital.memid = '$memid' and acc_sharecapital.subheadid = '$subid' 
                                    AND acc_sharecapital.memid = acc_cashbook.accno AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = 1");
     $count6 = mysqli_num_rows($sql6);
     if($count6>0){
       $row6 = mysqli_fetch_assoc($sql6);       
       $balance = $row6['creceipt'] + $row6['areceipt'] - $row6['cpayment'] - $row6['apayment'];
        echo $memid."*".$balance; 
     }
     else{
       echo $memid."*".''; 
     }
      
    }
   else if($row['SubHeadModule'] == 3){
     $sql1 = mysqli_query($connection,"SELECT acc_loans.loanno, sum(receiptcash) as creceipt,sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment  
                                    FROM acc_loans, acc_subhead, acc_cashbook, acc_transactions WHERE acc_subhead.SubHeadModule = 3 AND acc_subhead.SubID = acc_loans.subheadid AND acc_loans.memid = '$memid' 
                                    AND acc_loans.subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.accno = acc_loans.loanno 
                                    AND acc_loans.subheadid = acc_cashbook.subheadid AND acc_loans.status = 1");
     $count1 = mysqli_num_rows($sql1);
     $roiquery = mysqli_query($connection,"SELECT * FROM acc_rateofinterest WHERE subheadid = '$subid' AND status = 1");
     $roifetch = mysqli_fetch_assoc($roiquery);
     
     if($count1>0){
        $row1 = mysqli_fetch_assoc($sql1);       
        $balance =  $row1['cpayment'] + $row1['apayment'] - $row1['creceipt'] - $row1['areceipt'];
        echo $row1['loanno']."*".$balance."*".$roifetch['roi'];  
     }
     else {
       echo 0;
       echo "*Member doesn't have such loan";
     }
     
   }
   
   else if ($row['SubHeadModule'] == 4){
     $sql2 = mysqli_query($connection,"SELECT acc_deposits.depositno, sum(receiptcash) as creceipt,sum(receiptadj) as areceipt, sum(paymentcash) as cpayment, sum(paymentadj) as apayment 
                                    FROM acc_deposits, acc_subhead, acc_cashbook, acc_transactions WHERE acc_subhead.SubHeadModule = 4 AND acc_subhead.SubID = acc_deposits.subheadid AND acc_deposits.memid = '$memid' 
                                    AND acc_deposits.subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.accno = acc_deposits.depositno 
                                    AND acc_deposits.subheadid = acc_cashbook.subheadid AND acc_deposits.status = 1");
     $count2 = mysqli_num_rows($sql2);
     $roiquery = mysqli_query($connection,"SELECT * FROM acc_rateofinterest WHERE subheadid = '$subid' AND status = 1");
     $roifetch = mysqli_fetch_assoc($roiquery);
     if($count2>0){
        $row2 = mysqli_fetch_assoc($sql2);
        $balance = $row2['creceipt'] + $row2['areceipt'] - $row2['cpayment'] - $row2['apayment'];
        echo $row2['depositno']."*".$balance."*".$roifetch['roi'];    
     }    
     else{
       echo 0;
        echo "*Member doesn't have such deposit";  
     }
     
   }
  else if($row['SubHeadModule'] == 8){
     $sql5 = mysqli_query($connection,"SELECT loanno FROM acc_loans WHERE memid = '$memid' AND status = 1 AND loanno NOT IN (SELECT accno FROM acc_cashbook_dummy, acc_subhead WHERE acc_cashbook_dummy.subheadid = acc_subhead.SubID AND acc_subhead.SubHeadModule = 8 AND memid = '$memid')");
     $count5 = mysqli_num_rows($sql5);
     if($count5>0){
       echo "true*";              
       echo "<option></option>";
       while($row5 = mysqli_fetch_assoc($sql5)){
        echo "<option>".$row5['loanno']."</option>";
       }
     }
     else{
        echo 0;
        echo "*No Loans to collect interest";  
     }
    } 

    else if($row['SubHeadModule'] == 9){
     $sql5 = mysqli_query($connection,"SELECT depositno FROM acc_deposits WHERE memid = '$memid' AND status = 1 AND depositno NOT IN (SELECT accno FROM acc_payment_dummy, acc_subhead WHERE acc_payment_dummy.subheadid = acc_subhead.SubID AND acc_subhead.SubHeadModule = 9 AND memid = '$memid')");
     $count5 = mysqli_num_rows($sql5);
     if($count5>0){
       echo "true*";              
       echo "<option></option>";
       while($row5 = mysqli_fetch_assoc($sql5)){
        echo "<option>".$row5['depositno']."</option>";
       }
     }
     else{
        echo 0;
        echo "*No Deposits to pay interest";  
     }
    } 

    else if($row['SubHeadModule'] == 11 && $status =="payment"){
     $othinc = mysqli_query($connection,"SELECT Amount FROM acc_loan_income WHERE MemID = '$memid' AND SubID = '$subid' AND status = 0");
     $countothinc = mysqli_num_rows($othinc);
     if($countothinc>0){
        $dataothinc = mysqli_fetch_assoc($othinc);
        
        echo $memid."*".$dataothinc['Amount'];  
     }    
     else{
       echo 0;
        echo "*Member doesn't have such charges";  
     }
    } 

  else{
      echo 1;
    }
 
}  
?>