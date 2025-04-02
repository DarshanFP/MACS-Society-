<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president";
    include("pdtsidepan.php");
    if(isset($_GET['transid'])){
        $transid = $_GET['transid'];
        //echo $transid;       
        $sql5 = mysqli_query($connection,"SELECT acc_cashbook.*,TransStatus,CancelStatus FROM acc_cashbook,acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND TransStatus = 1 AND CancelStatus = 1 AND acc_transactions.TransID = '$transid'");
        $sql51 = mysqli_query($connection,"SELECT acc_cashbook.*,TransStatus FROM acc_cashbook,acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_cashbook.TransID = '$transid'");
    }    
    
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Transaction View
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
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
                                <input type="text" name="transid" id="transid" value=<?php echo $transid;?> readonly>
                                <button type = "button" id = "transview" class = "btn btn-primary" disabled>
                                  Submit
                                </button>
                              <!-- </form> -->                            
                                
                            </span>                          
                                
                            
													</div>
                          
												</div>
												

											</div>

											<div class="space-20"></div>

                                            <div class="content table-responsive table-full-width">
                                                <table class="table" border=1>	
                                                <thead>
                                                    <th>Sl.No</th>
                                                    <th>TransID</th>
                                                    <th>Date</th>
                                                    <th>Member Name</th>
                                                    <th>Head of Account</th>										
                                                    <th>Account No</th>	
                                                    <th>Details</th>
                                                    <th>Receipt</th>
                                                    <th>Payment</th>
                                                    <th>Cluster</th>
                                                    <th>Status</th>
                                                </thead>	
                                                <tbody id=transrows>
                                                <?php    
                                                    $sno = 1;
                                                    while($row5=mysqli_fetch_assoc($sql51)){
                                                        echo "<tr>";
                                                        echo "<td align='center'>".$sno."</td>";
                                                        echo "<td align='center'>".$transid."</td>";
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
                                                        echo "<td align='center'>".$receipt."</td>";
                                                        $payment = $row5['paymentcash'] + $row5['paymentadj'];
                                                        echo "<td align='center'>".$payment."</td>";
                                                        $cluster = $row5['clusterid'];
                                                        $clustersql = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID='$cluster'");
                                                        $clustername = mysqli_fetch_assoc($clustersql);
                                                        echo "<td align='center'>".$clustername['ClusterName']."</td>";
                                                        if($row5['TransStatus']==1){
                                                            echo "<td class='center'><span class='label label-success'>Active</span></td>";
                                                        }else if($row5['TransStatus']==0){
                                                            echo "<td class='center'><span class='label label-danger'>Cancelled</span></td>";
                                                        }
                                                        echo "</tr>";
                                                        $sno++;
                                                    }
                                                ?>    
                                                </tbody>
                                                </table>
                                            </div>
                                            <a id="link" href="pdt_trans_cancel_all.php"><button id="back" class="btn btn-primary" style="float:right;margin:20px;">
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
    $("#transview").click(function() 
	{   
        var transid = $('#transid').val();
        //alert(transid);
        $.ajax({  
            type: "POST",
            data:"transid="+ transid,
            url: "pdt_trans_view_suc.php",  
			success: function(response){
                //alert(response);
                if(!$.trim(response)){
                   $('#transrows').html('');
                   alert('TransID not exists.');
                }else{ 																				
                    $('#transrows').html(response);
                }
            } 
		}); 
		return false;
    });
});    
</script>    
