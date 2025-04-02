

<?php	  
	include("accounts_session.php");
	//$_SESSION['curpage']="accounts_reports";
    include("accountssidepan.php");
    if($_SERVER['REQUEST_METHOD']=='POST' || isset($_GET['memid'])){
        if(isset($_GET['memid'])){
            $memid = $_GET['memid']; 
            $back = 0; 
        }
        else{
            $memid = $_POST['memid'];     
            $back = 1;
        }
        if($back==0){
            $_SESSION['curpage']="accounts_member";
        }else{
            $_SESSION['curpage']="accounts_reports";
        }
        
        $month = date('m');
        
        if($month > 4)
        {
            $y = date('Y');
            $nexty = date('Y', strtotime('+1 year'));
            $start = $y."-04-01";
            $end = $nexty."-03-31";
        }
        else
        {
            $y = date('Y', strtotime('-1 year'));
            $nexty = date('Y');
            $start = $y."-04-01";
            $end = $nexty."-03-31";
        }
        
        $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
        $result1 = mysqli_query($connection, $sql1);
        $row1 = mysqli_fetch_assoc($result1);        
        
        $sql2 = "SELECT DISTINCT SubID,SubHead, SubHeadModule, min(accno) FROM acc_subhead,acc_cashbook WHERE SubID = subheadid AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' GROUP BY SubID,SubHead, SubHeadModule ORDER BY SubHeadModule";
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
        //$count20 = mysqli_num_rows($query20);
        $rows20 = mysqli_fetch_array($query20);
        $qry = $rows20[0];
        
        $sql3="SELECT month,".$cstring."    
            FROM (SELECT
                month,".$sstring."
            FROM (SELECT
                A.*,".$casestring."
            FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end'
                GROUP BY subheadid,month) AS A) AS B GROUP BY month 
                ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')) AS V";
        //echo $sql3;
        $result3=mysqli_query($connection,$sql3);


        $sql30="SELECT month,".$qry." FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
                GROUP BY subheadid,month) AS A GROUP BY month
                ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";
        //echo $sql30;
        $result30=mysqli_query($connection,$sql30);

        $sql300="SELECT month,".$qry." FROM (SELECT DATE_FORMAT(`date`,'%M-%Y') AS month,max(subheadid) AS subhead,SUM(receiptcash) AS receipt,SUM(paymentcash) AS payment FROM acc_cashbook,acc_transactions 
                WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1 AND memid = '$memid' AND subheadid != 14 AND date BETWEEN '$start' AND '$end' 
                GROUP BY subheadid,month) AS A GROUP BY month
                ORDER BY FIELD(month,'April-".$y."','May-".$y."','June-".$y."','July-".$y."','August-".$y."','September-".$y."','October-".$y."','November-".$y."','December-".$y."','January-".$nexty."','February-".$nexty."','March-".$nexty."')";
        $result300=mysqli_query($connection,$sql300);
        $count300 = mysqli_num_rows($result300);

        $ledgerobs = mysqli_query($connection,"SELECT accno,subheadid,SubHeadModule,sum(receiptcash) as rcash, sum(receiptadj) as radj, sum(paymentcash) as pcash, sum(paymentadj) as padj 
                                                 FROM acc_subhead,acc_cashbook,acc_transactions 
                                                 WHERE SubID=subheadid AND SubHeadModule IN (3,4) AND acc_transactions.TransID = acc_cashbook.TransID AND  acc_transactions.TransStatus = 1 AND date < '$start' AND memmid = '$memid' GROUP BY subheadid");
        $rowledgerobs = mysqli_fetch_assoc($ledgerobs);
        
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
    }
