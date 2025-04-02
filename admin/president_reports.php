<?php	  
	include("pdt_session.php");
	$_SESSION['curpage']="president_reports";
	include("pdtsidepan.php");	
?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header">
							<h1>
								Reports
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
                            <div class="col-sm-6">
                                <div class="dd" id="nestabl                                                                                             -id="1">
                                        <li class="dd-item" data-id="1">
                                            <a href="pdt_reports_ind_led.php">
                                                <div class="dd-handle">
                                                    Individual Ledgers
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dd-item" data-id="2">
                                            <a href="pdt_rep_group_ledger.php">
                                                <div class="dd-handle">
                                                    Group Wise Ledgers
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="2">
                                            <a href="pdt_rep_cluster_ledger.php">
                                                <div class="dd-handle">
                                                    Cluster Wise Ledgers
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="3">
                                            <a href="president_reports_cb.php">
                                                <div class="dd-handle">
                                                    Cash Book
                                                </div>
                                            </a>    
                                        </li>		

                                        <li class="dd-item" data-id="4">
                                            <a href="president_reports_rp.php">
                                                <div class="dd-handle">
                                                    Receipts & Payments
                                                </div>
                                            </a>   
                                        </li>
                                        <li class="dd-item" data-id="4">
                                            <a href="pdt_rep_loanstatement.php">
                                                <div class="dd-handle">
                                                    Loan Issue Statement
                                                </div>
                                            </a>   
                                        </li>  
                                </div>
                            </div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			