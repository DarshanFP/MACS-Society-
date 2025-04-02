<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
if(isset($_GET['memid'])){
	$memid = $_GET['memid'];
	$sql1 = "SELECT members.*, GroupName, GroupID from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
	$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
    $sql4 = mysqli_query($connection,"SELECT acc_cashbook_dummy.*, SubHead FROM acc_cashbook_dummy,acc_subhead 
                                    WHERE memid = '$memid' AND acc_subhead.SubID = acc_cashbook_dummy.subheadid");
    $count4 = mysqli_num_rows($sql4);
  
    $trans = mysqli_query($connection, "SELECT * FROM acc_transactions");
    $transcount = mysqli_num_rows($trans);
    $transcount = 1001 + $transcount;	
    $transid = "T".$macsshortname.$transcount;

    $transinsert = mysqli_query($connection,"INSERT INTO acc_transactions (`TransID`,`TransStatus`) 
                                VALUES ('$transid',1)");
    if($count4>0){
      while($row4 = mysqli_fetch_assoc($sql4)){
          $id = $row4['id'];
          $today = $row4['date'];
          $clusterid = $row4['clusterid'];
          $groupid = $row4['groupid'];
          $subheadid = $row4['subheadid'];
          $accno = $row4['accno'];
          $details = $row4['details'];
          $amount = $row4['receiptcash'];
          $remarks = $row4['remarks'];
          $empid = $row4['entryempid'];
            
          mysqli_query($connection,"start transaction");  
          $sql5 = mysqli_query($connection,"INSERT INTO acc_cashbook (`date`, `clusterid`, `groupid`, `TransID`, `memid`, `subheadid`, `accno`, `details`, `receiptcash`,`remarks`,`entryempid`)
						VALUES('$today','$clusterid','$groupid', '$transid', '$memid', '$subheadid', '$accno', '$details','$amount','$remarks','$empid')") or die(mysqli_error($connection));	
        
          $balance = mysqli_query($connection,"UPDATE acc_cluster_balance SET Balance = Balance + '$amount' WHERE ClusterID = '$clusterid'");
      
          $sql6 = mysqli_query($connection,"SELECT SubHeadModule FROM acc_subhead WHERE SubID = '$subheadid'");
          $row6 = mysqli_fetch_assoc($sql6);
          $module = $row6['SubHeadModule'];
          if ($module == 2){
            $sql3 = mysqli_query($connection,"SELECT * FROM acc_sharecapital WHERE memid = '$accno'");
            $count3 = mysqli_num_rows($sql3);
            if($count3>0)
              $sql9 = mysqli_query($connection,"UPDATE acc_sharecapital SET balance = balance + '$amount' WHERE memid = '$accno'");
            else
              $sql10 = mysqli_query($connection,"INSERT INTO acc_sharecapital (`memid`,`subheadid`,`dateofopening`,`balance`,`empid`)
                  VALUES('$memid','$subheadid','$today','$amount','$empid')");
          }
          else if($module == 3){
            $sql7 = mysqli_query($connection,"UPDATE acc_loans SET cb = cb - '$amount' WHERE loanno = '$accno'");
          }
          else if($module == 4){
            $sql81 = mysqli_query($connection,"UPDATE acc_deposits SET cb = cb + '$amount' WHERE depositno = '$accno'");
          }
          else if($module == 8){
            $sql71 = mysqli_query($connection,"UPDATE acc_loans SET intcollected = intcollected + '$amount' WHERE loanno = '$accno'");
          }
          else if($module == 11){
              $loanincome = mysqli_query($connection,"INSERT INTO `acc_loan_income`(`TransID`,`SubID`, `MemID`, `Amount`, `Status`)
                  VALUES('$transid','$subheadid','$memid','$amount',0)");
          }
          $sql8 = mysqli_query($connection,"DELETE FROM acc_cashbook_dummy WHERE id = '$id'");
          
          if($module == 2){
              if($count3>0){
                  if($sql5 && $balance && $sql9 && $sql8){
                     mysqli_query($connection,"commit"); 
                  }
                  else{
                    mysqli_query($connection,"rollback"); 
                  }
              }
              else{
                  if($sql5 && $balance && $sql10 && $sql8){
                     mysqli_query($connection,"commit"); 
                  }
                  else{
                    mysqli_query($connection,"rollback"); 
                  }
              }
          }
          else if($module == 3){
                  if($sql5 && $balance && $sql7 && $sql8){
                     mysqli_query($connection,"commit"); 
                  }
                  else{
                    mysqli_query($connection,"rollback"); 
                  }  
          }
          else if($module == 4){
            if($sql5 && $balance && $sql81 && $sql8){
                mysqli_query($connection,"commit"); 
            }
            else{
                mysqli_query($connection,"rollback"); 
            }
         }
         else if($module == 8){
            if($sql5 && $balance && $sql71 && $sql8){
                mysqli_query($connection,"commit"); 
            }
            else{
                mysqli_query($connection,"rollback"); 
            }
        }
        else if($module == 11){
              if($sql5 && $balance && $loanincome && $sql8){
                mysqli_query($connection,"commit"); 
              }
              else{
                mysqli_query($connection,"rollback"); 
              }
        }
        else{
            if($sql5 && $balance && $sql8){
                mysqli_query($connection,"commit"); 
              }
              else{
                mysqli_query($connection,"rollback"); 
              }
        }        
      }
    }

    $sql2 = mysqli_query($connection,"SELECT acc_cashbook.*, SubHead FROM acc_cashbook,acc_subhead 
                                    WHERE TransID = '$transid' AND acc_subhead.SubID = acc_cashbook.subheadid");
    $count2 = mysqli_num_rows($sql2);
  

  
    	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Receipt Successful with Trans ID : <?php echo $transid; ?>
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
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
													</div>
												</div>
                        
                        <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
                                                        <a href="acc_group_det.php?groupid=<?php echo $row1['GroupID']; ?>"><span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span></a>
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
                          <table class="table">
                              <thead>
                                <th style="text-align:center">Sl.No</th>
                                <th style="text-align:left">Sub Head</th>										
                                <th style="text-align:center">Account No</th>
                                <th style="text-align:center">Amount Received</th>
                              </thead>
                              <tbody>
                                <?php
                                  if($count2>0){
                                    $slno = 1;
                                    $total = 0;
                                    while($row2 = mysqli_fetch_assoc($sql2)){
                                      echo "<tr>";
                                      echo "<td align='center'>".$slno."</td>";
                                      echo "<td>".$row2['SubHead']."</td>";
                                      echo "<td align='center'>".$row2['accno']."</td>";
                                      echo "<td align='right'>".$row2['receiptcash']."</td>";
                                      echo "</tr>";
                                      $slno = $slno + 1;
                                      $total = $total + $row2['receiptcash'];
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
                        <button class='btn btn-info btn-fill' onclick="window.print()">Print</button>                              									                        
                      </div>
                      <div class="content table-responsive table-full-width" style="float:right;">
                        <a href='acc_mem_det.php?memid=<?php echo $memid; ?>'><button class='btn btn-info btn-fill'>Back</button></a>                              									                        
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

  
  $('#subid').change(function(){
    $('#but').prop('disabled', false);
    var subid = $("#subid").val();
    $.ajax({  
						type: "POST",  
						url: "acc_account_nos.php",  
						data: "subid="+ subid, 
						datatype: "html",
						success: function(data){
				    var res = data.split("-");
              
            if(res[0] == 0){
              alert(res[1]);
              $('#but').prop('disabled', true);
              $('#accno').val("");
              $('#balance').val("");
             
            }
            else if(res[0]==1){
              $('#accno').val("");
              $('#balance').val("");
              
            }  
            else{
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
    
