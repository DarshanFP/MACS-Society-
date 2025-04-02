<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
    if(isset($_GET['memid'])){
        $count3 = 1;
        $memid = $_GET['memid'];        
        $sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
            $result1 = mysqli_query($connection, $sql1);
        $count1 = mysqli_num_rows($result1);
        $row1 = mysqli_fetch_assoc($result1);

    
        $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule != 8 AND SubHeadModule != 10 AND SubHeadModule != 1 AND SubHeadModule != 5 AND SubHeadModule != 6 AND SubHeadModule != 7 AND SubHeadModule != 3  AND SubID NOT IN (SELECT subheadid FROM acc_payment_dummy, acc_subhead WHERE acc_payment_dummy.subheadid = acc_subhead.SubID AND acc_subhead.SubHeadModule <> 9 AND memid = '$memid')");
        $count2 = mysqli_num_rows($sql2);
    
        $sql4 = mysqli_query($connection,"SELECT acc_payment_dummy.*, SubHead FROM acc_payment_dummy, acc_subhead 
                                        WHERE memid = '$memid' AND acc_subhead.SubID = acc_payment_dummy.subheadid");
        $count4 = mysqli_num_rows($sql4);

        $bank = mysqli_query($connection, "SELECT * FROM acc_subhead WHERE SubHeadModule = 7");
        $bankcount = mysqli_num_rows($bank);

    }
    else{
    $memid = 0;
    }
		
    

  
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Cheque Payment 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">										
										<div class="col-xs-12 col-sm-10">											
											<div class="space-12"></div>
											<div class="profile-user-info profile-user-info-striped">												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member ID </div>
													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $memid;?> </span>
                            
													</div>                          
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php if($count3>0){ echo $row1['memname']; }?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>
													<div class="profile-info-value">
														<span class="editable" id="username"> <?php if($count3>0){ echo $row1['GroupName']; }?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php if($count3>0){ echo $row1['memphone'];}?> </span>
													</div>
												</div>

											</div>

											<div class="space-20"></div>


                                            <div class="content table-responsive table-full-width">
                                                <table class="table">	
                                                <thead>
                                                    <th>Sl.No</th>
                                                    <th>Head of Account</th>										
                                                    <th>Account No</th>	
                                                    <th>Balance</th>
                                                    <th>Amount</th>
                                                    <th></th>
                                                </thead>	
                                                <tbody>
                                                    <tr> <form action="acc_mem_payment_add.php" class="form-horizontal" method ="post">
                                                    <td align="center"> 1 </td>
                                                    <td>												
                                                        <select class="form-group" id="subid" name="subid"  style="width:200px; " required >	
                                                        <option value=''> </option>
                                                        <?php 
                                                            while ($row2 = mysqli_fetch_assoc($sql2)) 												
                                                            echo "<option value ='".$row2['SubID']."'>".$row2['SubHead']."</option>";								
                                                        ?>
                                                        </select>												
                                                    </td>
                                                    <td id='accdropdown'>
                                                        <input type = "text" class="form-group" name="accno" id="accno"  style="width:200px; " readonly >	
                                                        <select name="depositno" id = "loanno" class = "form-group" style="width:200px;" required>
                                                        
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name ="balance"  id="balance" readonly />
                                                        <input type="hidden" name ="memid" id="memid" value="<?php echo $memid;?>" /> </td>
                                                        <input type="hidden" name ="status" id="status" value="payment" /> </td>											
                                                    <td><input type="text" class="form-control"  name ="amount"  id="amount" required />
                                                    <td><button type="submit" id="but" class="btn btn-info btn-fill">Add</button></td>													
                                                    </form>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>

                                            <div class="content table-responsive table-full-width">
                                                <table class="table">
                                                    <thead>
                                                        <th style="text-align:center">Sl.No</th>
                                                        <th style="text-align:left">Sub Head</th>										
                                                        <th style="text-align:center">Account No</th>
                                                        <th style="text-align:center">Amount Received</th>
                                                        <th style="text-align:center">Delete</th>                                    	
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $total = 0;
                                                        if($count4>0){
                                                            $slno = 1;
                                                            
                                                            while($row4 = mysqli_fetch_assoc($sql4)){
                                                            echo "<tr>";
                                                            echo "<td align='center'>".$slno."</td>";
                                                            echo "<td>".$row4['SubHead']."</td>";
                                                            echo "<td align='center'>".$row4['accno']."</td>";
                                                            echo "<td align='right'>".$row4['paymentadj']."</td>";
                                                            echo "<td align='center'><a href='acc_mem_payment_del.php?id=".$row4['id']."'><i class='fa fa-close'></i></a></td>";
                                                            echo "</tr>";
                                                            $slno = $slno + 1;
                                                            $total = $total + $row4['paymentadj'];
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                        <td></td>
                                                        <td style="text-align:center">Total Amount</td>
                                                        <td></td>
                                                        <td align='right'><?php echo number_format($total,2); ?></td>
                                                        <td></td>
                                                        </tr>	
                                                    </tbody>
                                                </table>

                                            </div>

                                            <form class="form-horizontal" role="form" method="post" action="acc_mem_payment_suc.php">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Bank Account </label>

                                                <div class="col-sm-9">
                                                    <input type="hidden" value="<?php echo $memid; ?>" name="memid">
                                                    <select name="banksubid" id="form-field-2" required>
                                                        <option></option>
                                                        <?php 
                                                            if($bankcount > 0){
                                                                while($bankacc = mysqli_fetch_assoc($bank)){
                                                                    echo "<option value='".$bankacc['SubID']."'>".$bankacc['SubHead']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                        
                                                    </select>		
                                                </div>
                                            </div>

                                            <div class="space-20"></div>
                      
                                            <div class="content table-responsive table-full-width" style="float:left;">                        
                                                <a href='acc_mem_det.php?memid=<?php echo $memid; ?>'><button type='button' class='btn btn-info btn-fill'>Back</button></a>												
                                            </div>
                      

                                            <div class="content table-responsive table-full-width" style="float:right;">
                                                <?php if($count4 == 0){
                                                        echo "<button class='btn btn-info btn-fill' disabled>Proceed</button>";
                                                    }
                                                        else {
                                                            echo "<button type='submit' class='btn btn-info btn-fill' id='proceed' onclick='return confirm(&quot Please Check Once, Once submitted cannot be revoked. &quot); return false;'>Proceed</button>";                                									
                                                    }?>
                                                
                                            </div>
                                            </form>
                      
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<script>
$(document).ready(function(){
    $('#loanno').prop('disabled', true);
  $('#loanno').hide();
  
  var subid = $("#subid").val();
  if(subid == ''){
    $('#but').prop('disabled', true);
    $('#accno').val("");
    $('#balance').val("");
  }
  $('#subid').change(function(){    
    $('#but').prop('disabled', false);
    $('#amount').prop('disabled', false);
    var subid = $("#subid").val();
    var memid = $("#memid").val();
    var status = $("#status").val();
    if(subid == '')
      $('#but').prop('disabled', true);
        $.ajax({  
						type: "POST",  
						url: "acc_account_nos.php",  
						data: {subid: subid, memid: memid, status: status}, 
						datatype: "html",
						success: function(data){
            
				    var res = data.split("*");
            
            if(res[0] == 0){
              alert(res[1]);
              $('#but').prop('disabled', true);
              $('#loanno').prop('disabled', true);
              $('#loanno').hide();
              $('#amount').prop('disabled', true);
              $('#accno').show();              
              $('#accno').val("");
              $('#balance').val("");
             
            }
            else if(res[0]==1){
                $('#loanno').prop('disabled', true);
              $('#loanno').hide();
              
              $('#accno').show();
              $('#accno').val("");
              $('#balance').val("");
              
            }
            else if(res[0]=="true"){
              $('#accno').hide();              
              $('#loanno').prop('disabled', false);
              $('#loanno').show();              
              //var loanid = JSON.parse(res[1]);
              $('#loanno').html(res[1]);              
              /* $.each(loanid, function(key, value){                
                 $('#loanno').append('<option>' + value + '</option>');  
              });              */
              
              $('#balance').val("");
            }  
            else{
              $('#loanno').hide();
              $('#accno').show();
              $('#accno').val(res[0]);
              $('#balance').val(res[1]);
              
            }  
							
						},
					});
  });
  
    $('#amount').keyup(function(){
     $('#but').prop('disabled', false);
      var balance = $("#balance").val();
      var amount = $("#amount").val();
      var accno = $("#accno").val();
      amount = parseInt(amount);
      balance = parseInt(balance);
//      alert(amount);
//      alert(balance);
      if(amount != ""){
        if(accno[0]=="D"){
          if(amount>balance){
            alert("Amount Entered more than Balance");
            $('#but').prop('disabled', true);
          }
        }
      }
    });
  
});
</script>

<?php 
	include("footer.php");    
?>		
    
