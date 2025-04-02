<?php     
	include("accounts_session.php");
	$_SESSION['curpage']="accounts_loans";
    include("accountssidepan.php");
    
    if(isset($_GET['clusterid'])){
 
        $clusterid = $_GET['clusterid'];

        $clustprop = mysqli_query($connection, "SELECT * FROM clusterloanprop");
        $clustpropcount = mysqli_num_rows($clustprop);
        $clustpropcount = 1001 + $clustpropcount;	
        $clustpropid = "CP".$clustpropcount;        

        mysqli_query($connection, "START TRANSACTION");

        
        $clustpropinsert = mysqli_query($connection,"INSERT INTO clusterloanprop (`ClusterPropID`,`ClusterID`,`status`) 
                                VALUES ('$clustpropid','$clusterid',1)");
        $lonapropupdate = mysqli_query($connection,"UPDATE acc_loan_dummy SET status = 1, ClusterPropID = '$clustpropid' 
                                        WHERE status = 0 AND ClusterID = '$clusterid'");

        if($clustpropinsert && $lonapropupdate){
            mysqli_query($connection, "COMMIT");
        }
        else{
            mysqli_query($connection, "ROLLBACK");
            echo 0;
        }

        $loanproposal = mysqli_query($connection,"SELECT acc_loan_dummy.*, groups.GroupName, members.memname, acc_subhead.SubHead
                                            FROM acc_loan_dummy, groups, members, acc_subhead  
                                            WHERE acc_loan_dummy.ClusterID = '$clusterid' and acc_loan_dummy.status = 1  AND acc_loan_dummy.subid = acc_subhead.SubID       
                                        AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND ClusterPropID = '$clustpropid' ");
    
        $count4 = mysqli_num_rows($loanproposal);	

    }

    
    
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Loan Proposal Submitted with Proposal ID : <?php echo $clustpropid; ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-10"> <!-- PAGE CONTENT BEGINS -->
							    												
								<div class="row">
                                    <div class="col-xs-12">
                                        <table class="table">
                                            <thead>
                                                <th style="text-align:center">Sl.No</th>
                                                <th style="text-align:left">Group Name</th>										
                                                <th style="text-align:center">Member Name</th>
                                                <th style="text-align:center">Loan Type</th>
                                                <th style="text-align:center">Loan Balance</th>
                                                <th style="text-align:center">Proposed Loan</th>
                                                <th style="text-align:center">Total Loan</th>                                                
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                if($count4>0){
                                                    $slno = 1;
                                                    
                                                    while($row4 = mysqli_fetch_assoc($loanproposal)){
                                                    echo "<tr>";
                                                    echo "<td align='center'>".$slno."</td>";
                                                    echo "<td>".$row4['GroupName']."</td>";
                                                    echo "<td align='center'>".$row4['memname']."</td>";
                                                    echo "<td align='center'>".$row4['SubHead']."</td>";
                                                    echo "<td align='right'>".$row4['loanbalance']."</td>";
                                                    echo "<td align='right'>".$row4['proposedloan']."</td>";
                                                    $loantot = $row4['loanbalance'] + $row4['proposedloan'];
                                                    echo "<td align='right'>".$loantot."</td>";                                                    
                                                    echo "</tr>";
                                                    $slno = $slno + 1;
                                                    $total = $total + $row4['proposedloan'];
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                <td></td>
                                                <td style="text-align:center">Total Amount</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align='right'><?php echo number_format($total,2); ?></td>
                                                <td></td>
                                                <td></td>
                                                </tr>	
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="col-xs-12">
                                        <div class="content table-responsive table-full-width" style="float:right;">
                                            <a href='accounts_loans.php'><button type='button' class='btn btn-info btn-fill'>Back</button></a>;
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
 <script>		
		$(document).ready(function()
		{ 	
			$('#group').change(function() 
			{   
                $("#member").text('');
                $("#balance").val(0);                
                $("#loanbalance").val(0);
                var group = $("#group").val();
                $.ajax({  
					type: "POST",  
					url: "memberselect.php",  
					data: "group="+ group, 
					success: function(response){
                        //alert(response);
                        $("#member").html(response);								
					} 
				}); 
				return false;
            });
            $('#member').change(function() 
			{   
                $("#balance").val(0);                
                $("#loanbalance").val(0);
                var memid = $("#member").val();
                
                $.ajax({  
					type: "POST",  
					url: "amountselect.php",  
					data: "memid="+ memid, 
					success: function(response){
                        //alert(response);
                        if(response==''){
                            $("#balance").val(0);
                        }
                        else{
                            $("#balance").val(response);						    
                        }
                        
					} 
				}); 
				return false;
            });
            $('#loantype').change(function() 
			{   
                $("#loanbalance").text('');
                var loantype = $("#loantype").val();
                var memid = $("#member").val();
                $.ajax({  
					type: "POST",  
					url: "loanbalanceselect.php",  
					data: {loantype:loantype,memid:memid}, 
					success: function(response){
                        //alert(response);
                        $("#loanbalance").val(response);
                        if(response==''){
                            $("#loanbalance").val(0);
                        }								
					} 
				}); 
				return false;
            });
            $('#proposedloan').keyup(function() 
			{   
                var loanbalance = parseInt($("#loanbalance").val());
                var proposedloan = parseInt($("#proposedloan").val());
                var totalloan = loanbalance + proposedloan;
                $("#totalloan").val(totalloan);	
               	return false;
			});					
		});
</script>					