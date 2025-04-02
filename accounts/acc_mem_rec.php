<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");
  
  
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
														<span class="editable" id="username">
                              <form class = "form-group" action="acc_mem_receipt.php" method ="post">
                                <input type="text" name="member" id="member">
                                <button type = "submit" class = "btn btn-primary">
                                  <i class="ace-icon fa fa-check bigger-110"></i>Submit
                                </button>
                                <a href="accounts_member.php"><button class="btn btn-info" type="button" style="float:right;">
                                            
											<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>
                              </form>                            
                                
                            </span>
                            
													</div>

												</div>
												

											</div>

											<div class="space-20"></div>


                      
                      
	
												
                      
                      
                      
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
    
