<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_cluster";
    include("pdtsidepan.php");
    if(isset($_GET['clusterid'])){
        $clusterid = $_GET['clusterid'];
        /*$sql = "SELECT A.GroupName, A.GroupID, deposits, loans FROM (SELECT  sum(cb) as deposits, GroupName, GroupID FROM acc_deposits, members, groups
                              WHERE acc_deposits.memid = members.memid 
                              AND members.memgroupid = groups.GroupID 
                              AND groups.ClusterID = '$clusterid' GROUP BY GroupID) AS A, 
                                (SELECT  sum(cb) as loans, GroupName, GroupID FROM acc_loans, members, groups
                                                            WHERE acc_loans.memid = members.memid 
                                                            AND members.memgroupid = groups.GroupID 
                                                            AND groups.ClusterID = '$clusterid' GROUP BY GroupID) AS B
                                WHERE A.GroupID = B.GroupID";*/

        $sql = "SELECT A.ClusterID,A.GroupName, A.GroupID, gs, ps, ms, mid, loans 
                    FROM (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as gs 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 2) AS gsdeposit
						 ON members.memid = gsdeposit.memid GROUP BY GroupID) AS A, 
						 
						 (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as ps 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 3) AS spdeposit
						 ON members.memid = spdeposit.memid GROUP BY GroupID) AS B, 
						 
						 (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as ms 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 4) AS mdeposit
						 ON members.memid = mdeposit.memid GROUP BY GroupID) AS C, 
						 
						 (SELECT D.ClusterID,GroupName, GroupID ,sum(cb) as mid 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS D 
                         LEFT JOIN members ON D.GroupID = members.memgroupid 
                         LEFT JOIN (SELECT * FROM acc_deposits WHERE subheadid = 10) AS middeposit
						 ON members.memid = middeposit.memid GROUP BY GroupID) AS F, 
						 
                         (SELECT E.ClusterID,GroupName, GroupID ,sum(cb) as loans 
                         FROM (SELECT * FROM groups WHERE ClusterID = '$clusterid') AS E 
                         LEFT JOIN members ON E.GroupID = members.memgroupid 
                         LEFT JOIN acc_loans ON members.memid = acc_loans.memid GROUP BY GroupID) AS G 
						 
                         WHERE A.GroupID = B.GroupID AND A.GroupID = C.GroupID AND A.GroupID = F.GroupID AND A.GroupID = G.GroupID";                        
        $result = mysqli_query($connection, $sql);
        $count = mysqli_num_rows($result);

        $sql1 = "SELECT * from cluster where ClusterID ='$clusterid'";
        $result1 = 	mysqli_query($connection, $sql1);
        $row1 = mysqli_fetch_assoc($result1);
        $clustername = $row1['ClusterName'];
        }
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								 Cluster Details
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
													<div class="profile-info-name"> Cluster ID </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $clusterid;?> </span>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Cluster Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row1['ClusterName'];?> </span>
													</div>
												</div>
											</div>

											<div class="space-20"></div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Groups Under <?php echo $row1['ClusterName']; ?> Cluster
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
																				<th class="center">Group ID</th>
																				<th class="center">Group Name</th>													
                                                                                <th class="center">Members</th>
                                                                                <th class="center">General Savings</th>
                                                                                <th class="center">Special Savings</th>
                                                                                <th class="center">Marraige Savings</th>
                                                                                <th class="center">Mutual Aid</th>
                                                                                <th class="center">Total Loans</th>                                                                                
                                                                                <th class="center">Leave</th>
                                        
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                                                            
                                                                        if($count>0){
                                                                            $slno=1;
                                                                            $gsdeposit = 0;
                                                                            $psdeposit = 0;
                                                                            $msdeposit = 0;
                                                                            $middeposit = 0;
                                                                            $loan = 0;
																			while($row = mysqli_fetch_assoc($result))
																			{ 	
                                                                                $groupid = $row['GroupID'];
                                                                                $sql2 = mysqli_query($connection,"SELECT * FROM members WHERE memgroupid = '$groupid' AND memstatus = 1");
                                                                                $count2 = mysqli_num_rows($sql2); 
                                        
																				echo "<tr><td class='center'>".$slno."</td>"; 
												                                echo "<td><a href='pdt_group_det.php?groupid=".$groupid."'>".$groupid."</a></td>";					                          
																				echo "<td class='center'>".$row['GroupName']."</td>";
                                                                                echo "<td class='center'>".$count2."</td>";
                                                                                echo "<td align = 'right'>".number_format($row['gs'],2,'.','')."</td>";
                                                                                echo "<td align = 'right'>".number_format($row['ps'],2,'.','')."</td>";
                                                                                echo "<td align = 'right'>".number_format($row['ms'],2,'.','')."</td>";
                                                                                echo "<td align = 'right'>".number_format($row['mid'],2,'.','')."</td>";
                                                                                echo "<td align = 'right'>".number_format($row['loans'],2,'.','')."</td>";
                                                                                echo "<td class='center'><a href='pdt_cluster_group_allot_close.php?groupid=".$row['GroupID']."&&clusterid=".$clusterid."'<i class='ace-icon fa fa-times orange'></i></a></td></tr>";
                                                                                $gsdeposit = $gsdeposit + $row['gs'];
                                                                                $psdeposit = $psdeposit + $row['ps'];
                                                                                $msdeposit = $msdeposit + $row['ms'];
                                                                                $middeposit = $middeposit + $row['mid'];
                                                                                $loan = $loan + $row['loans'];
																				$slno = $slno +1;					
                                                                            }				
                                                                            echo "<tr><td colspan='4'>Total</td>";
                                                                            echo "<td align ='right'>".number_format($gsdeposit,2,'.','')."</td>";
                                                                            echo "<td align ='right'>".number_format($psdeposit,2,'.','')."</td>";
                                                                            echo "<td align ='right'>".number_format($msdeposit,2,'.','')."</td>";
                                                                            echo "<td align ='right'>".number_format($middeposit,2,'.','')."</td>";
                                                                            echo "<td align ='right'>".number_format($loan,2,'.','')."</td><td></td></tr>";

																		}
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
                      
											<div class="space-6"></div>
                      
                                            <h5>Assign Group</h5>
                                            <div class="content table-responsive table-full-width">
                                            <table class="table ">											
                                                <tbody>
                                                <tr> <form action="pdt_cluster_group_allot.php" method ="post">
                                                    <td>
                                                    <select name="groupid" class="form-control" required>								
                                                        <?php 
                                                        
                                                        $sql6 = "SELECT GroupID, GroupName FROM groups WHERE ClusterID = '0'"; 
                                                                    
                                                        $result6 = mysqli_query($connection,$sql6);
                                                        while ($row6 = mysqli_fetch_assoc($result6)) 
                                                        echo "<option value ='".$row6['GroupID']."'>".$row6["GroupName"]."</option>";
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="clusterid" value="<?php echo $clusterid;?>"> 
                                                    </td>
                                                    <?php 
                                                    if($clustername=='Head Office')
                                                        echo '<td><button class="btn btn-info btn-fill" disabled>Assign Group</button></td>';  
                                                    else 
                                                        echo '<td><button type="submit" class="btn btn-info btn-fill">Assign Group</button></td>';                                                                         
                                                    
                                                    ?>
                                                                                                        
                                                    </form>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </div>

                      <div class="space-6"></div>
                      
                      
                      
                      
                      <div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-user orange"></i>
														Working Employee
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
																				<th class="center">Employee ID</th>
																				<th class="center">Employee Name</th>													
																				<th class="center">From</th>
                                        <th class="center">Leave</th>
																			</tr>
																		</thead>

																		<tbody>
																		<?php 
                                      $sql3 = mysqli_query($connection,"SELECT employee.*, allot.DOE FROM employee, allot 
                                                      WHERE allot.ClusterID = '$clusterid' and allot.EmpID = employee.empid and allot.Status = 1");
                                      $count3 = mysqli_num_rows($sql3);
                                                                          
																			$slno=1;
																			while($row3 = mysqli_fetch_assoc($sql3))
																			{ 	
                                         
                                        
																				echo "<tr><td class='center'>".$slno."</td>"; 
																				echo "<td class='center'>".$row3['empid']."</td>";																				
																				echo "<td class='center'>".$row3['empname']."</td>";
                                                                                echo "<td class='center'>".$row3['DOE']."</td>";
																				echo "<td class='center'><a href='pdt_cluster_allot_close.php?empid=".$row3['empid']."&&clusterid=".$clusterid."'<i class='ace-icon fa fa-times orange'></i></a></td>";
																				$slno = $slno +1;					
																			}				
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="space-6"></div>

                      
                      <h5>Assign Employees</h5>
                      <div class="content table-responsive table-full-width">
                       <table class="table ">											
                        <tbody>
                          <tr> <form action="pdt_cluster_allot.php" method ="post">
                            <td>
                              <select name="empid" class="form-control">								
                                <?php 
                                 
                                  $sql4 = "SELECT
                                              employee.empname,
                                              allot.EmpID
                                            FROM
                                              allot, employee
                                            WHERE  
                                              allot.EmpID = employee.empid AND employee.empstatus = 1 AND allot.ClusterID = '' AND allot.Status = 0"; 
                                              
                                  $result4 = mysqli_query($connection,$sql4);
                                  while ($row4 = mysqli_fetch_assoc($result4)) 
                                  echo "<option value ='".$row4['EmpID']."'>".$row4["empname"]."</option>";
                                ?>
                              </select>
                              <input type="hidden" name="clusterid" value="<?php echo $clusterid;?>"> 
                            </td>
                            <?php                             
                            if($count3 == 0 && $clustername<>'Head Office')
                              echo '<td><button type="submit" class="btn btn-info btn-fill">Assign Employee</button></td>';  
                            else 
                              echo '<td><button type="submit" class="btn btn-info btn-fill" disabled >Assign Employee</button></td>';  
                            ?>
                            													
                            </form>
                          </tr>
                        </tbody>
                      </table>
                      </div>
                        <a href="president_cluster.php"><button class="btn btn-primary" style="float:right;">
                            <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                        </button></a>

											<div class="space-6"></div>
                      
                      
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