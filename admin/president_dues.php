<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_dues";
	include("pdtsidepan.php");
	$sql1 	= "SELECT * FROM acc_dues WHERE duestype = 1"; 	
	$result1 = mysqli_query($connection,$sql1) or die(mysqli_error());
	$count1 = mysqli_num_rows($result1);

	$sql2 	= "SELECT * FROM acc_dues WHERE duestype = 2"; 	 	
	$result2 = mysqli_query($connection,$sql2) or die(mysqli_error());
	$count2 = mysqli_num_rows($result2);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Due to & Due by
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
													Due to / Sundry Debtors
													<span class="smaller-80" style="float:right"><a href='pdt_dues_new.php?id=1'><button class="btn btn-info">Add New Head</button></a></span>
												</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-8">
												<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col" style="width=200px;">Head</th>
													<th class="center">Details</th>
													<th class="center">Remarks</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count1>0){
												$slno=1;
												while($row1 = mysqli_fetch_assoc($result1))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>"; 	
													echo "<td style='width:200px;'><a href='president_dues_det.php?duesid=".$row1['duesid']."'>".$row1['duesname']."</a></td>";
													echo "<td class='center'>".$row1['details']."</td>";
													echo "<td>".$row1['remarks']."</td>";
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
													Due by / Sundry Creditors
													<span class="smaller-80" style="float:right"><a href='pdt_dues_new.php?id=2'><button class="btn btn-success">Add New Head </button></a></span>
												</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-8">
												<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col" style="width=200px;">Head</th>
													<th class="center">Details</th>
													<th class="center">Remarks</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count2>0){
												$slno=1;
												while($row2 = mysqli_fetch_assoc($result2))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>"; 	
													echo "<td style='width:200px;'><a href='president_dues_det.php?duesid=".$row2['duesid']."'>".$row2['duesname']."</a></td>";
													echo "<td class='center'>".$row2['details']."</td>";
													echo "<td>".$row2['remarks']."</td>";
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