<?php	    
	include("accounts_session.php");
	
    $user = $_SESSION['login_user'];
    $headid = $_POST['headid'];
    $fromdate = $_POST['fdate'];
    $todate = $_POST['tdate'];    

    $name = mysqli_query($connection,"SELECT * FROM master");
    $name = mysqli_fetch_assoc($name);  
    $macsshortname = $name['shortform'];
    $fullname = $name['name'];       

    $subheadquery = mysqli_query($connection, "SELECT SubHead FROM acc_subhead WHERE SubID = '$headid'");
    $subheadfetch = mysqli_fetch_assoc($subheadquery);
    $subhead = $subheadfetch['SubHead'];


    echo '<div class="profile-info-row">
            <div class="profile-info-name"> Society Name </div>

            <div class="profile-info-value">
                <span class="editable" id="memid">'.$fullname.'</span>
            </div>
        </div>
        <div class="profile-info-row">
            <div class="profile-info-name"> Head Name </div>

            <div class="profile-info-value">
                <span class="editable" id="memname">'.$subhead.'</span>
            </div>
        </div>                      
        <div class="profile-info-row">
            <div class="profile-info-name"> Period </div>

            <div class="profile-info-value">
                <span class="editable" id="memname">'.date('d-m-Y',strtotime($fromdate)).' to '.date('d-m-Y',strtotime($todate)).'</span>
            </div>
        </div>';                           
    echo "*";
    
    
    $dcbquery = mysqli_query($connection,"SELECT * FROM (SELECT accno as obaccno, memid as obmemid, sum(receiptcash) as obrcash, sum(receiptadj) as obradj, sum(paymentcash) as obpcash, sum(paymentadj) as obpadj FROM acc_cashbook, acc_transactions, acc_subhead WHERE subheadid = '$headid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = acc_subhead.SubID AND date < '$fromdate'  GROUP BY accno, obmemid) as A
                                            LEFT JOIN (SELECT accno, memid, sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions, acc_subhead WHERE subheadid = '$headid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = acc_subhead.SubID AND date BETWEEN '$fromdate' AND '$todate' GROUP BY accno, memid) as B ON B.accno = A.obaccno
                                            UNION
                                            SELECT * FROM (SELECT accno as obaccno, memid as obmemid, sum(receiptcash) as obrcash, sum(receiptadj) as obradj, sum(paymentcash) as obpcash, sum(paymentadj) as obpadj FROM acc_cashbook, acc_transactions, acc_subhead WHERE subheadid = '$headid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = acc_subhead.SubID AND date < '$fromdate'  GROUP BY accno, obmemid) as A
                                            RIGHT JOIN (SELECT accno, memid, sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions, acc_subhead WHERE subheadid = '$headid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND acc_cashbook.subheadid = acc_subhead.SubID AND date BETWEEN '$fromdate' AND '$todate' GROUP BY accno, memid) as B ON B.accno = A.obaccno");     
    $count = mysqli_num_rows($dcbquery);   
    $mainid = mysqli_query($connection,"SELECT MainID FROM acc_majorheads, acc_subhead WHERE SubID = '$headid' AND acc_subhead.MajorID = acc_majorheads.MajorID");
    $mainfetch = mysqli_fetch_assoc($mainid);
    $main = $mainfetch['MainID'];
    

	if($count>0){
        $slno = 1;   
        $obtotal = 0;
        $ob = 0;
        $receipttotal = 0;
        $paymenttotal = 0;
        $cbtotal = 0;                              
        while($row4 = mysqli_fetch_assoc($dcbquery)){                

            if($main == 1)
                $ob = $row4['obrcash'] + $row4['obradj'] - $row4['obpcash'] - $row4['obpadj'];   
            else
                $ob = $row4['obpcash'] + $row4['obpadj'] - $row4['obrcash'] - $row4['obradj'];
            
            $receipt = $row4['rcash'] + $row4['radj'];
            $payment = $row4['pcash'] + $row4['padj'];   

            if($ob == 0 && $receipt == 00 && $payment == 0){

            }
            else{

            
            
                echo "<tr><td class='center'>".$slno."</td>";
                if(is_null($row4['obaccno']))
                    echo "<td class='center'>".$row4['accno']."</td>";
                else             
                    echo "<td class='center'>".$row4['obaccno']."</td>";
                $memid = $row4['memid'];
                $obmemid = $row4['obmemid'];
                if(!is_null($memid)){
                    $namequery = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$memid'");
                    $namefetch = mysqli_fetch_assoc($namequery);
                    $name = $namefetch['memname'];                
                }
                else if(!is_null($obmemid)){
                    $namequery = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$obmemid'");
                    $namefetch = mysqli_fetch_assoc($namequery);
                    $name = $namefetch['memname'];                
                }            
                    
                else{
                    $accno = $row4['accno'];
                    $namequery = mysqli_query($connection,"SELECT duesname FROM acc_dues WHERE duesid = '$accno'");
                    $namefetch = mysqli_fetch_assoc($namequery);
                    $name = $namefetch['duesname'];
                }
                echo "<td align='left'>".$name."</td>";

                echo "<td align='right'>".number_format($ob,2,'.','')."</td>";
                
                echo "<td align='right'>".number_format($receipt,2,'.','')."</td>";
                
                echo "<td align='right'>".number_format($payment,2,'.','')."</td>";
                if($main == 1)
                    $closing = $ob + $receipt - $payment;                                                      
                else
                    $closing = $ob - $receipt + $payment;                                                      
                echo "<td align='right'>".number_format($closing,2,'.','')."</td></tr>";
                $obtotal = $obtotal + $ob;
                $receipttotal =  $receipttotal + $receipt;
                $paymenttotal =  $paymenttotal + $payment;
                $cbtotal = $cbtotal + $closing;          
                $slno = $slno + 1;           
            }
        }
        echo "<tr class='center'><td colspan='3'><b>Total</b></td>";
        echo "<td align='right'><b>".number_format($obtotal,2,'.','')."</b></td>";
        echo "<td align='right'><b>".number_format($receipttotal,2,'.','')."</b></td>";
        echo "<td align='right'><b>".number_format($paymenttotal,2,'.','')."</b></td>";
        echo "<td align='right'><b>".number_format($cbtotal,2,'.','')."</b></td></tr>";
    }                                                                   
																		
?>