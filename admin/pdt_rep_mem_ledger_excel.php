<?php    
    include("pdt_session.php");
	$user = $_SESSION['login_user'];
    	
    $year = $_POST['year'];
    $memid = $_POST['memid'];
    $nexty = $year + 1;
    $start = $year."-04-01";
    $end = $nexty."-03-31";

    $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
    $result1 = mysqli_query($connection, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
               
    $sql2 = "SELECT DISTINCT SubID,SubHead, SubHeadModule, min(accno) FROM acc_subhead,acc_cashbook WHERE SubID = subheadid AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' GROUP BY SubID,SubHead, SubHeadModule ORDER BY SubHeadModule";
    $result2 = mysqli_query($connection,$sql2);
    $count2 = mysqli_num_rows($result2);
    //$output.= $count2;
    $i = 0;
    $cstring = '';
    $sstring = ''; 
    $casestring = '';
    
        
    $x = 'A';
    while($row2 = mysqli_fetch_assoc($result2)){
        
        $subid[$i] = $row2['SubID'];
        $previd = $subid[$i];
        $subhead[$i] = $row2['SubHead'];
        $subheadmodule[$i] = $row2['SubHeadModule'];
        $accno[$i] = $row2['min(accno)'];
                    
        if($i < ($count2-1)){
            //$cstring .= "coalesce(".$subhead[$i].", 0) as ".$subhead[$i].",";
            //$sstring .= "sum(".$subhead[$i].") as ".$subhead[$i].",";
            //$casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$subhead[$i].",";
            
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
        }
        else{
            //$cstring .= "coalesce(".$subhead[$i].", 0) as ".$subhead[$i]."";
            //$sstring .= "sum(".$subhead[$i].") as ".$subhead[$i]."";
            //$casestring .= "case when subhead = '".$subid[$i]."' then receipt end as ".$subhead[$i]."";
            
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
        
    $sql3="select month,".$cstring."    
        from (select
            month,".$sstring."
        from (select
            A.*,".$casestring."
        from (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
            WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
            GROUP BY subheadid,month) AS A) AS B GROUP BY month 
            ORDER BY FIELD(month,'April-".$year."','May-".$year."','June-".$year."','July-".$year."','August-".$year."','September-".$year."','October-".$year."','November-".$year."','December-".$year."','January-".$nexty."','February-".$nexty."','March-".$nexty."')) AS V";
    //$output.= $sql3;
    $result3=mysqli_query($connection,$sql3);
    $i = 0;
    while ($i <= ($count2-1)){
        $ledgerobs = mysqli_query($connection,"SELECT sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj FROM acc_cashbook, acc_transactions 
                            WHERE accno = '$accno[$i]' AND subheadid = '$subid[$i]' AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1
                            AND date < '$start' GROUP BY accno");  
        $ledgerobs = mysqli_fetch_assoc($ledgerobs);            
        if($subheadmodule[$i] == 3){
            $ledobs[$i] = $ledgerobs['pcash'] + $ledgerobs['padj'] - $ledgerobs['rcash'] - $ledgerobs['radj'];                
        }
        else if($subheadmodule[$i] == 4){
            $ledobs[$i] = $ledgerobs['rcash'] + $ledgerobs['radj'] - $ledgerobs['pcash'] - $ledgerobs['padj'];
        }             
        $i++;                
    }
    $output = '';

    $output .= "<table class='table' style='font-family:verdana;'>
							<tr>
								<td style='font-size:14px;' colspan=10><b>Ledger of ".$row1['memname']."-".$memid." for the year ".$year."</b></td>
							</tr></table>";
    
    $output.= '<table style="font-size:13px" style="font-family:verdana;"  border = "1">';																	
    $output.= '<thead id="theads1">';
    $output.= '<tr>';                
                $i = 0;
                $output.= "<th style='text-align: center;' rowspan='2'>Month</th>";                                                                                
                while ($i <= ($count2-1)){
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
                while ($i <= ($count2-1)){
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
            
            $output.= '</tr>
        </thead> 
        <tbody id="trows1">';
            
            
        while ($row3 = mysqli_fetch_assoc($result3)){
        $x = 'A';
        $output.= "<tr>";
        $output.= "<td align='left'>".$row3['month']."</td>";
        $j = 0;
        $i = 0;
            while ($j <= ($count2-1)){

                
                if($subheadmodule[$i] == 4 ){
                    $output.= "<td align='right'>".$row3[$x]."</td>";
                    $ledobs[$i] = $ledobs[$i] + $row3[$x];
                    $output.= "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                }
                else if($subheadmodule[$i] == 3 ){
                    $output.= "<td align='right'>".$row3[$x]."</td>";
                    $receiptloan = $row3[$x];
                    $x++;
                    $output.= "<td align='right'>".$row3[$x]."</td>";
                    $paymentloan = $row3[$x];
                    $ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                    $output.= "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                }
                else{
                    $output.= "<td align='right' style='width:10%'>".$row3[$x]."</td>";
                }
                $x++;
                $j++;
                $i++;
            } 
            $output.= "</tr>";
        } 
            
        $output.='</tbody>
    </table>';																			
    header("Content-Type: application/xls, true");
    header("Content-Disposition:attachment; filename=$memid _YearReport-_$year.xls");
    echo $output;
    
    
?>    