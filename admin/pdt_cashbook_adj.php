<?php	  
    include("pdt_session.php");
	$_SESSION['curpage']="president_cashbook";
	include("pdtsidepan.php");

    $sql = mysqli_query($connection,"SELECT * FROM acc_subhead WHERE SubHeadModule = 1 OR SubHeadModule = 7");

    $sql2 = mysqli_query($connection,"SELECT acc_cashbook_dummy.*, SubHead FROM acc_cashbook_dummy,acc_subhead 
                                    WHERE clusterid = '$clusterid' AND acc_subhead.SubID = acc_cashbook_dummy.subheadid");
    $count2 = mysqli_num_rows($sql2);


?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Adjustment Entry
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-10"> <!-- PAGE CONTENT BEGINS -->
                
                <div class="content table-responsive table-full-width">
                  <table class="table ">	
                    <thead>
                      <th>Sl.No</th>
                      <th>Select Head</th>
                      <!-- <th>Select Sub Head</th> -->
                      <th>Details</th>
                      <th style="text-align:center;">Receipt</th>
                      <th style="text-align:center">Payment</th>
                      <th></th>
                    </thead>	
                    <tbody>
                      <tr> <form action="pdt_cb_adj_add.php" method ="post">
                        <td text-align="center"> 1 </td>
                        <td>												
                          <select name="subid" id="subhead" class="js-example-basic-single form-control" style="width:200px;" >
                            <option></option>
                            <?php while ($row = mysqli_fetch_assoc($sql)) 												
                              echo "<option value ='".$row['SubID']."'>".$row["SubHead"]."</option>";								
                             ?>
                          </select>												
                        </td>		
                        <!-- <td><select name="accno" id="accnoselect" class="js-example-basic-single form-control" style="width:150px;" disabled  >	 -->

                          </select></td>
                        <td><input type="text" class="form-control" name ="details"  id="details" autocomplete="off" required></td>											
                        <td><input type="text" style="text-align:center" class="form-control" name ="credit"  id="credit" autocomplete="off" ></td>
                        <td><input type="text" style="text-align:center" class="form-control" name ="debit"  id="debit" autocomplete="off" ></td>

                        <td><button type="submit" class="btn btn-info btn-fill">Add</button></td>													
                        </form>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="header">                                
								  <h4 class="title">Passed Entries</h4>                                									
							  </div>             
          
                <div class="content table-responsive table-full-width">
                <table class="table">
                    <thead>
                      <th>Sl.No</th>
                      <th>Head Name</th>																													
                      <th>Details</th>
                      <th style="text-align:right">Receipt</th>
                      <th style="text-align:right">Payment</th>
                      <th style="text-align:center">Delete</th>                                    	
                    </thead>
                    <tbody>
                        <?php 
                            $debit = 0;
                              $credit = 0;
                            if($count2>0){
                              $slno=1;				
                              while($row2 = mysqli_fetch_assoc($sql2))
                              { 

                                echo "<tr><td>".$slno."</td>";
                                echo "<td>".$row2['SubHead']."</td>";
                                echo "<td>".$row2['details']."</td>";                                
                                echo "<td style='text-align:right'>".$row2['receiptadj']."</td>";
                                echo "<td style='text-align:right'>".$row2['paymentadj']."</td>";

                                echo "<td align='center'>
                                      <a href='pdt_cb_adj_del.php?id=".$row2['id']."'><i class='fa fa-close'></i></a>							  
                                    </td></tr>";

                                $slno = $slno + 1;
                                $debit = $debit + $row2['paymentadj'];
                                $credit = $credit + $row2['receiptadj'];	

                              }				
                            }
                            ?> 
                            <tr>
                              <td></td>
                              <td><b>Total Amount</b></td>
                              <td></td>
                              <td style='text-align:right'><b><?php echo $debit; ?></b></td>
                              <td style='text-align:right'><b><?php echo $credit; ?></b></td>
                              <td></td>
                            </tr>

                            <tr>
                              <td><a href="president_cashbook.php"><button type="button" class="btn btn-info btn-fill"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="float:right;"><?php
                                if($debit == $credit && $debit!= 0 ){
                                  echo "<a href='pdt_cb_adj_suc.php'><button type='submit' class='btn btn-info btn-fill' id='proceed'>Proceed</button></a>";                                									
                                }
                                else {

                                  echo "<button class='btn btn-info btn-fill' disabled>Proceed</button>";
                                } ?></td>
                            </tr>

                        </tbody>
                    </table>

                  </div>
          
          
          
                
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php 
	include("footer.php");    
?>			