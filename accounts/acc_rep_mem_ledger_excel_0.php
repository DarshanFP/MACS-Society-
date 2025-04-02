<?php	  
	include("accounts_session.php");
	
    $y = $_POST['year'];
    $memid = $_POST['memid'];
    $nexty = $y + 1;
    $start = $y."-04-01";
    $end = $nexty."-03-31";

    $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
    $result1 = mysqli_query($connection, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
        
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
    $output = '';

    $output .= "<table class='table' style='font-family:verdana;'><tr>
        <td style='font-size:14px;' align='center' colspan='16'><b>Ledger of ".$row1['memname']."-".$memid." for the year ".$year."</b></td>
    </tr></table>";

    $output .="<table style='font-size:x-small' id='simple-table' border=1 class='table table-bordered table-hover'>
                <thead>
                    <tr>    
                        <th rowspan='2' style='text-align:center'>Month / Date</th>
                        <th colspan='3' style='text-align:center'>General Savings</th>
                        <th colspan='3' style='text-align:center'>Special Savings</th>
                        <th colspan='3' style='text-align:center'>Marriage Savings</th>
                        <th colspan='4' style='text-align:center'>Loan</th>
                        <th colspan='2' style='text-align:center'>Mutual Aid Fund</th>
                    </tr>
                    <tr>
                        <th>Received</th>
                        <th>Withdraw</th>
                        <th>Balance</th>
                        <th>Received</th>
                        <th>Withdraw</th>
                        <th>Balance</th>
                        <th>Received</th>
                        <th>Withdraw</th>
                        <th>Balance</th>
                        <th>Received</th>
                        <th>Interest</th>
                        <th>Payment</th>
                        <th>Balance</th>
                        <th>Received</th>
                        <th>Payment</th>
                    </tr>    
                </thead>                                                                            
                <tbody id='trows'>";
            
                while ($row3 = mysqli_fetch_assoc($result3)){
                    $GSob = $GSob+$row3['2']-$row3['General Savings'];
                    $SSob = $SSob+$row3['3']-$row3['Special Savings'];
                    $MSob = $MSob+$row3['4']-$row3['Marriage Savings'];
                    $GLob = $GLob+$row3['General Loan']-$row3['5'];
                    $output .= "<tr>";
                    $output .= "<td align='left'>".$row3['month']."</td>";
                    $output .= "<td align='center'>".$row3['2']."</td>";
                    $output .= "<td align='center'>".$row3['General Savings']."</td>";
                    $output .= "<td align='center'>".$GSob."</td>";
                    $output .= "<td align='center'>".$row3['3']."</td>";
                    $output .= "<td align='center'>".$row3['Special Savings']."</td>";
                    $output .= "<td align='center'>".$SSob."</td>";
                    $output .= "<td align='center'>".$row3['4']."</td>";
                    $output .= "<td align='center'>".$row3['Marriage Savings']."</td>";
                    $output .= "<td align='center'>".$MSob."</td>";
                    $output .= "<td align='center'>".$row3['5']."</td>";
                    $output .= "<td align='center'>".$row3['6']."</td>";
                    $output .= "<td align='center'>".$row3['General Loan']."</td>";
                    $output .= "<td align='center'>".$GLob."</td>";
                    $output .= "<td align='center'>".$row3['10']."</td>";
                    $output .= "<td align='center'>".$row3['Mutual Aid Fund']."</td>";
                    $output .= "</tr>";
                }
            
            $output .="</tbody></table>";

            header("Content-Type: application/xls, true");
            header("Content-Disposition:attachment; filename=$memid _YearReport_$year.xls");
            echo $output;
    
?>



