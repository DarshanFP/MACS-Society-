<?php	    
	include("pdt_session.php");
    $_SESSION['curpage']="president_trans";
    	
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $transid = $_POST['transid'];
        //echo $transid;       
        $sql5 = mysqli_query($connection,"SELECT acc_cashbook.*,TransStatus FROM acc_cashbook,acc_transactions WHERE acc_cashbook.TransID = acc_transactions.TransID AND acc_cashbook.TransID = '$transid'");
                      
        $sno = 1;
        while($row5=mysqli_fetch_assoc($sql5)){
            echo "<tr>";
            echo "<td align='center'>".$sno."</td>";
            echo "<td align='center'>".$transid."</td>";
            echo "<td align='center'>".date('d-m-Y',strtotime($row5['date']))."</td>";
            $subheadid = $row5['subheadid'];
            $memid = $row5['memid'];
            $sql61 = mysqli_query($connection,"SELECT SubHead FROM acc_subhead WHERE SubID = '$subheadid'");
            $row61 = mysqli_fetch_assoc($sql61);
            $subhead = $row61['SubHead'];
            $sql62 = mysqli_query($connection,"SELECT memname FROM members WHERE memid = '$memid'");
            $row62 = mysqli_fetch_assoc($sql62);
            $memname = $row62['memname'];
            echo "<td>".$memname."</td>";
            echo "<td align='center'>".$subhead."</td>";
            echo "<td align='center'>".$row5['accno']."</td>";
            echo "<td>".$row5['details']."</td>";
            echo "<td align='center'>".$row5['ob']."</td>";
            echo "<td align='center'>".$row5['receiptcash']."</td>";
            echo "<td align='center'>".$row5['receiptadj']."</td>";
            echo "<td align='center'>".$row5['paymentcash']."</td>";
            echo "<td align='center'>".$row5['paymentadj']."</td>";
            $cluster = $row5['clusterid'];
            $clustersql = mysqli_query($connection,"SELECT ClusterName FROM cluster WHERE ClusterID='$cluster'");
            $clustername = mysqli_fetch_assoc($clustersql);
            echo "<td align='center'>".$clustername['ClusterName']."</td>";
            if($row5['TransStatus']==1){
                echo "<td class='center'><span class='label label-success'>Active</span></td>";
            }else if($row5['TransStatus']==0){
                echo "<td class='center'><span class='label label-danger'>Cancelled</span></td>";
            }
            echo "</tr>"; 
            $sno++;     
        }
    }     
?>    
                                                
    
