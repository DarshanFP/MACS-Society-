<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";
	include("accountssidepan.php");	
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $memid = $_POST['memid'];
        $memname = $_POST['memname'];
        $gender = $_POST['gender'];
        $pgroupid = $_POST['pgroupid'];
        $tgroupid = $_POST['tgroupid'];
        
		$today = date("Y-m-d");
        $user = $_SESSION['login_user'];
        				
        //mysqli_query($connection, "start transaction");
        
        $sql1 = mysqli_query($connection, "SELECT * FROM acc_mem_transfer_dummy WHERE memid = '$memid' AND status=1");
        $count1 = mysqli_num_rows($sql1);
        

        if($count1==0){
            $sql2 = mysqli_query($connection,"INSERT INTO acc_mem_transfer_dummy VALUES('$memid','$memname','$gender','$pgroupid','$tgroupid','$clusterid',1)");
        }
		  
        //mysqli_query($connection, "COMMIT");
	}
	else{
		header("location:accounts_member.php");
	}	
	
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
                                <?php
                                if($count1==0){
                                    echo "Member transfer request added successfully.";
                                }
                                else{
                                    echo "Member transfer request for ".$memname." already in queue."; 
                                }    
                                ?>									
								
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" >
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
                                            <a href="acc_mem_transfer.php"><button class="btn btn-info" type="button">
                                            <i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>											
										</div>
									</div>
								</form>		
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
	

<?php 
	include("footer.php");    
?>			