?>

        <div class="main-content">
            <form method="post" target="_blank">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Individual Ledger View
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<label style="margin-left:10px">Year</label>
                                    <select name="year" id="year"   style="font-size:11pt;height:30px;width:160px;" required >	
                                        <?php
                                            $current = date("Y");   
                                            for($y=$current,$i=0 ; $i<6; $i++.$y--){
                                                echo "<option>".$y."</option>";
                                            }
                                        ?>
                                    </select>
                                    <input type="hidden" name="memid" value="<?php echo $memid; ?>">
								</small>                                
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										
										<div class="col-xs-12 col-sm-12">
                                        
                                        <div class="space-12"></div>

											<div class="profile-user-info profile-user-info-striped">
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member ID </div>

													<div class="profile-info-value">
														<span class="editable" id="memid"> <?php echo $memid;?> </span>
                                                    </div>
                                                    <div class="profile-info-name"> Bank Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankname'];?> </span>
                                                    </div>
                          
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank IFSC</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankifsc'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Account No.</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaccountno'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Address</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaddress'];?> </span>
													</div>
												</div>
											
                                        </div>
										<div class="space-20"></div>

                                           
                                            
                                        
                                        
                                        <div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Yearly Ledger 
                                                    </h4>
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<!-- <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">																	
                                                                        <thead id="theads1">
                                                                            <tr>
                                                                                <?php
                                                                                /* $i = 0;
                                                                                echo "<th style='text-align: center;' rowspan='2'>Month</th>";                                                                                
                                                                                while ($i <= ($count2-1)){
                                                                                    if($subheadmodule[$i] == 3)
                                                                                        echo "<th style='text-align: center;' colspan='3'>".$subhead[$i]."</th>";
                                                                                    else if ($subheadmodule[$i] == 4)
                                                                                        echo "<th style='text-align: center;' colspan='2'>".$subhead[$i]."</th>";
                                                                                    else
                                                                                        echo "<th style='text-align: center;' rowspan='2'>".$subhead[$i]."</th>"; 
                                                                                    $i++; 
                                                                                }
                                                                                echo "</tr><tr>";
                                                                                $i=0;
                                                                                while ($i <= ($count2-1)){
                                                                                    if($subheadmodule[$i] == 4 ){
                                                                                        echo "<th style='text-align: center;'>Receipt</th>";
                                                                                        echo "<th style='text-align: center;'>Total</th>";
                                                                                    }else if($subheadmodule[$i] == 3){
                                                                                        echo "<th style='text-align: center;'>Receipt</th>";
                                                                                        echo "<th style='text-align: center;'>Payment</th>";
                                                                                        echo "<th style='text-align: center;'>Total</th>";
                                                                                    }
                                                                                    $i++; 
                                                                                }*/ 
                                                                                ?>
                                                                            </tr>
                                                                        </thead> 
                                                                        <tbody id="trows1">
                                                                            <?php /*
                                                                            
                                                                            while ($row3 = mysqli_fetch_assoc($result3)){
                                                                            $x = 'A';
                                                                            echo "<tr>";
                                                                            echo "<td align='left'>".$row3['month']."</td>";
                                                                            $j = 0;
                                                                            $i = 0;
                                                                            while ($j <= ($count2-1)){

                                                                                
                                                                                if($subheadmodule[$i] == 4 ){
                                                                                    echo "<td align='right'>".$row3[$x]."</td>";
                                                                                    $ledobs[$i] = $ledobs[$i] + $row3[$x];
                                                                                    echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                                                                                }
                                                                                else if($subheadmodule[$i] == 3 ){
                                                                                    echo "<td align='right'>".$row3[$x]."</td>";
                                                                                    $receiptloan = $row3[$x];
                                                                                    $x++;
                                                                                    echo "<td align='right'>".$row3[$x]."</td>";
                                                                                    $paymentloan = $row3[$x];
                                                                                    $ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                                                                                    echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                                                                                }
                                                                                else{
                                                                                    echo "<td align='right'>".$row3[$x]."</td>";
                                                                                }
                                                                                $x++;
                                                                                $j++;
                                                                                $i++;
                                                                            } 
                                                                            echo "</tr>";
                                                                            } */
                                                                            ?>
                                                                        </tbody>
                                                                    </table> -->
                                                                    <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                                    <?php 
                                                                        $cnt = 0;
                                                                        $slno = 1;
                                                                        $headtotal[] = 0;
                                                                        $headarray[] = 0;
                                                                        $headnamearray[] = '';
                                                                        $row = mysqli_fetch_array($result30, MYSQLI_ASSOC);
                                                                            //if ($cnt == 0) {
                                                                                $columns = array_keys($row);
                                                                                $count1 = mysqli_num_fields($result30);
                                                                                //$count1 = mysqli_num_fields($columns);
                                                                                //$count2 = mysqli_num_fields($row);
                                                                                $heading = 0;
                                                                                echo "<tr>";
                                                                                //echo "<th style='text-align: center;' rowspan='2'>Month</th>";
                                                                                /*echo '<th style="text-align:center;">Sl.No.</th>';
                                                                                foreach ($columns as $name => $value) {
                                                                                    $head = mysqli_query($connection,"SELECT SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value'");
                                                                                    $headname = mysqli_fetch_assoc($head);
                                                                                    $headmodule = $headname['SubHeadModule'];
                                                                                    if($headmodule == 3)
                                                                                        echo "<th style='text-align: center;' colspan='3'>".$headname['SubHead']."</th>";
                                                                                    else if ($headmodule == 4)
                                                                                        echo "<th style='text-align: center;' colspan='2'>".$headname['SubHead']."</th>";
                                                                                    else
                                                                                        echo "<th style='text-align: center;' rowspan='2'>".$headname['SubHead']."</th>"; 
                                                                                    
                                                                                    if($heading == 0){
                                                                                        //echo '<th style="text-align:center;">Name of the Member</th>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<th style="text-align:center; width:10%;" colspan="2">'.$headname['SubHead'].'</th>';
                                                                                    }                                                                            
                                                                                    $heading++; 
                                                                                }
                                                                                
                                                                                echo '<th style="text-align:center;">Total</th>';
                                                                                echo "</tr>";*/
                                                                                //echo '<tr><th>' . implode('</th><th>', $columns) . '</th></tr>';
                                                                            //}


                                                                            $i=0;
                                                                            $prevhead = '';
                                                                            echo "<th style='text-align: center;' rowspan='2'>Month</th>";                                                                                
                                                                            foreach ($columns as $name => $value) {

                                                                                $head = mysqli_query($connection,"SELECT SubID,SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value' OR SubHead = '$value'");
                                                                                $headname = mysqli_fetch_assoc($head);
                                                                                $headmodule = $headname['SubHeadModule'];
                                                                                $headarray[$i] = $headname['SubHeadModule'];
                                                                                $headnamearray[$i] = $headname['SubHead'];
                                                                                $headid[$i] = $headname['SubID'];
                                                                                                                                                                
                                                                                if($headname['SubHead'] == $prevhead){
                                                                                    $i++;
                                                                                    continue;
                                                                                }
                                                                                    

                                                                                $prevhead = $headname['SubHead'];
                                                                                    
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
                                                                            $i = 0;
                                                                            foreach ($columns as $name => $value) {
                                                                                $head = mysqli_query($connection,"SELECT SubHead,SubHeadModule FROM acc_subhead WHERE SubID = '$value' OR SubHead = '$value'");
                                                                                $headname = mysqli_fetch_assoc($head);
                                                                                $headmodule = $headname['SubHeadModule'];
                                                                                
                                                                                if($headnamearray[$i] == $prevhead){
                                                                                    $i++;
                                                                                    continue;
                                                                                }    
                                                                                $prevhead = $headnamearray[$i];

                                                                                if($headmodule == 4 ){
                                                                                    echo "<th style='text-align: center;'>Receipt</th>";
                                                                                    echo "<th style='text-align: center;'>Total</th>";
                                                                                }else if($headmodule == 3){
                                                                                    echo "<th style='text-align: center;'>Receipt</th>";
                                                                                    echo "<th style='text-align: center;'>Payment</th>";
                                                                                    echo "<th style='text-align: center;'>Total</th>";
                                                                                }
                                                                                $i++;
                                                                            }            

                                                                            echo '</tr>';

                                                                    
                                                                           /* $cnt++;
                                                                            echo '<tr>';
                                                                            $head = 0;
                                                                            $total = 0;
                                                                            //echo '<td align="center"> '.$slno.' </td>'; 
                                                                            foreach ($row as $name => $value) {
                                                                                if($head == 0){
                                                                                    echo '<td> '.$value.' </td>'; 
                                                                                }
                                                                                else{
                                                                                    echo '<td align="right"> '.number_format($value,2).' </td>'; 
                                                                                }
                                                                                $total = $total + $value;
                                                                                $headtotal[$head] = $headtotal[$head] + $value;
                                                                                $head++;
                                                                            }
                                                                            echo '<td align="right"> '.number_format($total,2).' </td>';     
                                                                            echo '</tr>'; 
                                                                            $slno++;
                                                                        
                                                                        echo "<tr><td align='center'><b>Total</b></td>";
                                                                        $grandtot = 0;
                                                                        for($i = 1; $i < $head ; $i++){
                                                                            echo '<td align="right"><b>'.number_format($headtotal[$i],2).'</b></td>';                                                                
                                                                            $grandtot = $grandtot + $headtotal[$i];
                                                                        }                                                                
                                                                        echo "<td align='right'><b>".number_format($grandtot,2)."</b></td>";
                                                                        echo "</tr>";*/




                                                                        /*echo "<tr>";
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

                                                                        echo "</tr>";*/
                                                                        /*$heads = $headnamearray;
                                                                        $headunique = array_unique($heads);
                                                                        $string=implode(",",$headunique);
                                                                        echo $string;*/
                                                                        while ($row300 = mysqli_fetch_assoc($result300)){
                                                                            //$x = 'A';
                                                                            echo "<tr>";
                                                                            echo "<td align='left'>".$row300['month']."</td>";
                                                                            $j = 0;
                                                                            $i = 0;
                                                                            $prevname = '';
                                                                            $flag = 'receipt';
                                                                            
                                                                            while ($i < count($headnamearray)){
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
                                                                                    
                                                                                    echo "<td align='right'>".$row300[$headid[$i]]."</td>";
                                                                                    //$ledobs[$i] = $ledobs[$i] + $value;
                                                                                    //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                                                                                    //echo "<td align='right'>".$value."</td>";
                                                                                    echo "<td align='right'>".number_format($obledger+$row300[$headid[$i]],2)."</td>";
                                                                                }
                                                                                elseif($headarray[$i] == 3){
                                                                                    
                                                                                    
                                                                                    if($flag == 'receipt'){
                                                                                        echo "<td align='right'>".$row300[$headid[$i]]."</td>";    
                                                                                        $receiptloan = $row300[$headid[$i]];
                                                                                        $flag = 'payment';
                                                                                    }
                                                                                    else if($flag == 'payment'){
                                                                                        //echo "<td align='right'>".$value."</td>";
                                                                                        echo "<td align='right'>".$row300[$ledgerhead]."</td>";
                                                                                        $paymentloan = $row300[$ledgerhead];
                                                                                        //$ledobs[$i] = $ledobs[$i] - $receiptloan + $paymentloan;
                                                                                        //echo "<td align='right'>".number_format($ledobs[$i],2)."</td>";
                                                                                        //echo "<td align='right'>".$value."</td>";
                                                                                        echo "<td align='right'>".number_format($obledger - $receiptloan + $paymentloan,2)."</td>";
                                                                                        $flag = 'receipt';
                                                                                    }
                                                                                }
                                                                                else{
                                                                                    if($headid[$i]!=9){
                                                                                        echo "<td align='right'>".$row300[$headid[$i]]."</td>";
                                                                                    }else{
                                                                                        echo "<td align='right'>".$row300[$ledgerhead]."</td>";
                                                                                    }
                                                                                }
                                                                                
                                                                                $prevname = $headnamearray[$i];
                                                                                $i++;
                                                                                $j++;
                                                                            } 
                                                                            echo "</tr>";
                                                                        }
                                                                        
                                                                        
                                                                        ?>
                                                                    </table>																			
															</div>
															</div>
														</div>
													</div>
												</div>
											</div>
                                        </div>
                                        <button type="submit" formaction="acc_rep_mem_ledger_excel.php" class="btn btn-success">Export to Excel</button>
                                        
                                        <button type="button" class="btn btn-app btn-light btn-xs" onclick="window.print()"><i class="ace-icon fa fa-print bigger-160"></i>Print</button>
                                        <?php 
                                            if($back == 1){
                                                echo '<a href="acc_reports_ind_led.php"><button type="button" id="back" class="btn btn-primary" style="float:right;">
                                                        <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                    </button></a>';
                                            }
                                            else{
                                                echo '<a href="acc_mem_det.php?memid='.$memid.'"><button type="button" id="back" class="btn btn-primary" style="float:right;">
                                                        <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                    </button></a>';
                                            }
                                        ?>
                                        
										
									</div>
								</div>
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
                </form>
			</div><!-- /.main-content -->
<?php 
	include("footer.php");    
?>

<script type="text/javascript">
    $(document).ready(function()
	{ 	
		$('#year').change(function() 
		{   
				       
            var year = $("#year").val();
            var memid = $("#memid").text();
                   
                $.ajax({  
                    type: "POST",  
                    url: "acc_rep_mem_ledger_view_0.php",  
                    data: {year: year, memid: memid}, 
                    datatype: "html",
                    success: function(response){
                        alert(response);
                        var data = response.split("|");
                        
                        $("#theads1").html(data[0]); 
                        $("#trows1").html(data[1]);
                    }	 
                });
        });
	    return false;
	});
</script>


