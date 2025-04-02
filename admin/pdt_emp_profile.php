<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_employee";
include("pdtsidepan.php");;
if(isset($_GET['empid'])){
		$empid = $_GET['empid'];
		$sql = "SELECT employee.empid, employee.empname, employee.empdob, employee.empaddress, employee.empmobile FROM employee WHERE employee.empid = '$empid' AND employee.empstatus = 1";
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);	
  
    $sql1 = mysqli_query($connection,"SELECT ClusterName, allot.ClusterID FROM allot, cluster WHERE allot.EmpID = '$empid' AND allot.Status = 1 AND allot.ClusterID = cluster.ClusterID");
    $count1 = mysqli_num_rows($sql1);
			
	}
?>
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Employee Profile Page
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<div>
									<div id="user-profile-1" class="user-profile row">
										<div class="col-xs-12 col-sm-3 center">
											<div>
												<!-- <span class="profile-picture">
													<img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="assets/images/avatars/profile-pic.jpg" />
												</span> -->

												<div class="space-4"></div>

												<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
													<div class="inline position-relative">
														<a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
															<i class="ace-icon fa fa-circle light-green"></i>
															&nbsp;
															<span class="white"><?php echo $row['empname'];?></span>
														</a>													
													</div>
												</div>
											</div>

											<div class="space-6"></div>																						

											<div class="hr hr16 dotted"></div>
										</div>

										<div class="col-xs-12 col-sm-9">											

											<div class="space-12"></div>

											<div class="profile-user-info profile-user-info-striped">
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Employee ID </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $empid;?> </span>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Employee Name </div>

													<div class="profile-info-value">
														<span class="editable" id="username"> <?php echo $row['empname'];?> </span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Date of Birth </div>

													<div class="profile-info-value">
														<span class="editable" id="age"><?php echo $row['empdob'];?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Address </div>

													<div class="profile-info-value">
														<span class="editable" id="signup"><?php echo $row['empaddress'];?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Mobile </div>

													<div class="profile-info-value">
														<span class="editable" id="login"><?php echo $row['empmobile'];?> </span>
													</div>
												</div>												
											</div>

											<div class="space-20"></div>

											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Clusters Assigned
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
																				<th class="center">Name of the Cluster</th>
																				<th class="center">Status</th>																				
																			</tr>
																		</thead>

																		<tbody>
																		<?php if($count1>0){
																			$slno=1;
																			while($row1 = mysqli_fetch_assoc($sql1))
																			{ 	
																				echo "<tr><td class='center'>".$slno."</td>"; 
																				echo "<td class='center'>".$row1['ClusterName']."</td>";																				
																				echo "<td class='center'><a href='pdt_cluster_allot_close.php?empid=".$empid."&&clusterid=".$row1['ClusterID']."'<i class='ace-icon fa fa-times orange'></i></a></td>";
																				$slno = $slno +1;					
																			}				
																		}
																		?>
																			
																		</tbody>
																	</table>																				
																</div>
                                                                 <a href="president_employee.php"><button class="btn btn-primary" style="float:right;">
                                                                    <i class="fa fa-arrow-left" style="margin-right:10px;"></i>Back
                                                                 </button></a>
															</div>
														</div>
													</div>
												</div>
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