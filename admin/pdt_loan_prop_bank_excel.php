<?php 
    include("../config.php");
    if(isset($_GET['macspropid'])){
        $macspropid = $_GET['macspropid'];

        $acceptedloans = mysqli_query($connection,"SELECT acc_loan_dummy.*, cluster.ClusterName, groups.GroupName, members.memname, members.bankname, members.bankifsc, members.bankaccountno, acc_subhead.SubHead
                                                FROM acc_loan_dummy, groups, members, acc_subhead, cluster  
                                                WHERE acc_loan_dummy.subid = acc_subhead.SubID       
                                            AND acc_loan_dummy.memid = members.memid AND acc_loan_dummy.GroupID = groups. GroupID AND acc_loan_dummy.status = 4 
                                            AND acc_loan_dummy.ClusterID = cluster.ClusterID AND acc_loan_dummy.MacsPropID = '$macspropid' ORDER BY cluster.ClusterID, groups.GroupID, members.memid");
        
        $acceptcount = mysqli_num_rows($acceptedloans);      

         $output = "";
	
		
			$output .= "<table class='table' style='font-family:verdana;'>
							<tr>
								<td colspan='7' style='text-align:center; font-size:20px;'>".$name." ".$city."</td>
							</tr>
							<tr>
								<td colspan='7' style='text-align:center; font-size:20px;'>".$macspropid." Ledger:".$officename."</td>
							</tr>";
			
				$output .= "<tr>
								<td colspan='7' style='text-align:center; font-size:16px;'>Statement from ".date('d-m-Y',strtotime($fromdate))." to ".date('d-m-Y',strtotime($todate))."</td>
							</tr>
						</table>";
			
										
			$output .= "<table class='table' border = '1' style='font-family:verdana; font-size:10px;'>
							<thead>			
								<tr style='word-wrap: break-word;'>									
									<th  style='text-align: center;'>Sl.No.</th>
									<th  style='text-align: center;'>Name</th>																				                                    	
									<th  style='text-align: center;'>Accout No</th>
									<th  style='text-align: center;'>Amount</th>
									<th  style='text-align: center;'>IFSC Code</th>
                                    <th  style='text-align: center;'>Bank Name</th>									
                                    <th  style='text-align: center;'>Society Name</th>
								</tr>
							</thead>
                            <tbody>";
            if($acceptcount > 0){
                $slno = 1;
                $total = 0;
                while($row1 = mysqli_fetch_assoc($acceptedloans)){
                        $output .= "<tr>
                                        <td>".$slno."</td>
                                        <td>".$row1['memname']."</td>
                                        <td>".$row1['proposedloan']."</td>
                                        <td>".$row1['bankaccountno']."</td>
                                        <td>".$row1['bankifsc']."</td>
                                        <td>".$row1['bankname']."</td>
                                        <td></td>                                        
                                    </tr>";
                        $total = $total + $row1['proposedloan'];
                        $slno = $slno + 1;
                }     
                          

            }
             $output .= "<tr>
                            <td>Total</td>
                            <td></td>
                            <td>".$total."</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>                                        
                        </tr>";
		
 		    $output .="</tbody></table>";	
			header("Content-Type: application/x-msdownload, true");
			header("Content-Disposition:attachment; filename=bankreport.xls");
			echo $output;
    }


	

   

?>
	