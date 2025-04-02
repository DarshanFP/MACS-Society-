<?php
include("accounts_session.php");
ob_start();
//require('fpdf.php');
include_once('fpdf.php');
$user = $_SESSION['login_user'];
    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];


    $group = $_POST['group'];
		$today = $_POST['sdate'];
 		$date = $_POST['sdate'];

    $sql9 = mysqli_query($connection,"SELECT GroupName FROM groups WHERE GroupID = '$group'");
    $row9 = mysqli_fetch_assoc($sql9);
    if($group == 'All'){
      $groupname = 'All Groups';
    }
    else{
      $groupname = $row9['GroupName'];
    }
global $groupname;
global $today;

//PDF Invoice Starts
class PDF extends FPDF
{
	// Page header
	function Header()
	{
		$today = $GLOBALS['today'];
		$groupname = $GLOBALS['groupname'];
    				
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
		$this->Cell(40,0,'Cash Book : '.$groupname,0,0,'C');
		//$this->Cell(63,0,$officename,0,0,'L');
    $this->Cell(20);
		$this->Cell(86,0,'','T',1,'L');
		$this->Ln(6);
    $this->Cell(20);
		$this->Cell(254,0,'Cash Book dated '.date("d/m/Y",strtotime($today)).'',0,1,'L');
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

		$cashfirstob = 0;
 	  if($group != 'All'){
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE groupid =  '$group' AND date < '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY groupid");
      $cou = mysqli_num_rows($sql1);
      if ($cou >0){
        $cash = mysqli_fetch_assoc($sql1);
        $cashreceipt = $cash['sum(receiptcash)'];
        $cashpayment = $cash['sum(paymentcash)'];
        $cashob = $cashfirstob + $cashreceipt - $cashpayment;
      }
      else{
        $cashob = $cashfirstob;
      }
    }
    else{
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid = '$clusterid' AND date < '$today' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
      $cou = mysqli_num_rows($sql1);
      if ($cou >0){
        $cash = mysqli_fetch_assoc($sql1);
        $cashreceipt = $cash['sum(receiptcash)'];
        $cashpayment = $cash['sum(paymentcash)'];
        $cashob = $cashfirstob + $cashreceipt - $cashpayment;
      }
      else{
        $cashob = $cashfirstob;
      }  
    }
		
    if($group != 'All'){
      $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions WHERE groupid =  '$group' AND date =  '$date' AND subheadid = SubID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY subheadid ");
      $count2 = mysqli_num_rows($sql2);
    }
    else{
      $sql2 = mysqli_query($connection, "SELECT acc_cashbook.*, SubHead FROM acc_cashbook, acc_subhead, acc_transactions WHERE acc_cashbook.clusterid = '$clusterid' AND acc_cashbook.date =  '$date' AND subheadid = SubID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 ORDER BY subheadid ");
      $count2 = mysqli_num_rows($sql2);  
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
    $pdf->Cell(28,6,'TransID','LTR',0,'C');
    $pdf->Cell(30,6,'MemberID','LTR',0,'C');
    $pdf->Cell(76,6,'Details','LTR',0,'C');																				                                    	
    $pdf->Cell(60,6,'Cash',1,0,'C');													
    $pdf->Cell(60,6,'Adjustment',1,1,'C');
    $pdf->Cell(20);
    $pdf->Cell(28,6,'','LBR',0,'L');
    $pdf->Cell(30,6,'','LBR',0,'L');
    $pdf->Cell(76,6,'','LBR',0,'L');
    $pdf->Cell(30,6,'Receipt',1,0,'C');
    $pdf->Cell(30,6,'Payment',1,0,'C');
    $pdf->Cell(30,6,'Receipt',1,0,'C');
    $pdf->Cell(30,6,'Payment',1,1,'C');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20);
    $pdf->Cell(134,6,'Opening Balance',1,0,'L');
    //$pdf->Cell(30,6,'',1,0,L);
    $pdf->Cell(30,6,$cashob,1,0,'R');
    $pdf->Cell(30,6,'',1,0,'L');
    $pdf->Cell(30,6,'',1,0,'L');
    $pdf->Cell(30,6,'',1,1,'L');

		$debitcash = 0.00;
		$debitadj = 0.00;
		$creditcash = 0.00;
		$creditadj = 0.00;
		$cashcb = 0.00;
    $grandreceiptcash = 0.00;
    $sumreceiptadj = 0.00;
    if($count2>0){
    $headingsubhead = 0;


    while($row2 = mysqli_fetch_assoc($sql2)){
      if($headingsubhead == $row2['subheadid']){
        
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(20);
        $pdf->Cell(28,6,$row2['TransID'],'LTR',0,'L');
        if (!empty($row2['memid'])) {
          $memid = $row2['memid'];
          $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
          $row7 = mysqli_fetch_assoc($sql7);
          $pdf->Cell(30,6,$memid,'LTR',0,'C');
          $pdf->Cell(76,6,$row7['memname']." - ".$row2['details'],'LTR',0,'L');	
        }
        else{
          $pdf->Cell(106,6,$row2['details'],'LTR',0,'C'); 
        }
        
        $pdf->Cell(30,6,$row2['receiptcash'],1,0,'R');
        $pdf->Cell(30,6,$row2['paymentcash'],1,0,'R');
        $pdf->Cell(30,6,$row2['receiptadj'],1,0,'R');
        $pdf->Cell(30,6,$row2['paymentadj'],1,1,'R');
                
        $headingsubhead = $row2['subheadid'];
        $debitcash = $debitcash + $row2['paymentcash'];
        $creditcash = $creditcash + $row2['receiptcash'];

        $debitadj = $debitadj + $row2['paymentadj'];
        $creditadj = $creditadj + $row2['receiptadj'];

      }
      else {
        
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20);
        $pdf->Cell(254,6,$row2['SubHead'],'LTR',1,'L');
        $headingsubhead = $row2['subheadid'];

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(20);
        $pdf->Cell(28,6,$row2['TransID'],'LTR',0,'L');																			
        if (!empty($row2['memid'])){
          $memid = $row2['memid'];
          $sql7 = mysqli_query($connection," SELECT memname FROM members WHERE memid = '$memid' ");
          $row7 = mysqli_fetch_assoc($sql7);
          $pdf->Cell(30,6,$memid,'LTR',0,'C');
          $pdf->Cell(76,6,$row7['memname']." - ".$row2['details'],'LTR',0,'L');	
        }
        else{
          $pdf->Cell(106,6,$row2['details'],'LTR',0,'L');      
        }
        $pdf->Cell(30,6,$row2['receiptcash'],1,0,'R');
        $pdf->Cell(30,6,$row2['paymentcash'],1,0,'R');
        $pdf->Cell(30,6,$row2['receiptadj'],1,0,'R');
        $pdf->Cell(30,6,$row2['paymentadj'],1,1,'R');
        
        $headingsubhead = $row2['subheadid'];
        $debitcash = $debitcash + $row2['paymentcash'];
        $creditcash = $creditcash + $row2['receiptcash'];
        $debitadj = $debitadj + $row2['paymentadj'];
        $creditadj = $creditadj + $row2['receiptadj'];																																						
      }
    }
  }
	$creditcash = $cashob + $creditcash;
	$cashcb = $creditcash - $debitcash;
	$grandpaymentadj = $grandreceiptcash + $sumreceiptadj;

  $pdf->SetFont('Arial','B',10);
	$pdf->Cell(20);																
	$pdf->Cell(134,6,'Closing Balance',1,0,'L');
  $pdf->Cell(30,6,'',1,0,'L');
	$pdf->Cell(30,6,$cashcb,1,0,'R');				
	$pdf->Cell(30,6,'',1,0,'L');
  $pdf->Cell(30,6,'',1,1,'L');
	$debitcashcb = $debitcash + $cashcb;
	$pdf->Cell(20);
	$pdf->Cell(134,6,'Grand total',1,0,'L');
	$pdf->Cell(30,6,$debitcashcb,1,0,'R');	
	$pdf->Cell(30,6,$creditcash,1,0,'R');	
	$pdf->Cell(30,6,$debitadj,1,0,'R');	
	$pdf->Cell(30,6,$creditadj,1,1,'R');

	  
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