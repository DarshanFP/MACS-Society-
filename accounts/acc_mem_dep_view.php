<?php	    
include("accounts_session.php");
	
if(isset($_POST['memid'])){
		$memid = TRIM($_POST['memid']);
        $depid = TRIM($_POST['depid']);
        $fromdate = $_POST['fromdate'];
        $todate = $_POST['todate'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);

        $installment = mysqli_query($connection, "SELECT installment FROM acc_deposits WHERE depositno = '$depid'");
        $installment = mysqli_fetch_assoc($installment);
        $installment = $installment['installment'];

        $sql2 = "SELECT DISTINCT SubID,SubHead, SubHeadModule FROM acc_subhead,acc_cashbook WHERE SubID = subheadid AND accno = '$depid' GROUP BY SubID,SubHead, SubHeadModule ORDER BY SubHeadModule";
        $result2 = mysqli_query($connection,$sql2);
        $count2 = mysqli_num_rows($result2);
        //echo $count2;
        $i = 0;
        $cstring = '';
        $sstring = ''; 
        $casestring = '';
        
        
        $x = 'A';
        while($row2 = mysqli_fetch_assoc($result2)){
        
            $subid[$i] = $row2['SubID'];                
            $subhead[$i] = $row2['SubHead'];
            $subheadmodule[$i] = $row2['SubHeadModule'];
            
                        
            if($i < ($count2-1)){
                
                if($subheadmodule[$i] != 4){
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x.",";
                }
                else{ 
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptadj end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x.",";
                }
                $x++;
            }
            else{                    
                if($subheadmodule[$i] != 4){
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x."";
                    $sstring .= "sum(".$x.") as ".$x."";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x."";
                }
                else{ 
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then receiptadj end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x.",";
                    $sstring .= "sum(".$x.") as ".$x.",";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentcash end as ".$x.",";
                    $x++;
                    $cstring .= "coalesce(".$x.", 0) as ".$x."";
                    $sstring .= "sum(".$x.") as ".$x."";
                    $casestring .= "case when subheadid = '".$subid[$i]."' then paymentadj end as ".$x."";
                }
                $x++;    
            }              
            $i++;            
        }
  
    $depquery = "SELECT TransID,date1,".$cstring." 
                FROM (SELECT TransID,min(date) as date1,".$sstring."
                FROM (SELECT A.*,".$casestring."
                FROM (SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE accno = '$depid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 ) AS A) AS B GROUP BY TransID) AS V WHERE date1 BETWEEN '$fromdate' AND '$todate' ORDER BY date1";
    $depfinal = mysqli_query($connection,$depquery);
    $count = mysqli_num_rows($depfinal);
  
    $sql2 = mysqli_query($connection,"SELECT SubHead FROM acc_deposits, acc_subhead WHERE depositno = '$depid' AND SubID = subheadid");
    $row2 = mysqli_fetch_assoc($sql2);
   }

   if($count>0){
        $slno = 1;                  
        $i = 0;
        while($row4 = mysqli_fetch_assoc($depfinal)){
            if($slno == 1){
                $obsql = mysqli_query($connection,"SELECT (sum(`receiptcash`)+sum(`receiptadj`)-sum(`paymentcash`)-sum(`paymentadj`)) AS ob FROM `acc_cashbook` WHERE date < '$fromdate' AND accno = '$depid' AND subheadid = 2 GROUP BY accno");
                $obrow = mysqli_fetch_assoc($obsql);
                $ob = $obrow['ob'];
            }
            $x = 'A';  
            $receipt = $row4[$x];                                                                                                                                                                      
            $x++;
            $receipt = $receipt + $row4[$x];
            $x++;
            $payment = $row4[$x];
            $x++;
            $payment = $payment + $row4[$x];
            $cb = $ob + $receipt - $payment;                                                                                      
            $x++;
            $interest = $row4[$x];
            $x++;
            $interest = $interest + $row4[$x];
            echo "<tr><td class='center'>".$slno."</td>";
            echo "<td class='center'>".$row4['TransID']."</td>";
            echo "<td class='center'>".$row4['date1']."</td>";
            echo "<td align = 'right'>".number_format($ob,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($receipt,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($payment,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($cb,2,'.','')."</td>";
            echo "<td align = 'right'>".number_format($interest,2,'.','')."</td></tr>";
            $slno = $slno + 1;
            $ob = $cb;
        }
    }
?>