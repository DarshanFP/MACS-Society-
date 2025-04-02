<?php	    
	include("accounts_session.php");
	//$_SESSION['curpage']="accounts_trans";
	include("accountssidepan.php"); 
    $transcount = 0;
    if(isset($_GET['transid'])){
        $transid = $_GET['transid'];
        $_SESSION['curpage']="accounts";
        $sql5 = mysqli_query($connection,"SELECT acc_cashbook.*,CancelStatus FROM acc_cashbook, acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND TransStatus = 1 AND acc_cashbook.TransID = '$transid'");
        $transcount = mysqli_num_rows($sql5);
    }
    else{
        $_SESSION['curpage']="accounts_trans";
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
                                <span id="cancel0">Cancel Request added successfully.</span>
                                <span id="cancel1">Cancel Request already in queue.</span>
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
                                                        <?php 
                                                            if($transcount > 0){
                                                                echo '<span class="editable" id="username">                                                        
                                                                    <input type="text" name="transid" id="transid" value = "'.$transid.'" disabled >                                                            
                                                                    <button type = "button" class = "btn btn-primary" disabled >Submit
                                                                    </button>
                                                                </span>';      
                                                            }
                                                            else{
                                                                echo '<span class="editable" id="username">                                                        
                                                                    <input type="text" name="transid" id="transid">                                                            
                                                                    <button type = "button" id = "transview" class = "btn btn-primary">Submit
                                                                    </button>
                                                                </span>';      
                                                            }
                                                        ?>
                                                        
                                                    </div>
                                                </div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                                    </div>
                                                </div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
                                                    </div>
                                                </div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
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
                                                </thead>	
                                                <tbody id=transrows>
                                                    <?php 
                                                        if($transcount > 0){
                                                            $sno = 1;
                                                            while($row5=mysqli_fetch_assoc($sql5)){
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
                                                                $receipt = $row5['receiptcash']+$row5['receiptadj'];
                                                                $payment = $row5['paymentcash']+$row5['paymentadj'];
                                                                echo "<td align='center'>".$receipt."</td>";            
                                                                echo "<td align='center'>".$payment."</td>";            
                                                                echo "</tr>";               
                                                                $sno++;  
                                                                
                                                            }
                                                        }
                                                    
                                                    
                                                    ?>

                                                </tbody>
                                                </table>
                                            </div> 
                                            
                                            <!-- <a id="link" href="acc_trans_cancel.php"> -->
                                            <button id="cancel" class="btn btn-primary" style="float:right;">
                                                <i class="fa fa-close" style="margin-right:10px;"></i>Cancel
                                            </button>
                                            <!-- </a> -->
                                            <?php
                                            if($_SESSION['curpage']=="accounts"){
                                                echo '<a href="accounts.php"><button class="btn btn-primary" style="float:left;">
                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                </button></a>';
                                            }else{
                                                echo '<a href="acc_trans_view.php"><button class="btn btn-primary" style="float:left;">
                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                </button></a>'; 
                                            }
                                            ?>   
                                            <div class="profile-info-value" style="float:right;">
                                                <label class="reasons" style="color:red;"><b>Cancellation Reasons</b></label>
												<span class="editable" id="username">
                                                <input type="text" class="reasons" name="cancelreasons" id="cancelreasons" required />
                                                </span>                          
                                            <div>                 
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
    $(".reasons").prop('disabled', true);
    $('#cancel0').hide();
    $('#cancel1').hide();
    $("#transview").prop('disabled', false); 
    $("#transview").click(function() 
	{   
        var transid = $('#transid').val();
        //$("#link").attr('href','acc_trans_cancel.php?transid='+transid);
        //alert(transid);
        $.ajax({  
            type: "POST",
            data:"transid="+ transid,
            url: "acc_trans_view_suc.php",  
			success: function(response){
                //alert(response);
                response = response.split('|');
                if(!$.trim(response[1])){
                   $('#transrows').html('');
                   alert('TransID not exists or belongs to other cluster.');
                   $("#cancel").prop('disabled', true);
                }else{
                     																				
                    $('#transrows').html(response[1]);
                    if(response[0] != 1){
                        $("#cancel").prop('disabled', false);
                        $(".reasons").prop('disabled', false);
                    }
                    else{
                        alert("Your are viewing Cancelled Transaction");
                    }
                    
                     
                }
            } 
		}); 
		return false;
    });

    $("#cancel").click(function() 
	{   
        var transid = $('#transid').val();
        var reasons = $('#cancelreasons').val();
        //alert(reasons); 
        $.ajax({  
            type: "POST",
            //data:"transid="+ transid,
            data:{transid : transid, reasons: reasons},
            url: "acc_trans_view_cancel.php",  
			success: function(response){
                //alert(response);
                if(response == 0 || response == 3){
                   $('#cancel0').show();
                   $("#transview").prop('disabled', true);
                   $("#cancel").prop('disabled', true);
                   $(".reasons").prop('disabled', true);
                }else if(response == 1){ 																				
                    $('#cancel1').show();
                    $("#transview").prop('disabled', true);
                    $("#cancel").prop('disabled', true);
                    $(".reasons").prop('disabled', true);
                }
            } 
		}); 
		return false;
    });
});    
</script>    