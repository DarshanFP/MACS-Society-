<?php
include("pdt_session.php");
ob_start();
//require('fpdf.php');
include_once('fpdf.php');
$user = $_SESSION['login_user'];
    
		$subid = $_POST['subid'];
    $group = $_POST['group'];
 		$todate = $_POST['tdate'];
 		$fromdate = $_POST['fdate'];
    $clusterid = $_POST['cluster'];		
    
 		$cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];
      
//     $sql10 = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID = '$office'");
//     $row10 = mysqli_fetch_assoc($sql10);
//     if($office == 'All'){
//       $clustername = 'All Clusters';
//     }
//     else{
//       $clustername = $row9['ClusterName'];
//     }   

    $sql9 = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID = '$group'");
    $row9 = mysqli_fetch_assoc($sql9);
    if($group == 'All'){
      $groupname = 'All Groups';
    }
    else{
      $groupname = $row9['GroupName'];
    }
      
    global $groupname;
    global $clustername;
    global $fromdate;  
    global $todate;

    //PDF Invoice Starts
    class PDF extends FPDF
    {
      // Page header
      function Header()
      {
        $fromdate = $GLOBALS['fromdate'];
        $todate = $GLOBALS['todate'];
        $groupname = $GLOBALS['groupname'];
        $clustername = $GLOBALS['clustername'];

        $this->SetFont('Arial','B',14);
        // Title
        $this->SetDrawColor(220,50,50);
        //$this->SetTextColor(220,50,50);
        $this->Cell(20);
        $this->Cell(254,4,'Mother Teresa MACS',0,1,'C');
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',10);
        $this->SetFont('Arial','B',12);
        //$this->SetTextColor(220,50,50);
        $this->Cell(20);
        $this->Cell(254,4,'Vijayawada',0,1,'C');
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',10);
        $this->SetFont('Arial','B',10);
        $this->SetFont('Arial','',10);
        $this->SetFont('Arial','B',12);
        //$this->SetTextColor(220,50,50);
        $this->Cell(20);
        $this->Cell(254,4,'Andhra Pradesh',0,1,'C');
        $this->SetFont('Arial','',10);
        //$this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',10);
        $this->Ln(6);
        $this->SetFont('Arial','B',10);
        $this->Cell(20);
        $this->Cell(86,0,'','T',0,'L');
        //$this->SetTextColor(220,50,50);
        $this->SetFont('Arial','B',12);
        $this->Cell(20);
        $this->Cell(40,0,'Ledger : '.$clusterid.'-'.$groupname,0,0,'C');
        //$this->Cell(63,0,$officename,0,0,'L');
        $this->Cell(20);
        $this->Cell(86,0,'','T',1,'L');
        $this->Ln(6);
        $this->Cell(20);
        $this->Cell(254,0,'Ledger dated '.date("d/m/Y",strtotime($fromdate)).' to '.date("d/m/Y",strtotime($todate)).'',0,1,'L');
        $this->SetTextColor(0,0,0);
        $this->Ln(2);
      }

      // Page footer
      function Footer()
      {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','B',9);
        $this->SetDrawColor(220,50,50);
        $this->Cell(20);
        $this->Cell(254,0,'','T',1,'L');
        $this->Cell(0,10,'Mother Teresa MACS,Vijayawada,Andhra pradesh',0,0,'C');
      }
      // Class definition Ends
    }
		
      
    $pdf = new PDF();
    //header
    $pdf->SetDrawColor(203,203,203);
    $pdf->SetFillColor(250,250,139);
    $pdf->AddPage('L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Ln(3);
    $pdf->Cell(20);
    $pdf->Cell(254,0,'','T',1,'L');
    //for($i=0;$i<8;$i++){
    //$pdf->Cell(24.5,12,$header[$i],0,0,'L');
    //} 
    //$pdf->Ln(6);
    $pdf->Cell(20);
    $pdf->Cell(254,0,'','T',1,'L');
    $pdf->SetDrawColor(174,183,179);

    $pdf->Cell(20);
    $pdf->Cell(30,6,'TransID','LBR',0,L);
    $pdf->Cell(30,6,'Date','LBR',0,L);
    $pdf->Cell(74,6,'Details','LBR',0,L);
    $pdf->Cell(30,6,'Opening Balance',1,0,C);
    $pdf->Cell(30,6,'Receipt',1,0,C);
    $pdf->Cell(30,6,'Payment',1,0,C);
    $pdf->Cell(30,6,'Closing Balance',1,1,C);
 		
    if($subid == 1){
      $ob = 0;     
      
      if($group != 'All'){
        $sql5 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE groupid = '$group' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
			  $row5 = mysqli_fetch_assoc($sql5);  
			
			  $sql6 = mysqli_query($connection, "SELECT date, acc_cashbook.TransID, receiptcash, paymentcash, details, remarks FROM acc_cashbook, acc_transactions WHERE groupid='$group' AND date >= '$fromdate' AND date <= '$todate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id");
			  $count6 = mysqli_num_rows($sql6);          
        
      }      
      else if($clusterid !='All'){
        $sql5 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
			  $row5 = mysqli_fetch_assoc($sql5);  
			
			  $sql6 = mysqli_query($connection, "SELECT date, acc_cashbook.TransID, receiptcash, paymentcash, details, remarks FROM acc_cashbook, acc_transactions WHERE clusterid='$clusterid' AND date >= '$fromdate' AND date <= '$todate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id");
			  $count6 = mysqli_num_rows($sql6);          
      }
      else{
        $sql5 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
			  $row5 = mysqli_fetch_assoc($sql5);  
			
			  $sql6 = mysqli_query($connection, "SELECT date, acc_cashbook.TransID, receiptcash, paymentcash, details, remarks FROM acc_cashbook, acc_transactions WHERE date >= '$fromdate' AND date <= '$todate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id");
			  $count6 = mysqli_num_rows($sql6);                  
      }
     
      
    
      
      $ob = $ob + $row5['sum(receiptcash)'] - $row5['sum(paymentcash)'];
				
      if( $count6 > 0){
        while($row6 = mysqli_fetch_assoc($sql6)){					
          $cb = $ob + $row6['receiptcash'] - $row6['paymentcash'];
          $payment = $row6['paymentcash'];  
          $receipt = $row6['receiptcash'];
          
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(20);
          $pdf->Cell(30,6,$row6['TransID'],'LBR',0,L);
          $pdf->Cell(30,6,$row6['date'],'LBR',0,L);
            if($row6['details'] == $row6['remarks']){
              $pdf->Cell(74,6,$row6['details'],'LBR',0,L);
            }
            else{
              $pdf->Cell(74,6,$row6['details']."-".$row6['remarks'],'LBR',0,L);
              $output .= "<td>".$row6['details']."-".$row6['remarks']."</td>";  
            } 
          $pdf->Cell(30,6,$ob,1,0,R);
          $pdf->Cell(30,6,$receipt,1,0,R);
          $pdf->Cell(30,6,$payment,1,0,R);
          $pdf->Cell(30,6,$cb,1,1,R);
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
      else if($clusterid !='All'){
        $sql3 = mysqli_query($connection, "SELECT sum(receiptcash), sum(receiptadj), sum(paymentcash), sum(paymentadj) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$fromdate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid");
			  $row3 = mysqli_fetch_assoc($sql3);
      
			  $sql4 = mysqli_query($connection, "SELECT acc_cashbook.TransID, receiptcash, receiptadj, paymentcash, paymentadj, date, details, remarks FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date >= '$fromdate' AND date <= '$todate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id ");
			  $count4 = mysqli_num_rows($sql4);  
      }
      else{
        $sql3 = mysqli_query($connection, "SELECT sum(receiptcash), sum(receiptadj), sum(paymentcash), sum(paymentadj) FROM acc_cashbook, acc_transactions WHERE  date < '$fromdate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid");
			  $row3 = mysqli_fetch_assoc($sql3);
      
			  $sql4 = mysqli_query($connection, "SELECT acc_cashbook.TransID, receiptcash, receiptadj, paymentcash, paymentadj, date, details, remarks FROM acc_cashbook, acc_transactions WHERE date >= '$fromdate' AND date <= '$todate' AND subheadid = '$subid' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY date, id ");
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
					
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(20);
          $pdf->Cell(30,6,$row6['TransID'],'LBR',0,L);
          $pdf->Cell(30,6,$row6['date'],'LBR',0,L);	        
          if($row4['details'] == $row4['remarks']){
              $pdf->Cell(74,6,$row6['details'],'LBR',0,L);
            }
            else{
              $pdf->Cell(74,6,$row6['details']."-".$row6['remarks'],'LBR',0,L);  
            } 
					$pdf->Cell(30,6,$ob,1,0,R);
          $pdf->Cell(30,6,$receipt,1,0,R);
          $pdf->Cell(30,6,$payment,1,0,R);
          $pdf->Cell(30,6,$cb,1,1,R);					
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
						
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(20);
            $pdf->Cell(30,6,$row6['TransID'],'LBR',0,L);
            $pdf->Cell(30,6,$row6['date'],'LBR',0,L);		            
            if($row4['details'] == $row4['remarks']){
              $pdf->Cell(74,6,$row6['details'],'LBR',0,L);
            }
            else{
              $pdf->Cell(74,6,$row6['details']."-".$row6['remarks'],'LBR',0,L);   
            } 
						$pdf->Cell(30,6,$ob,1,0,R);
            $pdf->Cell(30,6,$receipt,1,0,R);
            $pdf->Cell(30,6,$payment,1,0,R);
            $pdf->Cell(30,6,$cb,1,1,R);				
						$ob = $cb;											
					}
				}	
			}			
		}

	  
 $pdf->Ln(20);
//   $pdf->Cell(20);
//   $pdf->Cell(84,6,'Assistant Manager',0,0,C);
//   if($date != $last_day_month){
//     $pdf->Cell(86);
//     $pdf->Cell(84,6,'Business Manager',0,1,C);
//   }
//   else{
//     $pdf->Cell(86,6,'Business Manager',0,0,C);
//     $pdf->Cell(84,6,'Chairman',0,1,C);
//   }
//   $pdf->Cell(20);
//   $pdf->Cell(84,6,'KDCMS LTD VIJAYAWADA',0,0,C);
//   if($date != $last_day_month){
//     $pdf->Cell(86);
//     $pdf->Cell(84,6,'KDCMS LTD VIJAYAWADA',0,1,C);
//   }
//   else{
//     $pdf->Cell(86,6,'KDCMS LTD VIJAYAWADA',0,0,C);
//     $pdf->Cell(84,6,'KDCMS LTD VIJAYAWADA',0,1,C);
//   }
  $pdf->Output();
 	mysqli_close($connection);
  ob_end_flush();
?>