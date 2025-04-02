<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_ob";
	include("pdtsidepan.php");
	$sql1 	= "Select
				  SubID, SubHead, SubHeadModule				  
			  From
				  acc_subhead,acc_majorheads,acc_mainheads 
				Where
				  acc_subhead.majorid = acc_majorheads.majorid and acc_majorheads.mainid = acc_mainheads.mainid and acc_majorheads.mainid = 1 ORDER BY acc_majorheads.majorid"; 	
	$result1 = mysqli_query($connection,$sql1) or die(mysqli_error());
	$count1 = mysqli_num_rows($result1);

	$sql2 	= "Select
				  SubID, SubHead, SubHeadModule				  
			  From
				  acc_subhead,acc_majorheads,acc_mainheads 
				Where
				  acc_subhead.MajorID = acc_majorheads.majorid and acc_majorheads.mainid = acc_mainheads.mainid and acc_majorheads.mainid = 2  ORDER BY acc_majorheads.majorid"; 	
	$result2 = mysqli_query($connection,$sql2) or die(mysqli_error());
	$count2 = mysqli_num_rows($result2);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Opening Balances : Head of Accounts
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<div class="col-sm-6">
										<div class="widget-box widget-color-blue2">
											<div class="widget-header">
												<h4 class="widget-title lighter smaller">
													Liabilities													
												</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-8">
												<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col" style="width=200px;">SubHead</th>
													<th class="center">OB</th>
													<th class="center">Edit</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count1>0){
												$slno=1;
												while($row1 = mysqli_fetch_assoc($result1))
												{ 
                          $subid = $row1['SubID'];
                          
                          $ob = mysqli_query($connection, "SELECT sum(OB) FROM acc_subhead_ob
                                          WHERE acc_subhead_ob.SubID = '$subid' GROUP BY SubID");
                          $obcount = mysqli_num_rows($ob);
                          $ob = mysqli_fetch_assoc($ob);
                          $ob = $ob['sum(OB)'];
                          
                          
                          $submodule = $row1['SubHeadModule'];	
													echo "<tr><td class='center'>".$slno."</td>"; 	
													echo "<td style='width:200px;'>".$row1['SubHead']."</td>";
													echo "<td align='right'>".number_format($ob,2)."</td>";
                          
                          if(($submodule == 1 || $submodule == 7) && $obcount != 1)
													  echo "<td class='center'><button class='btn btn-xs btn-info'>
																<i class='ace-icon fa fa-pencil bigger-120'></i>
															  </button></td>";
                          else
                            echo "<td></td>";
													echo "</tr>";
													$slno = $slno +1;					
												}				
											}
											?>
											</tbody>
										</table>
													
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="widget-box widget-color-green2">
											<div class="widget-header">
												<h4 class="widget-title lighter smaller">
													Assets
													
												</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-8">
												<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col" width="200px">SubHead</th>
													<th class="center">OB</th>
													<th class="center">Edit</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count2>0){
												$slno=1;
												while($row2 = mysqli_fetch_assoc($result2))
												{ 	
													$subid = $row2['SubID'];
                          
                          $ob = mysqli_query($connection, "SELECT sum(OB) FROM acc_subhead_ob
                                          WHERE acc_subhead_ob.SubID = '$subid' GROUP BY SubID");
                          $obcount = mysqli_num_rows($ob);
                          $ob = mysqli_fetch_assoc($ob);
                          $ob = $ob['sum(OB)'];                          
                          $submodule = $row2['SubHeadModule'];
                          echo "<tr><td class='center'>".$slno."</td>"; 	
													echo "<td width='200px'>".$row2['SubHead']."</td>";
													echo "<td align='right'>".number_format($ob,2)."</td>";
                          
													if(($submodule == 1 || $submodule == 7) && $obcount != 1)
													  echo "<td class='center'><a href='president_ob_heads_edit.php?subid=".$subid."'><button class='btn btn-xs btn-info'>
																<i class='ace-icon fa fa-pencil bigger-120'></i>
															  </button></a></td>";
                          else
                            echo "<td></td>";
													echo "</tr>";
													$slno = $slno +1;					
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

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			