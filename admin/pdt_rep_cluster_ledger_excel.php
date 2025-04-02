<?php    
    include("pdt_session.php");
	$user = $_SESSION['login_user'];
    	
        $cluster = $_POST['cluster'];
        $month = $_POST['month'];
        $year = $_POST['year'];
           
        $month1 = $year.'-'.$month;       
        $obdate = $year.'-'.$month.'-01';
        $clustersql = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID = '$cluster'");
        $clustname = mysqli_fetch_assoc($clustersql);
        $clustername = $clustname['ClusterName'];

        $subheadq = mysqli_query($connection,"SELECT DISTINCT SubID,SubHead, SubHeadModule FROM acc_subhead,acc_cashbook WHERE SubID = subheadid AND clusterid = '$cluster' AND subheadid != 14 AND LEFT(date,7) = '$month1'");
        $subheadqcount = mysqli_num_rows($subheadq);

        $dateobj =DateTime::createFromFormat('!m',$month);
        $monthName = $dateobj->format('F'); 
                
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
        
        $finalq="select groupid,".$cstring."    
            from (select
                groupid,".$sstring."
            from (select
                A.*,".$casestring."
            from (SELECT groupid,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook WHERE clusterid = '$cluster' AND subheadid != 14 AND LEFT(date,7) = '$month1' GROUP BY subheadid,groupid) AS A) AS B GROUP BY groupid) AS V";
        
        $result3=mysqli_query($connection,$finalq);

        $output = '';

        $output .= "<table class='table' style='font-family:verdana;'>
							<tr>
								<td style='font-size:14px;' colspan=10><b>Ledger of ".$clustername." Cluster for ".$monthName."-".$year."</b></td>
							</tr></table>";
    
    $output .= '<table style="font-size:13px" style="font-family:verdana;"  border = "1">';																	
    $output .= '<thead id="theads1">';

        //$output .= "<tr>";
        $i = 0;
        $output .= "<th style='text-align: center;' rowspan='2'>GroupID</th>";
        $output .= "<th style='text-align: center;' rowspan='2'>GroupName</th>";
        while ($i <= ($subheadqcount-1)){
            if($subheadmodule[$i] == 3)
                $output .= "<th style='text-align: center;' colspan='3'>".$subhead[$i]."</th>";
            else if ($subheadmodule[$i] == 4)
                $output .= "<th style='text-align: center;' colspan='2'>".$subhead[$i]."</th>";
            else
                $output .= "<th style='text-align: center;' rowspan='2'>".$subhead[$i]."</th>"; 
            $i++; 
        }
        $output .= "</tr><tr>";
        $i=0;
        while ($i <= ($subheadqcount-1)){
            if($subheadmodule[$i] == 4 ){
                $output .= "<th style='text-align: center;'>Receipt</th>";
                $output .= "<th style='text-align: center;'>Total</th>";
            }else if($subheadmodule[$i] == 3){
                $output .= "<th style='text-align: center;'>Receipt</th>";
                $output .= "<th style='text-align: center;'>Payment</th>";
                $output .= "<th style='text-align: center;'>Total</th>";
            }
            $i++;
        } 
        $output .= "</tr>";
                
        while ($row3 = mysqli_fetch_assoc($result3)){
            $x = 'A';
            $output .= "<tr>";
            $output .= "<td align='left'>".$row3['groupid']."</td>";
            $groupid = $row3['groupid'];
            $groupq = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID = '$groupid'");
            $groupname = mysqli_fetch_assoc($groupq);
            $output .= "<td align='left'>".$groupname['GroupName']."</td>";
            $j = 0;
            $i = 0;            
            while ($j <= ($subheadqcount)){
                if($subheadmodule[$i] == 4 ){
                    $output .= "<td align='right'>".$row3[$x]."</td>";
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
                    $output .= "<td align='right'>".number_format($ledtot,2, '.', '')."</td>";                    
                }
                else if($subheadmodule[$i] == 3 ){
                    $output .= "<td align='right'>".$row3[$x]."</td>";
                    $total[$j] =  $total[$j] + $row3[$x];
                    $receipt = $row3[$x];

                    $loanno = mysqli_query($connection, "SELECT loanno FROM acc_loans WHERE memid = '$memid' AND subheadid = '$subid[$i]'");
                    $loanno = mysqli_fetch_assoc($loanno);
                    echo $loanno = $loanno['loanno'];
                    $ledgerobs = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions 
                                WHERE accno = '$loanno' AND subheadid = '$subid[$i]' AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1
                                AND date < '$obdate' GROUP BY accno");  
                    $ledgerobs = mysqli_fetch_assoc($ledgerobs);
                    $ledobs = $ledgerobs['pcash'] + $ledgerobs['padj'] - $ledgerobs['rcash'] - $ledgerobs['radj'];

                    $x++;
                    $j++;
                    $i++;
                    $output .= "<td align='right'>".$row3[$x]."</td>";
                    $total[$j] =  $total[$j] + $row3[$x];
                    $payment = $row3[$x];

                    
                    $ledtot = $ledobs - $receipt + $payment;
                    $ledtotgrand[$i] = $ledtotgrand[$i] + $ledtot;
                    $output .= "<td align='right'>".number_format($ledtot,2, '.', '')."</td>";
                }
                else{
                    $output .= "<td align='right'>".$row3[$x]."</td>";
                    $total[$j] =  $total[$j] + $row3[$x];
                }
                $x++;
                $j++;
                $i++;
            } 
            $output .= "</tr>";
            
        }
        $j = 0;
        $output .= "<tr>";
        $output .= "<td align='left' colspan='2'>Total</td>";
        while ($j <= ($subheadqcount)){            
            if($subheadmodule[$j] == 3 ){
                    $output .= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
                    $j++;
                    $output .= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
                    
                    $output .= "<td align='right'>".number_format($ledtotgrand[$j],2, '.', '')."</td>";
                }
            else if ($subheadmodule[$j] == 4 ){
                    $output .= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
                    
                    $output .= "<td align='right'>".number_format($ledtotgrand[$j],2, '.', '')."</td>";
            }
            else{
                    $output .= "<td align='right'>".number_format($total[$j],2, '.', '')."</td>";
            }
            $j++;
        }
        $output .= "</tr>";
                   
        $output .='</tbody>
    $output .= </table>';																			
    header("Content-Type: application/xls, true");
    header("Content-Disposition:attachment; filename=.$clustername._Ledger_$monthName-$year.xls");
    echo $output;
?>    