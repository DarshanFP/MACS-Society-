<?php	    
	include("pdt_session.php");
	//$_SESSION['curpage']="president_loans";
    include("pdtsidepan.php");
    if(isset($_GET['flag'])){
        $flag = $_GET['flag'];
    }
    if($flag==1){
       $_SESSION['curpage']="president"; 
    }
    else{
       $_SESSION['curpage']="president_loans"; 
    }	
    
    $initiatedpropabs = mysqli_query($connection,"SELECT A.*, B.status FROM 
                                        (SELECT MacsPropID, COUNT(memid) as totloanees, SUM(proposedloan) as totloan FROM acc_loan_dummy GROUP BY MacsPropID) as A, 
                                        (SELECT MacsPropID, macsloanprop.status FROM macsloanprop) as B 
                                        WHERE A.MacsPropID = B.MacsPropID");
    $initiatecount = mysqli_num_rows($initiatedpropabs);

    
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Loan Disbursement Module - All Proposals
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">

                            <div class="col-md-8 ">
                                <div class="widget-box transparent">                                    

                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <div id="profile-feed-1" class="profile-feed">
                                                <div class="profile-activity clearfix">
                                                    <div>                                
                                                        <table style="font-size:13px" id="simple-table" class="table  table-bordered table-hover">
                                                            <thead>
                                                            <tr>														
                                                                <th style="text-align: center;">Sl.No</th>
                                                                <th style="text-align: center;">MACS Prop ID</th>
                                                                <th style="text-align: center;">No of Members</th>
                                                                <th  style="text-align: center;">Total Proposed Loan</th>
                                                                <th  style="text-align: center;">Status</th>                                                            
                                                            </tr>
                                                            </thead>	
                                                            <tbody>
                                                                <?php 
                                                                    $slno = 1;
                                                                    if($initiatecount > 0){
                                                                        while($loanproprow = mysqli_fetch_assoc($initiatedpropabs)){                                                                                                                                
                                                                            $status = $loanproprow['status'];
                                                                            echo "<tr>";
                                                                            echo "<td>".$slno."</td>";
                                                                            if($flag==1){
                                                                                if($status == 2)
                                                                                    echo "<td><a href='pdt_loan_prop_view.php?macspropid=".$loanproprow['MacsPropID']."&flag=1'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                                else if($status == 3 || $status == 4)
                                                                                    echo "<td><a href='pdt_loan_prop_bank_view.php?macspropid=".$loanproprow['MacsPropID']."&flag=1'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                            }
                                                                            else{
                                                                                if($status == 2)
                                                                                    echo "<td><a href='pdt_loan_prop_view.php?macspropid=".$loanproprow['MacsPropID']."'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                                else if($status == 3 || $status == 4)
                                                                                    echo "<td><a href='pdt_loan_prop_bank_view.php?macspropid=".$loanproprow['MacsPropID']."'><button class'btn-primary'>".$loanproprow['MacsPropID']."</button></a></td>";                                                                                    
                                                                            }
                                                                            echo "<td align='center'>".$loanproprow['totloanees']."</td>";
                                                                            echo "<td align='right'>".$loanproprow['totloan']."</td>";                                                                                                                                                               
                                                                            if($status == 2)
                                                                                echo "<td>Proposals Initiated</td>";
                                                                            else if($status == 3)
                                                                                echo "<td>Proposed Amount Sent to Bank</td>";                                                                        
                                                                            else if($status == 4)
                                                                                echo "<td>Proposals Closed </td>";                                                                        
                                                                            echo "</tr>";
                                                                            $slno = $slno +1;
                                                                        }
                                                                    }
                                                                    
                                                                ?>               
                                                            </tbody>
                                                        </table>																				
                                                    </div>
                                                    <div class="content table-responsive table-full-width" style="float:right;">
                                                        <?php
                                                        if($flag==1){    
                                                            echo "<a href='president.php'><button type='button' class='btn btn-info btn-fill'><i class='fa fa-arrow-left' style='margin-right:10px;'></i>Back</button></a>";
                                                        }else{
                                                            echo "<a href='president_loans.php'><button type='button' class='btn btn-info btn-fill'><i class='fa fa-arrow-left' style='margin-right:10px;'></i>Back</button></a>"; 
                                                        }
                                                        ?>
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

