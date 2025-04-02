<?php 
	  include("accounts_session.php");
    $user = $_SESSION['login_user'];
		$subid = $_POST['subid'];
    $group = $_POST['group'];
 		$todate = $_POST['tdate'];
 		$fromdate = $_POST['fdate'];
		
    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];

    $sql9 = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID = '$group'");
    $row9 = mysqli_fetch_assoc($sql9);
    if($group == 'All'){
      $groupname = 'All Groups';
    }
    else{
      $groupname = $row9['GroupName'];
    } 

    $output = "";
    $output .= "<table class='table' style='font-family:verdana;'>
                          <tr>
                            <td colspan='7' style='text-align:center; font-size:16px;'><b>Mother Teresa MACS,Vijayawada</b></td>
                          </tr>
                          <tr>
                            <td colspan='7' style='text-align:center; font-size:14px;'><b>Ledger : ".$groupname."</b></td>
                          </tr>";

                          $output .= "<tr>
                            <td colspan='7' style='text-align:center; font-size:15px;'>Statement dated ".date('d/m/Y', strtotime($fromdate))." to ".date('d/m/Y', strtotime($todate))."</td>
                          </tr>
                          </table>";
                          $output .='<table border="1" class="table table-bordered table-hover">	
                          <thead>
                          <tr>														
                            <th style="text-align: center;">TransID</th>
                            <th style="text-align: center;">Date</th>														                            
                            <th style="text-align: center;">Details</th>																				                                    	
                            <th style="text-align: center;">Opening Balance</th>
                            <th style="text-align: center;">Receipts</th>														                            
                            <th style="text-align: center;">Payments</th>																				                                    	
                            <th style="text-align: center;">Closing Balance</th>                            
                          </tr>
                          </thead><tbody>';
    if($subid == 1){
      $ob = 0;     
      
      if($group != 'All'){
        $sql5 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE groupid = '$group' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
			  $row5 = mysqli_fetch_assoc($sql5);  
			
			  $sql6 = mysqli_query($connection, "SELECT date, acc_cashbook.TransID, receiptcash, paymentcash, details, remarks FROM acc_cashbook, acc_transactions WHERE groupid='$group' AND date >= '$fromdate' AND date <= '$todate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id");
			  $count6 = mysqli_num_rows($sql6);          
        
      }
      else{
        $sql5 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
			  $row5 = mysqli_fetch_assoc($sql5);  
			
			  $sql6 = mysqli_query($connection, "SELECT date, acc_cashbook.TransID, receiptcash, paymentcash, details, remarks FROM acc_cashbook, acc_transactions WHERE clusterid='$clusterid' AND date >= '$fromdate' AND date <= '$todate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id");
			  $count6 = mysqli_num_rows($sql6);          
      }
     
      
    
      
      $ob = $ob + $row5['sum(receiptcash)'] - $row5['sum(paymentcash)'];
				
      if( $count6 > 0){
        while($row6 = mysqli_fetch_assoc($sql6)){					
          $cb = $ob + $row6['receiptcash'] - $row6['paymentcash'];
          $payment = $row6['paymentcash'];  
          $receipt = $row6['receiptcash'];  
          $output .= "<tr><td align='center'>".$row6['TransID']."</td>";
          $output .= "<td>".$row6['date']."</td>";	      
          if($row6['details'] == $row6['remarks']){
              $output .= "<td>".$row6['details']."</td>";
            }
            else{
              $output .= "<td>".$row6['details']."-".$row6['remarks']."</td>";  
            } 
          $output .= "<td align='right'>".number_format($ob,2)."</td>";          
          $output .= "<td align='right'>".number_format($receipt,2)."</td>";
          $output .= "<td align='right'>".number_format($payment,2)."</td>";
          $output .= "<td align='right'>".number_format($cb,2)."</td></tr>";					
          $ob = $cb;							
        }
      }
    }


 		else {		
      if($group != 'All'){
        $sql3 = mysqli_query($connection, "SELECT sum(receiptcash), sum(receiptadj), sum(paymentcash), sum(paymentadj) FROM acc_cashbook, acc_transactions WHERE groupid = '$group' AND date < '$fromdate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid");
			  $row3 = mysqli_fetch_assoc($sql3);
      
			  $sql4 = mysqli_query($connection, "SELECT acc_cashbook.TransID, receiptcash, receiptadj, paymentcash, paymentadj, date, details, remarks FROM acc_cashbook, acc_transactions WHERE groupid = '$group' AND date >= '$fromdate' AND date <= '$todate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id ");
			  $count4 = mysqli_num_rows($sql4);          
      }
      else{
        $sql3 = mysqli_query($connection, "SELECT sum(receiptcash), sum(receiptadj), sum(paymentcash), sum(paymentadj) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$fromdate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid");
			  $row3 = mysqli_fetch_assoc($sql3);
      
			  $sql4 = mysqli_query($connection, "SELECT acc_cashbook.TransID, receiptcash, receiptadj, paymentcash, paymentadj, date, details, remarks FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date >= '$fromdate' AND date <= '$todate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id ");
			  $count4 = mysqli_num_rows($sql4);  
      }
      
    
			
      $sql5 = mysqli_query($connection, "SELECT MainID FROM acc_majorheads, acc_subhead WHERE acc_subhead.MajorID = acc_majorheads.MajorID AND SubID = '$subid'");
			$row5 = mysqli_fetch_assoc($sql5);
			$mainid = $row5['MainID'];  
      
			$ob = 0;
			if($mainid == 2 || $mainid == 4 || $mainid == 6 ){
				
				$ob = $ob - $row3['sum(receiptcash)'] - $row3['sum(receiptadj)'] + $row3['sum(paymentcash)'] + $row3['sum(paymentadj)'] ;
				
				if( $count4 > 0){
					while($row4 = mysqli_fetch_assoc($sql4)){					
					$cb = $ob - $row4['receiptcash'] - $row4['receiptadj']  + $row4['paymentcash'] + $row4['paymentadj'];
          $payment = $row4['paymentcash'] + $row4['paymentadj'];  
          $receipt = $row4['receiptcash'] + $row4['receiptadj'];  
					$output .= "<tr><td align='center'>".$row4['TransID']."</td>";
					$output .= "<td>".$row4['date']."</td>";	        
          if($row4['details'] == $row4['remarks']){
              $output .= "<td>".$row4['details']."</td>";
            }
            else{
              $output .= "<td>".$row4['details']."-".$row4['remarks']."</td>";  
            } 
					$output .= "<td align='right'>".number_format($ob,2)."</td>";					
					$output .= "<td align='right'>".number_format($receipt,2)."</td>";
          $output .= "<td align='right'>".number_format($payment,2)."</td>";
					$output .= "<td align='right'>".number_format($cb,2)."</td></tr>";					
					$ob = $cb;							
			 		}
				}
			}	
			else{
				$ob = $ob + $row3['sum(receiptcash)'] + $row3['sum(receiptadj)'] - $row3['sum(paymentcash)'] - $row3['sum(paymentadj)'] ;
				
				if($count4 > 0){
					while($row4 = mysqli_fetch_assoc($sql4)){					
						$cb = $ob + $row4['receiptcash'] + $row4['receiptadj']  - $row4['paymentcash'] - $row4['paymentadj'];  	
            $payment = $row4['paymentcash'] + $row4['paymentadj'];  
          $receipt = $row4['receiptcash'] + $row4['receiptadj'];
						$output .= "<tr><td align='center'>".$row4['TransID']."</td>";
						$output .= "<td>".$row4['date']."</td>";		            
            if($row4['details'] == $row4['remarks']){
              $output .= "<td>".$row4['details']."</td>";
            }
            else{
              $output .= "<td>".$row4['details']."-".$row4['remarks']."</td>";  
            } 
						$output .= "<td align='right'>".number_format($ob,2)."</td>";						
						$output .= "<td align='right'>".number_format($receipt,2)."</td>";
            $output .= "<td align='right'>".number_format($payment,2)."</td>";
						$output .= "<td align='right'>".number_format($cb,2)."</td></tr>";					
						$ob = $cb;											
					}
				}	
			}			
	  }
							  
			$output .='</tbody></table>';	
			header("Content-Type: application/xls, true");
			header("Content-Disposition:attachment; filename=legder.xls");
			echo $output;

 	mysqli_close($connection);



?>