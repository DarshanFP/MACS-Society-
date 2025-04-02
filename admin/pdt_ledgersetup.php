<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_hoa";
	include("pdtsidepan.php");
  $sql1 	= "Select
				  subhead,roi,doe			  
			  From
				  acc_subhead,acc_majorheads,acc_mainheads,acc_rateofinterest 
				Where
				  acc_subhead.majorid = acc_majorheads.majorid and acc_majorheads.mainid = acc_mainheads.mainid and (acc_majorheads.mainid = 1 or acc_majorheads.mainid = 3) and acc_subhead.subid = acc_rateofinterest.subheadid  and acc_rateofinterest.status=1 ORDER BY acc_majorheads.mainid"; 	
	$result1 = mysqli_query($connection,$sql1) or die(mysqli_error());
	$count1 = mysqli_num_rows($result1);

	$sql2 	= "Select
				  subhead,roi,doe			  
			  From
				  acc_subhead,acc_majorheads,acc_mainheads,acc_rateofinterest 
				Where
				  acc_subhead.majorid = acc_majorheads.majorid and acc_majorheads.mainid = acc_mainheads.mainid and (acc_majorheads.mainid = 2 or acc_majorheads.mainid = 4) and acc_subhead.subid = acc_rateofinterest.subheadid and acc_rateofinterest.status=1 ORDER BY acc_majorheads.mainid"; 	
	$result2 = mysqli_query($connection,$sql2) or die(mysqli_error());
	$count2 = mysqli_num_rows($result2);

	$sql3 	= "select roi from acc_rateofinterest,acc_subhead where subheadid=subid"; 	
	$result3 = mysqli_query($connection,$sql3) or die(mysqli_error());
	$row3 = mysqli_fetch_assoc($result3);
	$roi = $row3['roi'];
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Rate of Interest
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
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
													<th class="center">Rate of Interest %</th>
													<th class="center">Date of effect</th>
													<th class="center">Edit</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count1>0){
												$slno=1;
												while($row1 = mysqli_fetch_assoc($result1))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";?>
													<td onclick = "window.open('/clerk/roidetails.php?subhead=<?php echo $row1['subhead']; ?>', '_blank', 'resizable=yes, location=no, scrollbars=yes, width=500, height=400, top=150, left=500'); return false;" > <a href=""> <?php echo $row1['subhead'] ?> </a></td>
													<?php //echo "<td style='width:100px;'>".$row1['subhead']."</td>";
													echo "<td style='width:100px;' class='center'>".$row1['roi']."</td>";
													echo "<td style='width:100px;' class='center'>".$row1['doe']."</td>";
													//echo "<td width='50px' class='center'><a href='acc_ledgersetup_new.php?subhead=".$row1['subhead']."'><button class='btn btn-xs btn-success' >Setup</button></a></td>";
													echo "<td width='50px' class='center'> <a href='acc_ledgersetup_edit.php?subhead=".$row1['subhead']."'>
															  <button class='btn btn-xs btn-info'>
																<i class='ace-icon fa fa-pencil bigger-120'></i>
															  </button>
															  </a></td>";
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
													<th class="center">Rate of Interest %</th>
													<th class="center">Date of effect</th>
													<th class="center">Edit</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count2>0){
												$slno=1;
												while($row2 = mysqli_fetch_assoc($result2))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>";?>
													<td onclick = "window.open('/clerk/roidetails.php?subhead=<?php echo $row2['subhead']; ?>', '_blank', 'resizable=yes, location=no, scrollbars=yes, width=500, height=400, top=150, left=500'); return false;" > <a href=""> <?php echo $row2['subhead'] ?> </a></td> -->
													<?php //echo "<td width='100px'>".$row2['subhead']."</td>";
													echo "<td width='100px' class='center'>".$row2['roi']."</td>";
													echo "<td style='width:100px;' class='center'>".$row1['doe']."</td>";
													//echo "<td width='50px'class='center'><a href='acc_ledgersetup_new.php?subhead=".$row2['subhead']."'><button class='btn btn-xs btn-success'>Setup</button></a></td>";
													echo "<td width='50px' class='center'> <a href='acc_ledgersetup_edit.php?subhead=".$row2['subhead']."'>
															  <button class='btn btn-xs btn-info'>
																<i class='ace-icon fa fa-pencil bigger-120'></i>
															  </button>
															  </a></td>";
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
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			