<?php	    
	include("pdt_session.php");
	$_SESSION['curpage']="president_hoa";
	include("pdtsidepan.php");
	$sql1 	= "Select
				  SubHead,
				  majorhead,
				  mainhead
			  From
				  acc_subhead,acc_majorheads,acc_mainheads 
				Where
				  acc_subhead.majorid = acc_majorheads.majorid and acc_majorheads.mainid = acc_mainheads.mainid and (acc_majorheads.mainid = 1 or acc_majorheads.mainid = 3) ORDER BY acc_majorheads.majorid"; 	
	$result1 = mysqli_query($connection,$sql1) or die(mysqli_error());
	$count1 = mysqli_num_rows($result1);

	$sql2 	= "Select
				  SubHead,
				  majorhead,
				  mainhead
			  From
				  acc_subhead,acc_majorheads,acc_mainheads 
				Where
				  acc_subhead.MajorID = acc_majorheads.majorid and acc_majorheads.mainid = acc_mainheads.mainid and (acc_majorheads.mainid = 2 or acc_majorheads.mainid = 4) ORDER BY acc_majorheads.majorid"; 	
	$result2 = mysqli_query($connection,$sql2) or die(mysqli_error());
	$count2 = mysqli_num_rows($result2);
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Head of Accounts
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
													Liabilities & Income
													<span class="smaller-80" style="float:right"><a href='pdt_hoa_new.php?id=1'><button class="btn btn-info">Add New Head</button></a></span>
												</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-8">
												<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col" style="width=200px;">SubHead</th>
													<th class="center">MajorHead</th>
													<th class="center">MainHead</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count1>0){
												$slno=1;
												while($row1 = mysqli_fetch_assoc($result1))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>"; 	
													echo "<td style='width:200px;'>".$row1['SubHead']."</td>";
													echo "<td class='center'>".$row1['majorhead']."</td>";
													echo "<td>".$row1['mainhead']."</td>";
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
													Assets & Expenditure
													<span class="smaller-80" style="float:right"><a href='pdt_hoa_new.php?id=2'><button class="btn btn-success">Add New Head </button></a></span>
												</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-8">
												<table  id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>													
													<th class="detail-col">Sl.No.</th>
													<th class="detail-col" width="200px">SubHead</th>
													<th class="center">MajorHead</th>
													<th class="center">MainHead</th>
												</tr>
											</thead>

											<tbody>
											<?php if($count2>0){
												$slno=1;
												while($row2 = mysqli_fetch_assoc($result2))
												{ 	
													echo "<tr><td class='center'>".$slno."</td>"; 	
													echo "<td width='200px'>".$row2['SubHead']."</td>";
													echo "<td class='center'>".$row2['majorhead']."</td>";
													echo "<td>".$row2['mainhead']."</td>";
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