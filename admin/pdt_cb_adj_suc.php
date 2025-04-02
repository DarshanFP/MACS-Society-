<?php	  
include("pdt_session.php");
	$_SESSION['curpage']="president_cashbook";
	include("pdtsidepan.php");

  $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
 	$transcount = mysqli_num_rows($trans);
  $transcount = 1001 + $transcount;	
 	$transid = "T".$macsshortform.$transcount;

	date_default_timezone_set('Asia/Kolkata');
 	$timedate = date('Y-m-d H:i:s', time());
 		
 	mysqli_query($connection,"START TRANSACTION");

 $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`,`timedate`) VALUES ('$transid',1,'$timedate')");

 	$sql = mysqli_query($connection,"UPDATE acc_cashbook_dummy SET TransID ='$transid', date = '$today'  WHERE clusterid = '$clusterid'");

 	$sql1 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `TransID`,`subheadid`, `details`,`receiptadj`, 
					`paymentadj`, `remarks`, `entryempid`, `timedate`) SELECT `date`, `clusterid`, `TransID`,`subheadid`, `details`,`receiptadj`, 
					`paymentadj`, `remarks`, `entryempid`, `timedate` FROM acc_cashbook_dummy WHERE clusterid = '$clusterid'");

  $sql3 = mysqli_query($connection,"DELETE FROM acc_cashbook_dummy WHERE clusterid = '$clusterid'");

  if($transinsert && $sql && $sql1 && $sql3) {
		 	mysqli_query($connection,"COMMIT");
	}
  else{
		mysqli_query($connection,"ROLLBACK");
	}

$sql4 = mysqli_query($connection,"SELECT acc_subhead.SubHead, acc_cashbook.* FROM acc_cashbook, acc_subhead  WHERE acc_cashbook.TransID = '$transid' AND acc_cashbook.subheadid = acc_subhead.SubID");
$count4 = mysqli_num_rows($sql4);	


?>

			<div class="main-content">
  				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Cash Book Adjustment Entry passed successfully
									<i class="ace-icon fa fa-angle-double-right"></i>
									Trans ID : <?php echo $transid; ?>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-8"> <!-- PAGE CONTENT BEGINS -->
                
                <div class="content table-responsive table-full-width">
                  <table class="table ">
                      <thead>
                        <th style="text-align:center">Sl.No</th>
                        <th>Head</th>					
                        <th style="text-align:center">Details</th>
                        <th style="text-align:center">Debit</th>
                        <th style="text-align:center">Credit</th>                                    	
                      </thead>
                      <tbody>
                          <?php if($count4>0){
                            $slno=1;											
                            $debit = 0;
                            $credit = 0;
                            while($row1 = mysqli_fetch_assoc($sql4))
                            { 	
                              echo "<tr><td align='center'>".$slno."</td>";
                              echo "<td>".$row1['SubHead']."</td>";
                              echo "<td align='center'>".$row1['details']."</td>";
                              echo "<td align='center'>".$row1['paymentadj']."</td>";					
                              echo "<td align='center'>".$row1['receiptadj']."</td></tr>";

                              $slno = $slno +1;	
                              $debit = $debit + $row1['paymentadj'];
                              $credit = $credit + $row1['receiptadj'];

                            }				
                          }
                          ?> 
                          <tr>
                            <td></td>																					
                            <td style="text-align:center"><b>Total Amount</b></td>
                            <td></td>
                            <td style="text-align:center"><?php echo number_format($debit,2); ?></td>																					
                            <td style="text-align:center"><?php echo number_format($credit,2); ?></td>
                          </tr>	
                      </tbody>
                  </table>
                </div>
 							 	<div class="row">   
										<div class="col-md-12">
											<a href="president_cashbook.php"><button type="button" class="btn btn-info btn-fill"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
										</div>	
                </div>     

                
                
							
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			