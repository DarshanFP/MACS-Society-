<?php 
	include("pdt_session.php");
	$user = $_SESSION['login_user'];
    if($_SERVER['REQUEST_METHOD']=='POST'){	
        $y = $_POST['year'];
        $memid = TRIM($_POST['memid']);
           
        $nexty = $y + 1;
        $start = $y."-04-01";
        $end = $nexty."-03-31"; 
               
        $sql3 ="SELECT month,
                        max(case when subhead = 2 then receipt else 0 end) '2',
                        max(case when subhead = 2 then payment else 0 end) 'General Savings',
                        max(case when subhead = 3 then receipt else 0 end) '3',
                        max(case when subhead = 3 then payment else 0 end) 'Special Savings',
                        max(case when subhead = 4 then receipt else 0 end) '4',
                        max(case when subhead = 4 then payment else 0 end) 'Marriage Savings',
                        max(case when subhead = 5 then receipt else 0 end) '5',
                        max(case when subhead = 5 then payment else 0 end) 'General Loan',
                        max(case when subhead = 6 then receipt else 0 end) '6',
                        max(case when subhead = 6 then payment else 0 end) 'Interest Received on Loans',
                        max(case when subhead = 10 then receipt else 0 end) '10',
                        max(case when subhead = 10 then payment else 0 end) 'Mutual Aid Fund'
                FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment 
                      FROM acc_cashbook,acc_transactions 
                      WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' GROUP BY subheadid,month) AS A GROUP BY month 
                      ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";

        $result3=mysqli_query($connection,$sql3);
        
        $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                 FROM acc_subhead,acc_cashbook,acc_transactions 
                                                 WHERE SubID=subheadid AND SubID IN (2,3,4,5,6,10) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memid = '$memid' GROUP BY subheadid");
        $GSob=0;
        $SSob=0;
        $MSob=0;
        $GLob=0;
        while ($rowobs = mysqli_fetch_assoc($ledgerobs)){

            if($rowobs['subheadid'] == 2){
                $GSob = $rowobs['rcash'] + $rowobs['radj'] - $rowobs['pcash'] - $rowobs['padj'];
            }
            elseif($rowobs['subheadid'] == 3){
                $SSob = $rowobs['rcash'] + $rowobs['radj'] - $rowobs['pcash'] - $rowobs['padj'];
            }
            elseif($rowobs['subheadid'] == 4){
                $MSob = $rowobs['rcash'] + $rowobs['radj'] - $rowobs['pcash'] - $rowobs['padj'];
            }
            elseif($rowobs['subheadid'] == 5){
                $GLob = $rowobs['pcash'] + $rowobs['padj'] - $rowobs['rcash'] - $rowobs['radj'];
            }
        }

        while ($row3 = mysqli_fetch_assoc($result3)){
            $GSob = $GSob+$row3['2']-$row3['General Savings'];
            $SSob = $SSob+$row3['3']-$row3['Special Savings'];
            $MSob = $MSob+$row3['4']-$row3['Marriage Savings'];
            $GLob = $GLob+$row3['General Loan']-$row3['5'];
            echo "<tr>";
            echo "<td align='left'>".$row3['month']."</td>";
            echo "<td align='left'>".$row3['2']."</td>";
            echo "<td align='left'>".$row3['General Savings']."</td>";
            echo "<td align='left'>".$GSob."</td>";
            echo "<td align='left'>".$row3['3']."</td>";
            echo "<td align='left'>".$row3['Special Savings']."</td>";
            echo "<td align='left'>".$SSob."</td>";
            echo "<td align='left'>".$row3['4']."</td>";
            echo "<td align='left'>".$row3['Marriage Savings']."</td>";
            echo "<td align='left'>".$MSob."</td>";
            echo "<td align='left'>".$row3['5']."</td>";
            echo "<td align='left'>".$row3['6']."</td>";
            echo "<td align='left'>".$row3['General Loan']."</td>";
            echo "<td align='left'>".$GLob."</td>";
            echo "<td align='left'>".$row3['10']."</td>";
            echo "<td align='left'>".$row3['Mutual Aid Fund']."</td>";
            echo "</tr>";
        }
    }
?>