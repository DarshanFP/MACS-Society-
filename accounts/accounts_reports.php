<?php	    
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_reports";
	include("accountssidepan.php");
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
                                            <a href="acc_reports_ind_led.php">
                                                <div class="dd-handle">
                                                    Individual Ledgers
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dd-item" data-id="2">
                                            <a href="acc_rep_group_ledger.php">
                                                <div class="dd-handle">
                                                    Group Wise Ledgers
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="2">
                                            <a href="acc_rep_cluster_ledger.php">
                                                <div class="dd-handle">
                                                    Cluster Wise Ledgers
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="3">
                                            <a href="acc_rep_cashbook.php">
                                                <div class="dd-handle">
                                                    Cash Book
                                                </div>
                                            </a>    
                                        </li>		

                                        <li class="dd-item" data-id="4">
                                            <a href="acc_rep_rp.php">
                                                <div class="dd-handle">
                                                    Receipts & Payments
                                                </div>
                                            </a>   
                                        </li>
                                        <li class="dd-item" data-id="1">
                                            <a href="acc_rep_receipt.php">
                                                <div class="dd-handle">
                                                    Receipt Print
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dd-item" data-id="2">
                                            <a href="acc_rep_sc_state.php">
                                                <div class="dd-handle">
                                                    Share Capital Statement
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dd-item" data-id="2">
                                            <a href="acc_rep_loan_state.php">
                                                <div class="dd-handle">
                                                    Loan Statement
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dd-item" data-id="3">
                                            <a href="acc_rep_dep_state.php">
                                                <div class="dd-handle">
                                                    Deposit Statement
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="4">
                                            <a href="acc_rep_sundebtor_state.php">
                                                <div class="dd-handle">
                                                    Sundry Debtors Statement
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="5">
                                            <a href="acc_rep_suncreditor_state.php">
                                                <div class="dd-handle">
                                                    Sundry Creditors Statement
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="6">
                                            <a href="acc_rep_ledger.php">
                                                <div class="dd-handle">
                                                    Ledger Statements
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="7">
                                            <a href="acc_rep_dcbs.php">
                                                <div class="dd-handle">
                                                    DCB Statements
                                                </div>
                                            </a>    
                                        </li>
                                        <li class="dd-item" data-id="7">
                                            <a href="acc_rep_loanstatement.php">
                                                <div class="dd-handle">
                                                    Loan Issue Statement
                                                </div>
                                            </a>    
                                        </li>
                                          </div>
                                </div>
                            </div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			