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
          echo "<tr><td align='center'>".$row6['TransID']."</td>";
          echo "<td>".$row6['date']."</td>";	      
          if($row6['details'] == $row6['remarks']){
              echo "<td>".$row6['details']."</td>";
            }
            else{
              echo "<td>".$row6['details']."-".$row6['remarks']."</td>";  
            } 
          echo "<td align='right'>".number_format($ob,2)."</td>";          
          echo "<td align='right'>".number_format($receipt,2)."</td>";
          echo "<td align='right'>".number_format($payment,2)."</td>";
          echo "<td align='right'>".number_format($cb,2)."</td></tr>";					
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
					echo "<tr><td align='center'>".$row4['TransID']."</td>";
					echo "<td>".$row4['date']."</td>";	        
          if($row4['details'] == $row4['remarks']){
              echo "<td>".$row4['details']."</td>";
            }
            else{
              echo "<td>".$row4['details']."-".$row4['remarks']."</td>";  
            } 
					echo "<td align='right'>".number_format($ob,2)."</td>";					
					echo "<td align='right'>".number_format($receipt,2)."</td>";
            echo "<td align='right'>".number_format($payment,2)."</td>";
					echo "<td align='right'>".number_format($cb,2)."</td></tr>";					
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
						echo "<tr><td align='center'>".$row4['TransID']."</td>";
						echo "<td>".$row4['date']."</td>";		            
            if($row4['details'] == $row4['remarks']){
              echo "<td>".$row4['details']."</td>";
            }
            else{
              echo "<td>".$row4['details']."-".$row4['remarks']."</td>";  
            } 
						echo "<td align='right'>".number_format($ob,2)."</td>";						
						echo "<td align='right'>".number_format($receipt,2)."</td>";
            echo "<td align='right'>".number_format($payment,2)."</td>";
						echo "<td align='right'>".number_format($cb,2)."</td></tr>";					
						$ob = $cb;											
					}
				}	
			}			
		}
	
?>