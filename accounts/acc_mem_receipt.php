<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
    if($_SERVER['REQUEST_METHOD']=='POST'){
    $memid = $_POST['member'];
    $sql3 = mysqli_query($connection,"SELECT members.memgroupid FROM members, groups WHERE members.memid = '$memid' and members.memgroupid = groups.GroupID and groups.ClusterID = '$clusterid'");
    $count3 = mysqli_num_rows($sql3);
    
        if($count3>0){
            $sql1 = "SELECT members.*, GroupName, GroupID from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
                $result1 = mysqli_query($connection, $sql1);
            $count1 = mysqli_num_rows($result1);
            $row1 = mysqli_fetch_assoc($result1);
        
        
            $sql = mysqli_query($connection,"SELECT acc_deposits.*, SubHead FROM acc_deposits, acc_subhead
                                            WHERE acc_subhead.SubHeadModule = 2 AND acc_subhead.SubID = acc_deposits.subheadid");
        
            $row = mysqli_fetch_assoc($sql);   

        
            $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule != 9 AND SubHeadModule != 10 AND SubHeadModule != 1 AND SubHeadModule != 5 AND SubHeadModule != 6 AND SubHeadModule != 7  AND SubID NOT IN (SELECT subheadid FROM acc_cashbook_dummy, acc_subhead WHERE acc_cashbook_dummy.subheadid = acc_subhead.SubID AND acc_subhead.SubHeadModule <> 8 AND memid = '$memid')");
            $count2 = mysqli_num_rows($sql2);
        
            $sql4 = mysqli_query($connection,"SELECT acc_cashbook_dummy.*, SubHead FROM acc_cashbook_dummy,acc_subhead 
                                            WHERE memid = '$memid' AND acc_subhead.SubID = acc_cashbook_dummy.subheadid and receiptcash !=0");
            $count4 = mysqli_num_rows($sql4); 
        }
        else{
            $memid = "Member not belongs to this Cluster";
            $count4 = 0;
        }
    }
else if(isset($_GET['memid'])){
    $count3 = 1;
	$memid = $_GET['memid'];
    $sql1 = "SELECT members.*, GroupName, GroupID from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
  
    $sql = mysqli_query($connection,"SELECT acc_deposits.*, SubHead FROM acc_deposits, acc_subhead
                                    WHERE acc_subhead.SubHeadModule = 2 AND acc_subhead.SubID = acc_deposits.subheadid");
  
    $row = mysqli_fetch_assoc($sql);   

  
    $sql2 = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule != 9 AND SubHeadModule != 10 AND SubHeadModule != 1 AND SubHeadModule != 5 AND SubHeadModule != 6 AND SubHeadModule != 7  AND SubID NOT IN (SELECT subheadid FROM acc_cashbook_dummy, acc_subhead WHERE acc_cashbook_dummy.subheadid = acc_subhead.SubID AND acc_subhead.SubHeadModule <> 8 AND memid = '$memid')");
    $count2 = mysqli_num_rows($sql2);
  
    $sql4 = mysqli_query($connection,"SELECT acc_cashbook_dummy.*, SubHead FROM acc_cashbook_dummy,acc_subhead 
                                    WHERE memid = '$memid' AND acc_subhead.SubID = acc_cashbook_dummy.subheadid and receiptcash !=0");
    $count4 = mysqli_num_rows($sql4);

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
								 Receipts 
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
														<a href="acc_group_det.php?groupid=<?php echo $row1['GroupID']; ?>"><span class="editable" id="username"> <?php if($count3>0){ echo $row1['GroupName']; }?> </span></a>
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
                                                    <tr> <form action="acc_mem_receipt_add.php" class="form-horizontal" method ="post">
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
                                                        <select name="loanno" id = "loanno" class = "form-group" style="width:200px;" required>
                                                        
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name ="balance"  id="balance" readonly />
                                                        <input type="hidden" name ="memid" id="memid" value="<?php echo $memid;?>" /> </td>											
                                                    <td><input type="text" class="form-control"  name ="amount"  id="amount" required />
                                                    <td><button type="submit" id="but" class="btn btn-info btn-fill"><i class="fa fa-check" style="margin-right:10px;"></i>Add</button></td>													
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
                                                            echo "<td align='right'>".$row4['receiptcash']."</td>";
                                                            echo "<td align='center'><a href='acc_mem_receipt_del.php?id=".$row4['id']."'><i class='fa fa-close'></i></a></td>";
                                                            echo "</tr>";
                                                            $slno = $slno + 1;
                                                            $total = $total + $row4['receiptcash'];
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
                      
                                            <div class="content table-responsive table-full-width" style="float:left;">                        
                                                                            <a href='acc_mem_det.php?memid=<?php echo $memid; ?>'><button class='btn btn-info btn-fill'><i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back</button></a>												
                                            </div>
                      

                                            <div class="content table-responsive table-full-width" style="float:right;">
                                                <?php 
                                                    if($count4 == 0){
                                                        echo "<button class='btn btn-info btn-fill' disabled>Proceed</button>";
                                                    }
                                                    else{
                                                        echo "<a href='acc_mem_receipt_suc.php?memid=".$memid."'><button type='submit' class='btn btn-info btn-fill' id='proceed' onclick='return confirm(&quot Please Check Once, Once submitted cannot be revoked. &quot); return false;'>Proceed</button></a>";                                									
                                                    }
                                                ?>
                                                
                                            </div>
                      
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
    if(subid == '')
      $('#but').prop('disabled', true);
    $.ajax({  
						type: "POST",  
						url: "acc_account_nos.php",  
						data: {subid: subid, memid: memid}, 
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
              $('#loanno').html(res[1]);              
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
        if(accno[0]=="L"){
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
    
