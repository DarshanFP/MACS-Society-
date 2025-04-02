<?php    
    include("pdt_session.php");
	$user = $_SESSION['login_user'];
    
	$group = $_POST['group'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $date = '01';
    $dateobj   = DateTime::createFromFormat('!m', $month);
    $monthname = $dateobj->format('F');
    $sql11 = mysqli_query($connection,"SELECT * FROM groups WHERE GroupID='$group'");
    $row11 = mysqli_fetch_assoc($sql11);
    $groupname = $row11['GroupName'];  
    $a_date = $year.'-'.$month.'-'.$date;
    $monthlastday = date("Y-m-t", strtotime($a_date)); //t returns the number of days in the month of a given date
 	
    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];
    $clustername = $clus['ClusterName'];

    $month1 = $year.'-'.$month;       
    $obdate = $year.'-'.$month.'-01';

               
    $subheadq = mysqli_query($connection,"SELECT DISTINCT SubID,SubHead, SubHeadModule FROM acc_subhead,acc_cashbook WHERE SubID = subheadid AND groupid = '$group' AND subheadid != 14 AND LEFT(date,7) = '$month1'");
    $subheadqcount = mysqli_num_rows($subheadq);
        
         
    $i = 0;
    $cstring = '';
    $sstring = '';
    $casestring = '';
    
    $x = 'A';
    while($row2 = mysqli_fetch_assoc($subheadq)){
        $subid[$i] = $row2['SubID'];
        $subhead[$i] = $row2['SubHead'];
        $subheadmodule[$i] = $row2['SubHeadModule'];
        
        if($i < ($subheadqcount-1)){
            if($subheadmodule[$i] != 3){
                $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                $sstring .= "sum(".$x.") as ".$x.",";
                $casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$x.",";
            }
            else{
                $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                $sstring .= "sum(".$x.") as ".$x.",";
                $casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$x.",";
                $x++;
                $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                $sstring .= "sum(".$x.") as ".$x.",";
                $casestring .= "case when subhead = '".$subid[$i]."' then payment end as ".$x.",";
            }
            $x++;
            
        }else{
            if($subheadmodule[$i] != 3){
                $cstring .= "coalesce(".$x.", 0) as ".$x."";
                $sstring .= "sum(".$x.") as ".$x."";
                $casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$x."";
            }
            else{
                $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                $sstring .= "sum(".$x.") as ".$x.",";
                $casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$x.",";
                $x++;
                $cstring .= "coalesce(".$x.", 0) as ".$x."";
                $sstring .= "sum(".$x.") as ".$x.""; 
                $casestring .= "case when subhead = '".$subid[$i]."' then payment end as ".$x."";
            }
            $x++;    
        }
        $i++;
    } 
    
    $finalq="select memid,".$cstring."    
        from (select
            memid,".$sstring."
        from (select
            A.*,".$casestring."
        from (SELECT memid,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook WHERE groupid = '$group' AND subheadid != 14 AND LEFT(date,7) = '$month1' GROUP BY subheadid,memid) AS A) AS B GROUP BY memid) AS V";
    
    $result3=mysqli_query($connection,$finalq);
    $output = '';

    $output.= "<table style='font-family:verdana; font-size:14px;'>
                <tbody>
                    <tr>";
    $output.= "<td colspan='14' align='center'><b>Cluster Name:".$clustername."</b></td></tr>";
    $output.= "<tr><td colspan='14' align='center'><b>Group Name:".$groupname."</b></td></tr><tbody></table>";

    $output.= "<table border='1' style='font-family:verdana; font-size:12px;'><thead><tr>";
    $i = 0;
    $output.= "<th style='text-align: center;' rowspan='2'>MemID</th>";
    $output.= "<th style='text-align: center;' rowspan='2'>MemberName</th>";
    while ($i <= ($subheadqcount-1)){
        if($subheadmodule[$i] == 3)
            $output.= "<th style='text-align: center;' colspan='3'>".$subhead[$i]."</th>";
        else if ($subheadmodule[$i] == 4)
            $output.= "<th style='text-align: center;' colspan='2'>".$subhead[$i]."</th>";
        else
            $output.= "<th style='text-align: center;' rowspan='2'>".$subhead[$i]."</th>"; 
        $i++; 
    }
    $output.= "</tr><tr>";
    $i=0;
    while ($i <= ($subheadqcount-1)){
        if($subheadmodule[$i] == 4 ){
            $output.= "<th style='text-align: center;'>Receipt</th>";
            $output.= "<th style='text-align: center;'>Total</th>";
        }else if($subheadmodule[$i] == 3){
            $output.= "<th style='text-align: center;'>Receipt</th>";
            $output.= "<th style='text-align: center;'>Payment</th>";
            $output.= "<th style='text-align: center;'>Total</th>";
        }
        $i++;
    } 
    $output.= "</tr></thead><tbody>";
    
    
    while ($row3 = mysqli_fetch_assoc($result3)){
        $x = 'A';
        $output.= "<tr>";
        $output.= "<td align='left'>".$row3['memid']."</td>";
        $memid = $row3['memid'];
        $memq = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$memid'");
        $memname = mysqli_fetch_assoc($memq);
        $output.= "<td align='left'>".$memname['memname']."</td>";
        $j = 0;
        $i = 0;            
        while ($j <= ($subheadqcount)){
            if($subheadmodule[$i] == 4 ){
                $output.= "<td align='right'>".$row3[$x]."</td>";
                $total[$j] =  $total[$j] + $row3[$x];

                $depno = mysqli_query($connection, "SELECT depositno FROM acc_deposits WHERE memid = '$memid' AND subheadid = '$subid[$i]'");
                $depno = mysqli_fetch_assoc($depno);
                $depno = $depno['depositno'];
                $ledgerobs = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions 
                            WHERE accno = '$depno' AND subheadid = '$subid[$i]' AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1
                            AND date < '$obdate' GROUP BY accno");  
                $ledgerobs = mysqli_fetch_assoc($ledgerobs);
                $ledobs = $ledgerobs['rcash'] + $ledgerobs['radj'] - $ledgerobs['pcash'] - $ledgerobs['padj'];

                $ledtot = $ledobs + $row3[$x];
                $ledtotgrand[$i] = $ledtotgrand[$i] + $ledtot;
                $output.= "<td align='right'>".number_format($ledtot,2, '.', '')."</td>";                    
            }
            else if($subheadmodule[$i] == 3 ){
                $output.= "<td align='right'>".$row3[$x]."</td>";
                $total[$j] =  $total[$j] + $row3[$x];
                $receipt = $row3[$x];

                $loanno = mysqli_query($connection, "SELECT loanno FROM acc_loans WHERE memid = '$memid' AND subheadid = '$subid[$i]'");
                $loanno = mysqli_fetch_assoc($loanno);
                $loanno = $loanno['loanno'];
                $ledgerobs = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions 
                            WHERE accno = '$loanno' AND subheadid = '$subid[$i]' AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1
                            AND date < '$obdate' GROUP BY accno");  
                $ledgerobs = mysqli_fetch_assoc($ledgerobs);
                $ledobs = $ledgerobs['pcash'] + $ledgerobs['padj'] - $ledgerobs['rcash'] - $ledgerobs['radj'];

                $x++;
                $j++;
                $i++;
                $output.= "<td align='right'>".$row3[$x]."</td>";
                $total[$j] =  $total[$j] + $row3[$x];
                $payment = $row3[$x];

                
                $ledtot = $ledobs - $receipt + $payment;
                $ledtotgrand[$i] = $ledtotgrand[$i] + $ledtot;
                $output.= "<td align='right'>".number_format($ledtot,2, '.', '')."</td>";
            }
            else{
                $output.= "<td align='right'>".$row3[$x]."</td>";
                $total[$j] =  $total[$j] + $row3[$x];
            }
            $x++;
            $j++;
            $i++;
        } 
        $output.= "</tr>";
        
    }
    $j = 0;
    $output.= "<tr>";
    $output.= "<td align='left' colspan='2'>Total</td>";
    while ($j <= ($subheadqcount)){            
        if($subheadmodule[$j] == 3 ){
                $output.= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
                $j++;
                $output.= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
                
                $output.= "<td align='right'>".number_format($ledtotgrand[$j],2, '.', '')."</td>";
            }
        else if ($subheadmodule[$j] == 4 ){
                $output.= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
                
                $output.= "<td align='right'>".number_format($ledtotgrand[$j],2, '.', '')."</td>";
        }
        else{
                $output.= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
        }
        $j++;
    }
    $output.= "</tr></tbody></table>";
    header("Content-Type: application/xls, true");
	header("Content-Disposition:attachment; filename=$groupname _GroupReport-$monthname _$year.xls");
    echo $output;
?>    