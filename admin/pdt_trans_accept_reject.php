<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president";
    include("pdtsidepan.php");
    if(isset($_GET['transid'])){
        $transid = $_GET['transid'];
        $status = $_GET['status'];
        //echo $transid;       
        $sql5 = mysqli_query($connection,"SELECT acc_cashbook.*,TransStatus,CancelStatus FROM acc_cashbook,acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND TransStatus = 1 AND CancelStatus = 1 AND acc_transactions.TransID = '$transid'");
        $transremarks = mysqli_query($connection, "SELECT TransRemarks FROM acc_transactions WHERE TransID = '$transid'");
        $remarks = mysqli_fetch_assoc($transremarks);
        
        if($status == 'reject'){
            $transupdate = mysqli_query($connection,"UPDATE acc_transactions SET CancelStatus = 3 WHERE TransID = '$transid'");
        }
        else if($status == 'accept'){
            $canceltran = mysqli_query($connection,"SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND TransStatus = 1 AND CancelStatus = 1 AND acc_transactions.TransID = '$transid'");            
            $cancelcount = mysqli_num_rows($canceltran);
            if($cancelcount > 0){
                $transupdate = mysqli_query($connection,"UPDATE acc_transactions SET CancelStatus = 2, TransStatus = 0 WHERE TransID = '$transid'");
                while($rowcancel = mysqli_fetch_assoc($canceltran)){
                    $clusterid = $rowcancel['clusterid'];
                    $memid = $rowcancel['memid'];
                    $subid = $rowcancel['subheadid'];
                    $accno = $rowcancel['accno'];
                    $receiptcash = $rowcancel['receiptcash'];
                    $receiptadj = $rowcancel['receiptadj'];
                    $paymentcash = $rowcancel['paymentcash'];
                    $paymentadj = $rowcancel['paymentadj'];

                    $balamount = $receiptcash - $paymentcash;
                    $balance = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance - '$balamount' WHERE ClusterID = '$clusterid'");
                    
                    $sql6 = mysqli_query($connection,"SELECT SubHeadModule FROM acc_subhead WHERE SubID = '$subid'");
                    $row6 = mysqli_fetch_assoc($sql6);
                    $module = $row6['SubHeadModule'];

                    if ($module == 2){
                        $amount = $receiptcash + $receiptadj - $paymentcash - $paymentadj;                        
                        $shareupdate = mysqli_query($connection,"UPDATE acc_sharecapital SET balance = balance - '$amount' WHERE memid = '$memid'");                        
                    }
                    else if ($module == 3){
                        $amount = $receiptcash + $receiptadj - $paymentcash - $paymentadj;                        
                        $loanupdate = mysqli_query($connection,"UPDATE acc_loans SET cb = cb + '$amount', status = 1 WHERE loanno = '$accno'");
                    }
                    else if ($module == 4){
                        $amount = $receiptcash + $receiptadj - $paymentcash - $paymentadj;                        
                        $depositupdate =  mysqli_query($connection,"UPDATE acc_deposits SET cb = cb - '$amount', status = 1 WHERE depositno = '$accno'");
                    }
                    else if ($module == 8){
                        $amount = $receiptcash + $receiptadj;
                        $loanintupdate = mysqli_query($connection,"UPDATE acc_loans SET intcollected = intcollected - '$amount' WHERE loanno = '$accno'");
                    }
                    else if ($module == 9){
                        $amount = $paymentcash + $paymentadj;
                        $depositintupdate = mysqli_query($connection,"UPDATE acc_deposits SET intpaid = intpaid - '$amount' WHERE depositno = '$accno'");
                    }
                    else if ($module == 11){
                        $receipt = $receiptcash + $receiptadj;
                        $payment = $paymentcash + $paymentadj;
                        if($receipt != 0){
                            $otherincupdate = mysqli_query($connection,"UPDATE acc_loan_income SET Status = 3, Remarks = 'Transaction Cancelled' WHERE SubID = '$subid' AND MemID = '$memid' AND TransID = '$transid'");
                        } 
                        else if($paymentadj != 0){
                            $otherincupdate = mysqli_query($connection,"UPDATE acc_loan_income SET Status = 0 WHERE SubID = '$subid' AND MemID = '$memid' AND Status = 2");
                        }
                    } 
                }
            }
        }
    } 
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Transaction Cancellation
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
                                    <?php 
                                        if($status == 'reject'){
                                            echo "<span style='color:red;'>Transaction Cancellation Request Rejected</span>";
                                        }
                                        else if($status == 'accept'){
                                            echo "<span style='color:red;'>Transaction Cancellation Request Accepted</span>";
                                        }
                                    ?>
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
													<div class="profile-info-name"><b>TransactionID</b> </div>

													<div class="profile-info-value">
														<span class="editable" id="username">
                              <!-- <form class = "form-group" action="acc_mem_receipt.php" method ="post">-->
                                <input type="text" name="transid" id="transid" value="<?php echo $transid; ?>" readonly >
                                Remarks : <span style="color:red;"> <?php echo $remarks['TransRemarks']; ?> </span>
                                <!--<button type = "button" id = "transview" class = "btn btn-primary">
                                  Submit
                                </button>
                              </form> -->                            
                                
                            </span>                          
                                
                            
													</div>
                          
												</div>
												

											</div>

											<div class="space-20"></div>

                                            <div class="content table-responsive table-full-width">
                                                <table class="table" border=1>	
                                                <thead>
                                                    <th text-align='center'>Sl.No</th>                                                    
                                                    <th text-align='center'>Date</th>
                                                    <th text-align='center'>Member Name</th>
                                                    <th text-align='center'>Head of Account</th>										
                                                    <th text-align='center'>Account No</th>	
                                                    <th text-align='center'>Details</th>                                                    
                                                    <th text-align='center'>Receipt</th>                                                    
                                                    <th text-align='center'>Payment</th>                                                    
                                                    <th text-align='center'>Cluster</th>
                                                </thead>	
                                                <tbody id=transrows>
                                                <?php
                                                $sno = 1;
                                                while($row5=mysqli_fetch_assoc($sql5)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$sno."</td>";                                                    
                                                    echo "<td align='center'>".date('d-m-Y',strtotime($row5['date']))."</td>";
                                                    $subheadid = $row5['subheadid'];
                                                    $memid = $row5['memid'];
                                                    $sql61 = mysqli_query($connection,"SELECT SubHead FROM acc_subhead WHERE SubID = '$subheadid'");
                                                    $row61 = mysqli_fetch_assoc($sql61);
                                                    $subhead = $row61['SubHead'];
                                                    $sql62 = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$memid'");
                                                    $row62 = mysqli_fetch_assoc($sql62);
                                                    $memname = $row62['memname'];
                                                    echo "<td>".$memname."</td>";
                                                    echo "<td align='center'>".$subhead."</td>";
                                                    echo "<td align='center'>".$row5['accno']."</td>";
                                                    echo "<td>".$row5['details']."</td>";   
                                                    $receipt = $row5['receiptcash'] + $row5['receiptadj'];
                                                    $payment = $row5['paymentcash'] + $row5['paymentadj'];                                                                                                                                                            
                                                    echo "<td align='center'>".$receipt."</td>";
                                                    echo "<td align='center'>".$payment."</td>";
                                                    $cluster = $row5['clusterid'];
                                                    $clustersql = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID='$cluster'");
                                                    $clustername = mysqli_fetch_assoc($clustersql);
                                                    echo "<td align='center'>".$clustername['ClusterName']."</td>";
                                                    echo "</tr>"; 
                                                    $sno++;     
                                                }
                                                ?>

                                                </tbody>
                                                </table>
                                            </div>
                                            
                                            <a id="link" href="president.php"><button id="back" class="btn btn-primary" style="float:right;margin:20px;">
                                                <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                            </button></a>
                                                             
										</div>
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
<script>
$(document).ready(function()
{   
    $("#cancel").prop('disabled', true); 
    $("#transview").click(function() 
	{   
        var transid = $('#transid').val();
        $("#link").attr('href','acc_trans_cancel.php?transid='+transid);
        //alert(transid);
        $.ajax({  
            type: "POST",
            data:"transid="+ transid,
            url: "acc_trans_view_suc.php",  
			success: function(response){
                //alert(response);
                if(!$.trim(response)){
                   $('#transrows').html('');
                   alert('TransID not exists or belongs to other cluster.');
                   $("#cancel").prop('disabled', true);
                }else{ 																				
                    $('#transrows').html(response);
                    
                    $("#cancel").prop('disabled', false);
                    
                }
            } 
		}); 
		return false;
    });
});    
</script>    
