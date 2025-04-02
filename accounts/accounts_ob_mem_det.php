<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_ob";
	include("accountssidepan.php");
  if(isset($_GET['memid'])){
		$memid = $_GET['memid'];
		$sql1 = "SELECT members.*, GroupName from members, groups where memid = '$memid' and members.memgroupid = groups.GroupID";
		$result1 = mysqli_query($connection, $sql1);
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
    
    $sql2 = mysqli_query($connection,"SELECT SubID, SubHead FROM acc_subhead WHERE SubHeadModule = 2 OR SubHeadModule = 3 OR SubHeadModule = 4");
    $count2 = mysqli_num_rows($sql2);
    
    $sql4 = mysqli_query($connection,"SELECT SubHead, acc_mem_ob.* FROM acc_subhead, acc_mem_ob
                                  WHERE acc_mem_ob.memid = '$memid' AND acc_mem_ob.subheadid = acc_subhead.SubID");
    $count4 = mysqli_num_rows($sql4);
  }

?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balances - Member
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
                        <table class="table">	
                          <thead>
                            <th>Sl.No</th>
                            <th>Head of Account</th>										                            
                            <th>Balance</th>                            
                            <th>Monthly Amount</th> 
                            <th></th>
                          </thead>	
                          <tbody>
                            <tr> <form action="accounts_ob_mem_add.php" class="form-horizontal" method ="post">
                              <td text-align="center"> 1 </td>
                              <td>												
                                <select class="form-group" id="subid" name="subid"  style="width:200px; " required >	
                                  <option value=''> </option>
                                  <?php 
                                    while ($row2 = mysqli_fetch_assoc($sql2)){
                                      $subid = $row2['SubID'];
                                      $sccheck = mysqli_query($connection,"SELECT * FROM acc_sharecapital WHERE memid = '$memid' AND subheadid ='$subid'");
                                      $sccheckcount = mysqli_num_rows($sccheck);
                                      $dcheck = mysqli_query($connection,"SELECT * FROM acc_deposits WHERE memid = '$memid' AND subheadid ='$subid'");
                                      $dcheckcount = mysqli_num_rows($dcheck);
                                      $lcheck = mysqli_query($connection,"SELECT * FROM acc_loans WHERE memid = '$memid' AND subheadid ='$subid'");
                                      $lcheckcount = mysqli_num_rows($lcheck);
                                      $checkcount = $sccheckcount + $dcheckcount + $lcheckcount;
                                      if($checkcount<1)
                                        echo "<option value ='".$row2['SubID']."'>".$row2['SubHead']."</option>";								  
                                    } 												                                    
                                  ?>
                                </select>												
                              </td>                              
                              <td><input type="number" class="form-control" name ="balance"  id="balance" required/>
                                  <input type="hidden" name ="memid" id="memid" value="<?php echo $memid;?>" /> </td>											                              
                              <td><input type="number" class="form-control" name ="installment"  id="installment" /></td>											                              
                              <td><button type="submit" id="but" class="btn btn-info btn-fill" onclick="return confirm('&quot Please Check Once, Once submitted cannot be revoked. &quot'); return false;">Submit</button></td>													
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
                                <th style="text-align:center">Balance</th> 
                                <th style="text-align:center">Monthly Amount</th>                                                                  	
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
                                      echo "<td align='right'>".number_format($row4['ob'],2)."</td>";                                      
                                      echo "<td align='right'>".number_format($row4['installment'],2)."</td>";                                      
                                      echo "</tr>";
                                      $slno = $slno + 1;                                      
                                    }
                                  }
                                ?>
                                
                              </tbody>
                          </table>

                      </div>
                      <div class="content table-responsive table-full-width" style="float:right;">
                        <a href='accounts_ob_members.php'><button class='btn btn-info btn-fill'>Back</button></a>
                      </div>


                      
                      
                      
                      
                      
                      


                      
                      
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