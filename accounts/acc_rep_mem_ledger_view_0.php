<?php	  
	include("accounts_session.php");
	//$_SESSION['curpage']="accounts_reports";
    //include("accountssidepan.php");
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $y = $_POST['year'];
        $memid = TRIM($_POST['memid']);
           
        $nexty = $y + 1;
        $start = $y."-04-01";
        $end = $nexty."-03-31";
        
        

        $query20 = mysqli_query($connection,"SELECT 
                                            GROUP_CONCAT(DISTINCT
                                                CONCAT(
                                                'max(case when subhead = ',
                                                subheadid,
                                                ' then receipt else 0 end) ','\'',
                                                subheadid,'\'',',',
												'max(case when subhead = ',
                                                subheadid,
                                                ' then payment else 0 end) ','\'',
                                                SubHead,'\''
                                                )
                                            )
                                            FROM acc_cashbook,acc_subhead WHERE memid = '$memid' AND subheadid = SubID");
        
        $rows20 = mysqli_fetch_array($query20);
        $qry = $rows20[0];
        
        
        $sql30="SELECT month,".$qry." FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
                GROUP BY subheadid,month) AS A GROUP BY month ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";
        //echo $sql30;
        $result30=mysqli_query($connection,$sql30);

        
        
    }
                                                                
    $headarray[] = 0; 
    $row = mysqli_fetch_array($result30, MYSQLI_ASSOC);
    
    $numrows = mysqli_num_rows($result30);   
    $columns = array_keys($row);
    $count1 = mysqli_num_fields($result30);
    //$count1 = mysqli_num_fields($columns);
    //$count2 = mysqli_num_fields($row);
    $heading = 0;
    echo "<tr>";
        
    $i=0;
    $prevhead = '';
    echo "<th style='text-align: center;' rowspan='2'>Month</th>";                                                                                
    foreach ($columns as $name => $value) {

        $head = mysqli_query($connection,"SELECT SubID,SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value' OR SubHead = '$value'");
        $headname = mysqli_fetch_assoc($head);
        $headmodule = $headname['SubHeadModule'];
        $headarray[$i] = $headname['SubHeadModule'];
        $headnamearray[$i] = $headname['SubHead'];
        

        if($headname == $prevhead){
            $i++;
            continue;
        }
            

        $prevhead = $headname;
            
        if($headmodule == 3){
            echo "<th style='text-align: center;' colspan='3'>".$headname['SubHead']."</th>";
        }
        else{
            if($headmodule == 4)
                echo "<th style='text-align: center;' colspan='2'>".$headname['SubHead']."</th>";
            else
                echo "<th style='text-align: center;' rowspan='2'>".$headname['SubHead']."</th>";
                
        }        
        $i++;
    }
    echo "</tr><tr>";
    
    $prevhead = '';

    foreach ($columns as $name => $value) {
        $head = mysqli_query($connection,"SELECT SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value' OR SubHead = '$value'");
        $headname = mysqli_fetch_assoc($head);
        $headmodule = $headname['SubHeadModule'];

        if($headname == $prevhead)
            continue;

        $prevhead = $headname;

        if($headmodule == 4 ){
            echo "<th style='text-align: center;'>Receipt</th>";
            echo "<th style='text-align: center;'>Total</th>";
        }else if($headmodule == 3){
            echo "<th style='text-align: center;'>Receipt</th>";
            echo "<th style='text-align: center;'>Payment</th>";
            echo "<th style='text-align: center;'>Total</th>";
        }
    }            

    echo '</tr>';

    echo "<tr>";

    $sql300="SELECT month,".$qry." FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
                GROUP BY subheadid,month) AS A GROUP BY month ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";
        
    $result300=mysqli_query($connection,$sql300);
    $count300 = mysqli_num_rows($result300);
    while ($row300 = mysqli_fetch_assoc($result300)){
        //$x = 'A';
        echo "<tr>";
        echo "<td align='left'>".$row300['month']."</td>";
        $j = 0;
        $i = 0;
        $prevname = '';
        $flag = 'receipt';
        while ($j <= ($count300-1)){
            if($headnamearray[$i] == $prevname && $headarray[$i] != 3){
                $i++;
                continue;
            }
    
            $ledgerhead = $headnamearray[$i];
    
            $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                    FROM acc_subhead,acc_cashbook,acc_transactions 
                                                    WHERE SubID=subheadid AND SubHeadModule IN (3,4) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memid = '$memid' AND SubHead = '$ledgerhead' GROUP BY subheadid");
            $rowledgerobs = mysqli_fetch_assoc($ledgerobs);
            
            if($headarray[$i] == 4 ){
                $obledger = $rowledgerobs['rcash'] + $rowledgerobs['radj'] - $rowledgerobs['pcash'] - $rowledgerobs['padj'];
            }
    
            if($headarray[$i] == 3 ){
                $obledger = $rowledgerobs['pcash'] + $rowledgerobs['padj'] - $rowledgerobs['rcash'] - $rowledgerobs['radj'];
            }
    
            //echo "<td align='right'>".$value."</td>";
    
            if($headarray[$i] == 4 ){
                
                echo "<td align='right'>".$row300[$ledgerhead]."</td>";
                //$ledobs[$i] = $ledobs[$i] + $value;
                //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                //echo "<td align='right'>".$value."</td>";
                echo "<td align='right'>".number_format($obledger+$row300[$ledgerhead],2)."</td>";
            }
            elseif($headarray[$i] == 3){
                
                echo "<td align='right'>".$row300[$ledgerhead]."</td>";
                if($flag == 'receipt'){    
                    $receiptloan = $row300[$ledgerhead];
                    $flag = 'payment';
                }
                else if($flag == 'payment'){
                    //echo "<td align='right'>".$value."</td>";
                    $paymentloan = $row300[$ledgerhead];
                    //$ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                    //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                    //echo "<td align='right'>".$value."</td>";
                    echo "<td align='right'>".number_format($obledger - $receiptloan + $paymentloan,2)."</td>";
                    $flag = 'receipt';
                }
            }
            else{
                echo "<td align='right'>".$row300[$ledgerhead]."</td>";
            }
            
            $prevname = $headnamearray[$i];
            $i++;
            $j++;
        } 
    echo "</tr>";
    }



    $i=0;
    $prevname = '';
    $flag = 'receipt';
    echo "<td align='left'>".$row['month']."</td>";
    foreach ($row as $name => $value) {
                                                                                     
        if($headnamearray[$i] == $prevname && $headarray[$i] != 3){
            $i++;
            continue;
        }

        $ledgerhead = $headnamearray[$i];

        $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                FROM acc_subhead,acc_cashbook,acc_transactions 
                                                WHERE SubID=subheadid AND SubHeadModule IN (3,4) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memid = '$memid' AND SubHead = '$ledgerhead' GROUP BY subheadid");
        $rowledgerobs = mysqli_fetch_assoc($ledgerobs);
        
        if($headarray[$i] == 4 ){
            $obledger = $rowledgerobs['rcash'] + $rowledgerobs['radj'] - $rowledgerobs['pcash'] - $rowledgerobs['padj'];
        }

        if($headarray[$i] == 3 ){
            $obledger = $rowledgerobs['pcash'] + $rowledgerobs['padj'] - $rowledgerobs['rcash'] - $rowledgerobs['radj'];
        }

        //echo "<td align='right'>".$value."</td>";

        if($headarray[$i] == 4 ){
            
            echo "<td align='right'>".$value."</td>";
            //$ledobs[$i] = $ledobs[$i] + $value;
            //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
            //echo "<td align='right'>".$value."</td>";
            echo "<td align='right'>".number_format($obledger+$value,2)."</td>";
        }
        elseif($headarray[$i] == 3){
            
            echo "<td align='right'>".$value."</td>";
            if($flag == 'receipt'){    
                $receiptloan = $value;
                $flag = 'payment';
            }
            else if($flag == 'payment'){
                //echo "<td align='right'>".$value."</td>";
                $paymentloan = $value;
                //$ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                //echo "<td align='right'>".$value."</td>";
                echo "<td align='right'>".number_format($obledger - $receiptloan + $paymentloan,2)."</td>";
                $flag = 'receipt';
            }
        }
        else{
            echo "<td align='right'>".$value."</td>";
        }
        
        $prevname = $headnamearray[$i];
        $i++; 
        
    } 
    
    echo "</tr>";
    
    
    ?>
                                                                    <?php	  
	include("accounts_session.php");
	//$_SESSION['curpage']="accounts_reports";
    //include("accountssidepan.php");
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $y = $_POST['year'];
        $memid = TRIM($_POST['memid']);
           
        $nexty = $y + 1;
        $start = $y."-04-01";
        $end = $nexty."-03-31";
        
        

        $query20 = mysqli_query($connection,"SELECT 
                                            GROUP_CONCAT(DISTINCT
                                                CONCAT(
                                                'max(case when subhead = ',
                                                subheadid,
                                                ' then receipt else 0 end) ','\'',
                                                subheadid,'\'',',',
												'max(case when subhead = ',
                                                subheadid,
                                                ' then payment else 0 end) ','\'',
                                                SubHead,'\''
                                                )
                                            )
                                            FROM acc_cashbook,acc_subhead WHERE memid = '$memid' AND subheadid = SubID");
        
        $rows20 = mysqli_fetch_array($query20);
        $qry = $rows20[0];
        
        
        $sql30="SELECT month,".$qry." FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
                GROUP BY subheadid,month) AS A GROUP BY month ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";
        //echo $sql30;
        $result30=mysqli_query($connection,$sql30);

        
        
    }
                                                                
    $headarray[] = 0; 
    $row = mysqli_fetch_array($result30, MYSQLI_ASSOC);
    
    $numrows = mysqli_num_rows($result30);   
    $columns = array_keys($row);
    $count1 = mysqli_num_fields($result30);
    //$count1 = mysqli_num_fields($columns);
    //$count2 = mysqli_num_fields($row);
    $heading = 0;
    echo "<tr>";
        
    $i=0;
    $prevhead = '';
    echo "<th style='text-align: center;' rowspan='2'>Month</th>";                                                                                
    foreach ($columns as $name => $value) {

        $head = mysqli_query($connection,"SELECT SubID,SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value' OR SubHead = '$value'");
        $headname = mysqli_fetch_assoc($head);
        $headmodule = $headname['SubHeadModule'];
        $headarray[$i] = $headname['SubHeadModule'];
        $headnamearray[$i] = $headname['SubHead'];
        

        if($headname == $prevhead){
            $i++;
            continue;
        }
            

        $prevhead = $headname;
            
        if($headmodule == 3){
            echo "<th style='text-align: center;' colspan='3'>".$headname['SubHead']."</th>";
        }
        else{
            if($headmodule == 4)
                echo "<th style='text-align: center;' colspan='2'>".$headname['SubHead']."</th>";
            else
                echo "<th style='text-align: center;' rowspan='2'>".$headname['SubHead']."</th>";
                
        }        
        $i++;
    }
    echo "</tr><tr>";
    
    $prevhead = '';

    foreach ($columns as $name => $value) {
        $head = mysqli_query($connection,"SELECT SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value' OR SubHead = '$value'");
        $headname = mysqli_fetch_assoc($head);
        $headmodule = $headname['SubHeadModule'];

        if($headname == $prevhead)
            continue;

        $prevhead = $headname;

        if($headmodule == 4 ){
            echo "<th style='text-align: center;'>Receipt</th>";
            echo "<th style='text-align: center;'>Total</th>";
        }else if($headmodule == 3){
            echo "<th style='text-align: center;'>Receipt</th>";
            echo "<th style='text-align: center;'>Payment</th>";
            echo "<th style='text-align: center;'>Total</th>";
        }
    }            

    echo '</tr>';

    echo "<tr>";

    $sql300="SELECT month,".$qry." FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
                GROUP BY subheadid,month) AS A GROUP BY month ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";
        
    $result300=mysqli_query($connection,$sql300);
    $count300 = mysqli_num_rows($result300);
    while ($row300 = mysqli_fetch_assoc($result300)){
        //$x = 'A';
        echo "<tr>";
        echo "<td align='left'>".$row300['month']."</td>";
        $j = 0;
        $i = 0;
        $prevname = '';
        $flag = 'receipt';
        while ($j <= ($count300-1)){
            if($headnamearray[$i] == $prevname && $headarray[$i] != 3){
                $i++;
                continue;
            }
    
            $ledgerhead = $headnamearray[$i];
    
            $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                    FROM acc_subhead,acc_cashbook,acc_transactions 
                                                    WHERE SubID=subheadid AND SubHeadModule IN (3,4) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memid = '$memid' AND SubHead = '$ledgerhead' GROUP BY subheadid");
            $rowledgerobs = mysqli_fetch_assoc($ledgerobs);
            
            if($headarray[$i] == 4 ){
                $obledger = $rowledgerobs['rcash'] + $rowledgerobs['radj'] - $rowledgerobs['pcash'] - $rowledgerobs['padj'];
            }
    
            if($headarray[$i] == 3 ){
                $obledger = $rowledgerobs['pcash'] + $rowledgerobs['padj'] - $rowledgerobs['rcash'] - $rowledgerobs['radj'];
            }
    
            //echo "<td align='right'>".$value."</td>";
    
            if($headarray[$i] == 4 ){
                
                echo "<td align='right'>".$row300[$ledgerhead]."</td>";
                //$ledobs[$i] = $ledobs[$i] + $value;
                //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                //echo "<td align='right'>".$value."</td>";
                echo "<td align='right'>".number_format($obledger+$row300[$ledgerhead],2)."</td>";
            }
            elseif($headarray[$i] == 3){
                
                echo "<td align='right'>".$row300[$ledgerhead]."</td>";
                if($flag == 'receipt'){    
                    $receiptloan = $row300[$ledgerhead];
                    $flag = 'payment';
                }
                else if($flag == 'payment'){
                    //echo "<td align='right'>".$value."</td>";
                    $paymentloan = $row300[$ledgerhead];
                    //$ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                    //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                    //echo "<td align='right'>".$value."</td>";
                    echo "<td align='right'>".number_format($obledger - $receiptloan + $paymentloan,2)."</td>";
                    $flag = 'receipt';
                }
            }
            else{
                echo "<td align='right'>".$row300[$ledgerhead]."</td>";
            }
            
            $prevname = $headnamearray[$i];
            $i++;
            $j++;
        } 
    echo "</tr>";
    }



    $i=0;
    $prevname = '';
    $flag = 'receipt';
    echo "<td align='left'>".$row['month']."</td>";
    foreach ($row as $name => $value) {
                                                                                     
        if($headnamearray[$i] == $prevname && $headarray[$i] != 3){
            $i++;
            continue;
        }

        $ledgerhead = $headnamearray[$i];

        $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                FROM acc_subhead,acc_cashbook,acc_transactions 
                                                WHERE SubID=subheadid AND SubHeadModule IN (3,4) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memid = '$memid' AND SubHead = '$ledgerhead' GROUP BY subheadid");
        $rowledgerobs = mysqli_fetch_assoc($ledgerobs);
        
        if($headarray[$i] == 4 ){
            $obledger = $rowledgerobs['rcash'] + $rowledgerobs['radj'] - $rowledgerobs['pcash'] - $rowledgerobs['padj'];
        }

        if($headarray[$i] == 3 ){
            $obledger = $rowledgerobs['pcash'] + $rowledgerobs['padj'] - $rowledgerobs['rcash'] - $rowledgerobs['radj'];
        }

        //echo "<td align='right'>".$value."</td>";

        if($headarray[$i] == 4 ){
            
            echo "<td align='right'>".$value."</td>";
            //$ledobs[$i] = $ledobs[$i] + $value;
            //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
            //echo "<td align='right'>".$value."</td>";
            echo "<td align='right'>".number_format($obledger+$value,2)."</td>";
        }
        elseif($headarray[$i] == 3){
            
            echo "<td align='right'>".$value."</td>";
            if($flag == 'receipt'){    
                $receiptloan = $value;
                $flag = 'payment';
            }
            else if($flag == 'payment'){
                //echo "<td align='right'>".$value."</td>";
                $paymentloan = $value;
                //$ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                //echo "<td align='right'>".$value."</td>";
                echo "<td align='right'>".number_format($obledger - $receiptloan + $paymentloan,2)."</td>";
                $flag = 'receipt';
            }
        }
        else{
            echo "<td align='right'>".$value."</td>";
        }
        
        $prevname = $headnamearray[$i];
        $i++; 
        
    } 
    
    echo "</tr>";
    
    
    ?>
                                                                    