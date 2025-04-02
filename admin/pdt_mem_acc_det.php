<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_member";
include("pdtsidepan.php");;
if(isset($_GET['accno'])){
    $accno = $_GET['accno'];
    
    if($accno[0] == 'M'){
      
      $memid = $accno;
      $sql = mysqli_query($connection,"SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE accno = '$accno' 
                                        AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1");
      $count = mysqli_num_rows($sql);
      $sql2 = mysqli_query($connection,"SELECT SubHead FROM acc_sharecapital, acc_subhead WHERE memid = '$accno' AND SubID = subheadid");
      $row2 = mysqli_fetch_assoc($sql2);
    }
    else if($accno[0] == 'D'){
      $memid = $_GET['memid'];
      $sql = mysqli_query($connection,"SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE accno = '$accno'
                                      AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1");
      $count = mysqli_num_rows($sql);      
      
      $sql2 = mysqli_query($connection,"SELECT SubHead FROM acc_deposits, acc_subhead WHERE depositno = '$accno' AND SubID = subheadid");
      $row2 = mysqli_fetch_assoc($sql2);
    } 
    else if($accno[0] == 'L'){
      $memid = $_GET['memid'];
      $sql = mysqli_query($connection,"SELECT acc_cashbook.* FROM acc_cashbook, acc_transactions WHERE accno = '$accno'
                                      AND acc_cashbook.TransID = acc_transactions.TransID AND acc_transactions.TransStatus = 1");
      $count = mysqli_num_rows($sql);   
      
      $sql2 = mysqli_query($connection,"SELECT SubHead FROM acc_loans, acc_subhead WHERE loanno = '$accno' AND SubID = subheadid");
      $row2 = mysqli_fetch_assoc($sql2);
      
    }
		
		$sql1 = "SELECT members.*, GroupName, ClusterName from members, groups, cluster 
    where memid = '$memid' and members.memgroupid = groups.GroupID and cluster.ClusterID = groups.ClusterID";
		$result1 = mysqli_query($connection, $sql1) or die(mysqli_error($sql1));
    $count1 = mysqli_num_rows($result1);
    $row1 = mysqli_fetch_assoc($result1);
  
  
     
  
    	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Member <?php echo $row2['SubHead']; ?> Statement
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
                                                    <div class="profile-info-name"> Bank Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankname'];?> </span>
                                                    </div>
                          
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Member Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memname'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank IFSC</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankifsc'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Member Group </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['GroupName'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Account No.</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaccountno'];?> </span>
													</div>
												</div>
                        
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['memphone'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name">Bank Address</div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['bankaddress'];?> </span>
													</div>
												</div>

											         
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Cluster Name </div>
													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['ClusterName'];?> </span>
                                                    </div>
                                                    <div class="profile-info-name"></div>

													<div class="profile-info-value">
														<span class="editable" id="username"></span>
													</div>
                                                </div>
                                                
                        
                                            </div>

											<div class="space-20"></div>

																						<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														<?php echo $row2['SubHead']; ?> 
													</h4>						                          
												</div>

												<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="profile-feed-1" class="profile-feed">
															<div class="profile-activity clearfix">
																<div>
												
																	<table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
																		<thead>
																			<tr>													
																				<th class="detail-col">S.No.</th>
																				<th class="center">Trans ID</th>
																				<th class="center">Date</th>
                                        <th class="center">Opening</th>
                                        <th class="center">Receipt</th>
                                        <th class="center">Payment</th>
                                        <th class="center">Closing</th>
                                        <?php 
                                          if($accno[0] == 'D'){
                                            echo "<th class='center'>Int Paid</th>";
                                            } 
                                          else if ($accno[0] == 'L'){
                                            echo "<th class='center'>Int Received</th>";
                                          }
                                          else if ($accno[0] == 'M'){
                                            
                                          }
                                        ?>
                                        
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                        if($count>0){
                                          $slno = 1;                  
                                          $ob = 0;
                                          while($row = mysqli_fetch_assoc($sql)){
                                            
                                            $subid = $row['subheadid'];
                                            $sql3 = mysqli_query($connection,"SELECT SubHeadModule FROM acc_subhead WHERE SubID = '$subid'");
                                            $row3 = mysqli_fetch_assoc($sql3);
                                            
                                            if($row3['SubHeadModule'] == 9 || $row3['SubHeadModule'] == 8){
                                              $recept = $row['receiptcash']+$row['receiptadj'];
                                              $payment = $row['paymentcash']+$row['paymentadj'];
                                              echo "<tr><td class='center'>".$slno."</td>";
                                              echo "<td class='center'>".$row['TransID']."</td>";
                                              echo "<td class='center'>".$row['date']."</td>";
                                              echo "<td class='center'>".number_format($ob,2)."</td>";
                                              echo "<td class='center'>".number_format(0,2)."</td>";
                                              echo "<td class='center'>".number_format(0,2)."</td>";
                                              echo "<td class='center'>".number_format($ob,2)."</td>";
                                              if($accno[0] == 'M'){
                                                echo "</tr>";
                                              }
                                              else if($accno[0]=='D'){
                                                echo "<td class='center'>".number_format($payment,2)."</td></tr>";
                                              }
                                              else if($accno[0]=='L'){
                                                echo "<td class='center'>".number_format($recept,2)."</td></tr>";
                                              }
                                                
                                            }
                                            else{
                                              $recept = $row['receiptcash']+$row['receiptadj'];
                                              $payment = $row['paymentcash']+$row['paymentadj'];
                                              if($accno[0] == 'L'){
                                                $cb = $ob - $recept + $payment;  
                                              }
                                              else{
                                                $cb = $ob + $recept - $payment;  
                                              }                                              
                                              echo "<tr><td class='center'>".$slno."</td>";
                                              echo "<td class='center'>".$row['TransID']."</td>";
                                              echo "<td class='center'>".$row['date']."</td>";
                                              echo "<td class='center'>".number_format($ob,2)."</td>";
                                              echo "<td class='center'>".number_format($recept,2)."</td>";
                                              echo "<td class='center'>".number_format($payment,2)."</td>";
                                              echo "<td class='center'>".number_format($cb,2)."</td>";
                                              if($accno[0] == 'M'){
                                                echo "</tr>";
                                              }
                                              else{
                                                echo "<td class='center'>".number_format(0,2)."</td></tr>";  
                                              }
                                              
                                              $ob = $cb;                                              
                                              
                                            }
                                            
                                            
                                            
                                          }
                                        }                                        
                                        
																				
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
												</div>
                        <a href="pdt_mem_det.php?memid=<?php echo $memid; ?>"><button class="btn btn-primary" style="float:right;">
                            <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                          </button></a>
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