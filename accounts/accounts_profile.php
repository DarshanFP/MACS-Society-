<?php	  
	include("accounts_session.php");
	$_SESSION['curpage']="president";
	include("accountssidepan.php");
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Employee Profile
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->

                                

								<div class="hr dotted"></div>

								<div>
									<div id="user-profile-1" class="user-profile row">
										<div class="col-xs-12 col-sm-3 center">
											<div>
												<span class="profile-picture">
													<img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="../assets/images/avatars/profile-pic.jpg" />
												</span>

												<div class="space-4"></div>

												<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
													<div class="inline position-relative">
														<a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
															<i class="ace-icon fa fa-circle light-green"></i>
															&nbsp;
															<span class="white"><?php echo $row['empname']; ?></span>
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
														<span class="editable" id="username"><?php echo $row['empid'] ?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Aadhaar </div>

													<div class="profile-info-value">
														<span class="editable" id="age"><?php echo $row['empaadhaar']; ?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Date of Birth </div>

													<div class="profile-info-value">
														<span class="editable" id="age"><?php echo $row['empdob']; ?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Date of Joining </div>

													<div class="profile-info-value">
														<span class="editable" id="signup"><?php echo $row['empdoj']; ?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Mobile No </div>

													<div class="profile-info-value">
														<span class="editable" id="login"><?php echo $row['empmobile']; ?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Address </div>

													<div class="profile-info-value">
														<span class="editable" id="about"><?php echo $row['empaddress'];?></span>
													</div>
												</div>
											</div>

											<div class="space-20"></div>

											

											<div class="hr hr2 hr-double"></div>

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