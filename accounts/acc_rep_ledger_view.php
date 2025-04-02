<?php	    
	include("accounts_session.php");
    $user = $_SESSION['login_user'];
    $subid = $_POST['subid'];
    $fromdate = $_POST['fdate'];
    $todate = $_POST['tdate'];

    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];

    $name = mysqli_query($connection,"SELECT * FROM master");
    $name = mysqli_fetch_assoc($name);  
    $macsshortname = $name['shortform'];
    $fullname = $name['name'];       

    $subheadquery = mysqli_query($connection, "SELECT SubHead FROM acc_subhead WHERE SubID = '$subid'");
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

    $mainidquery = mysqli_query($connection,"SELECT MainID FROM acc_subhead, acc_majorheads WHERE acc_subhead.SubID = '$subid' AND acc_subhead.MajorID = acc_majorheads.MajorID");
    $mainidfetch = mysqli_fetch_assoc($mainidquery);
    echo $mainid = $mainidfetch['MainID'];
	if($mainid == 1 || $mainid == 2){
        $queryob = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions
        WHERE subheadid = '$subid'  AND date < '$fromdate' AND clusterid = '$clusterid' AND  acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 GROUP BY subheadid" ); 
        $fetchob = mysqli_fetch_assoc($queryob);
        if($mainid == 1)
            $ob =  $fetchob['rcash'] + $fetchob['radj'] -  $fetchob['padj'] - $fetchob['pcash'];
        else
            $ob =  $fetchob['padj'] + $fetchob['pcash'] - $fetchob['rcash'] - $fetchob['radj'];
    }
    else{
        $ob = 0;
    }
    

    $finalquery = mysqli_query($connection,"SELECT date, sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions WHERE subheadid = '$subid'  AND clusterid = '$clusterid' AND   acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND date BETWEEN '$fromdate' AND '$todate' GROUP BY date ORDER BY date"); 
                
    $count = mysqli_num_rows($finalquery);

	if($count>0){
    $slno = 1;                      
    $rtotal = 0;
    $ptotal = 0;
        while($row4 = mysqli_fetch_assoc($finalquery)){
            $receipt = $row4['rcash'] + $row4['radj'];
            $payment = $row4['pcash'] + $row4['padj'];
            if($mainid == 1 || $mainid == 3 || $mainid == 5)
                $cb = $ob - $payment + $receipt;
            else 
                $cb = $ob + $payment - $receipt;
            echo "<tr><td class='center'>".$slno."</td>";            
            echo "<td class='center'>".date('d-m-Y',strtotime($row4['date']))."</td>";
            echo "<td align = 'right'>".number_format($ob,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($receipt,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($payment,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($cb,2,'.','')."</td></tr>";
            $rtotal = $rtotal + $receipt;
            $ptotal = $ptotal + $payment;
            $slno = $slno + 1;
            $ob = $cb;
        }
        echo "<tr><td colspan='3'></td>";
        echo "<td align= 'right'>".number_format($rtotal,2,'.','')."</td>";
        echo "<td align= 'right'>".number_format($ptotal,2,'.','')."</td>";
        echo "<td></td></tr>";
    }                                        
                                                                        
																			
																		
			