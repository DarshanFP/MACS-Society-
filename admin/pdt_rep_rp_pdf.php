<?php
include("pdt_session.php");
ob_start();
//require('fpdf.php');
include_once('fpdf.php');
    $office = $_POST['cluster'];
		$fromdate = $_POST['fdate'];
 		$todate = $_POST['tdate'];
		$user = $_SESSION['login_user'];

    $cluster = mysqli_query($connection,"SELECT allot.ClusterID, ClusterName FROM allot, cluster 
                                    WHERE allot.EmpID = '$user' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $clus = mysqli_fetch_assoc($cluster);

    $clusterid = $clus['ClusterID'];

    $sql9 = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID = '$office'");
    $row9 = mysqli_fetch_assoc($sql9);
    if($office == 'All'){
      $clustername = 'All Clusters';
    }
    else{
      $clustername = $row9['ClusterName'];
    } 

		
		$cashfirstob = 0;					
		// retrieve opening cash balance
 		
 	  if($office != 'All'){
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE clusterid =  '$office' AND date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY clusterid");
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
      $sql1 = mysqli_query($connection,"SELECT sum(receiptcash), sum(paymentcash) FROM acc_cashbook, acc_transactions WHERE date < '$fromdate' AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1");
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
		
    if($office != 'All'){
      $sql2 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash), sum(receiptadj), sum(paymentadj), SubHead, MajorHead FROM acc_cashbook, acc_subhead, acc_transactions, acc_majorheads WHERE clusterid =  '$office' AND date >=  '$fromdate' AND date <=  '$todate'  AND subheadid = SubID AND acc_subhead.MajorID = acc_majorheads.MajorID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid ");
      $count2 = mysqli_num_rows($sql2);
    }
    else{
      $sql2 = mysqli_query($connection, "SELECT sum(receiptcash), sum(paymentcash), sum(receiptadj), sum(paymentadj), SubHead, MajorHead FROM acc_cashbook, acc_subhead, acc_transactions, acc_majorheads WHERE date >=  '$fromdate' AND date <=  '$todate' AND subheadid = SubID AND acc_subhead.MajorID = acc_majorheads.MajorID AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus	= 1 GROUP BY subheadid ");
      $count2 = mysqli_num_rows($sql2);  
    }
global $clustername;
global $today;
global $fromdate;
global $todate;

//PDF Invoice Starts
class PDF extends FPDF
{
	// Page header
	function Header()
	{
		$today = $GLOBALS['today'];
    $fdate = $GLOBALS['fromdate'];
    $tdate = $GLOBALS['todate'];
		$clustername = $GLOBALS['clustername'];
    
    				
		$this->SetFont('Arial','B',14);
		// Title
		$this->SetDrawColor(220,50,50);
		$this->SetTextColor(220,50,50);
    $this->Cell(10);
		$this->Cell(180,4,'Mother Teresa MACS',0,1,'C');
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',10);
		$this->SetFont('Arial','B',12);
		$this->SetTextColor(220,50,50);
    $this->Cell(10);
		$this->Cell(180,4,'Vijayawada',0,1,'C');
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',10);
		$this->SetFont('Arial','B',10);
		$this->SetFont('Arial','',10);
		$this->SetFont('Arial','B',12);
		$this->SetTextColor(220,50,50);
		$this->SetTextColor(220,50,50);
    $this->Cell(10);
		$this->Cell(180,4,'Andhra Pradesh',0,1,'C');
		$this->SetFont('Arial','',10);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','B',10);
		$this->SetFont('Arial','',10);
		$this->Cell(125);
		$this->SetFont('Arial','',10);
		$this->SetFont('Arial','B',10);
		$this->SetFont('Arial','',10);
		$this->Cell(125);
		$this->SetFont('Arial','',10);
		$this->Ln(6);
		$this->SetFont('Arial','B',10);
    $this->Cell(10);
		$this->Cell(50,0,'','T',0,'L');
		$this->SetTextColor(220,50,50);
		$this->SetFont('Arial','B',12);
    $this->Cell(20);
		$this->Cell(40,0,'Reports & Payments : '.$clustername,0,0,'C');
		//$this->Cell(63,0,$officename,0,0,'L');
    $this->Cell(20);
		$this->Cell(50,0,'','T',1,'L');
		$this->Ln(6);
    $this->Cell(10);
		$this->Cell(180,0,'Statement dated '.date("d/m/Y",strtotime($fdate)).' to '.date("d/m/Y",strtotime($tdate)).'',0,1,'L');
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
    $this->Cell(10);
		$this->Cell(180,0,'','T',1,'L');
		$this->Cell(0,10,'Mother Teresa MACS,Vijayawada,Andhra pradesh',0,0,'C');
	}
  // Class definition Ends
}


    $pdf = new PDF();
    //header
    $pdf->SetDrawColor(203,203,203);
    $pdf->SetFillColor(250,250,139);
    $pdf->AddPage('P');

    $pdf->SetFont('Arial','B',10);
    $pdf->Ln(3);
    $pdf->Cell(10);
    $pdf->Cell(180,0,'','T',1,'L');
    //for($i=0;$i<8;$i++){
    //$pdf->Cell(24.5,12,$header[$i],0,0,'L');
    //} 
    //$pdf->Ln(6);
    $pdf->Cell(10);
    $pdf->Cell(180,0,'','T',1,'L');
    $pdf->SetDrawColor(174,183,179);

    $pdf->Cell(10);														
    $pdf->Cell(20,6,'Sl.No.','LTR',0,C);
    $pdf->Cell(80,6,'Particulars','LTR',0,C);
    $pdf->Cell(40,6,'Receipts','LTR',0,C);																				                                    	
    $pdf->Cell(40,6,'Payments',1,1,C);													
    
    $pdf->Cell(10);
    $pdf->Cell(100,6,'Opening Balance',1,0,L);
    //$pdf->Cell(30,6,'',1,0,L);
    $pdf->Cell(40,6,$cashob,1,0,R);
    $pdf->Cell(40,6,'',1,1,L);
    
		$totreceipt = 0;
    $totpayment = 0;
    $slno = 1;
		if($count2>0){
      $majorhead = '';
			while($row2 = mysqli_fetch_assoc($sql2)){
        if($majorhead != $row2['MajorHead']){
              $pdf->SetFont('Arial','B',10);
              $pdf->Cell(10);														
              //$pdf->Cell(20,6,'','LTR',0,C);
              $pdf->Cell(100,6,$row2['MajorHead'],'LTR',0,C);
              $pdf->Cell(40,6,'','LTR',0,R);																				                                    	
              $pdf->Cell(40,6,'',1,1,R);
         }
			  $receipt = $row2['sum(receiptcash)'] + $row2['sum(receiptadj)'];
        $payment = $row2['sum(paymentcash)'] + $row2['sum(paymentadj)'];
        
              $pdf->SetFont('Arial','',10);
              $pdf->Cell(10);														
              $pdf->Cell(20,6,$slno,'LTR',0,C);
              $pdf->Cell(80,6,$row2['SubHead'],'LTR',0,L);
              $pdf->Cell(40,6,$receipt,'LTR',0,R);																				                                    	
              $pdf->Cell(40,6,$payment,1,1,R);
        
        $totreceipt = $totreceipt + $receipt;
        $totpayment = $totpayment + $payment;
        $slno = $slno + 1;
        $majorhead = $row2['MajorHead'];
		  }
	  }
	$cashcb = $cashob + $totreceipt - $totpayment;
  $grandreceipttotal = $cashob + $totreceipt;
  $grandpaymenttotal = $cashcb + $totpayment;

  $pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);
  $pdf->Cell(100,6,'Closing Balance',1,0,L);
    //$pdf->Cell(30,6,'',1,0,L);
  $pdf->Cell(40,6,'',1,0,L);  
  $pdf->Cell(40,6,$cashcb,1,1,R);

  $pdf->Cell(10);
  $pdf->Cell(100,6,'Grand Total',1,0,L);
    //$pdf->Cell(30,6,'',1,0,L);
  $pdf->Cell(40,6,$grandreceipttotal,1,0,R);  
  $pdf->Cell(40,6,$grandpaymenttotal,1,1,R);
    

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