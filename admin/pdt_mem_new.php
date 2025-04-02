<?php	    
	include("pdt_session.php");
	//$_SESSION['curpage']="president_member";
    include("pdtsidepan.php");
    
    if(isset($_GET['groupid'])){
        $_SESSION['curpage']="president_group";
        $groupid = $_GET['groupid'];
        $sql1=mysqli_query($connection,"SELECT groups.*,ClusterName FROM groups,cluster WHERE groups.ClusterID=cluster.ClusterID AND groups.GroupID = '$groupid'");
        $count1 = mysqli_num_rows($sql1);
        $row1=mysqli_fetch_assoc($sql1);
    }else{
        $_SESSION['curpage']="president_member";
        $sql1=mysqli_query($connection,"SELECT * FROM cluster");
    }
?>

			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Add New Member
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12"> <!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="pdt_mem_suc.php">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="name" placeholder="Enter Member Name" class="col-xs-10 col-sm-5" autocomplete="off" required />
                                            <input type="hidden" name="count1" value="<?php echo $count1;?>" class="col-xs-10 col-sm-5"/>
                                        </div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Gender</label>

										<div class="col-sm-9">
											<select name="gender" id="form-field-2">
                                                <option value="female">Female</option>
                                                <option value="male">Male</option>
                                                <option value="female">Transgender</option>
											</select>		
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Aadhaar No. </label>

										<div class="col-sm-4">
											<input type="text" id="aadhaar" name="aadhaar" minlength="12" maxlength="12" placeholder="Enter Aadhaar Number" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member ID </label>

										<div class="col-sm-4">
											<input type="text" id="form-field-1" name="memberid" placeholder="Enter Member ID" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Date of Birth </label>

										<div class="col-sm-4">
											<input type="date" id="form-field-3" name="dob" placeholder="mm/dd/yyyy"  class="col-xs-8 col-sm-6" autocomplete="off" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Date of Joining </label>

										<div class="col-sm-4">
											<input type="date" id="form-field-5" name="doj" placeholder="mm/dd/yyyy"  class="col-xs-8 col-sm-6" autocomplete="off" required />
										</div>
									</div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Cluster Name </label>

										<div class="col-sm-4">
                                            <select name="cluster" id = "cluster" class="col-xs-10 col-sm-5" autocomplete="off" required>
                                                 
                                                <?php
                                                if($count1==1){
                                                    echo "<option value = ".$row1['ClusterID'].">".$row1['ClusterName']."</option>";      
                                                }
                                                else{
                                                    echo "<option></option>"; 
                                                    while($row1 = mysqli_fetch_assoc($sql1)){
                                                        echo "<option value = ".$row1['ClusterID'].">".$row1['ClusterName']."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>                       
									    </div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Group Name </label>
                                        <div class="col-sm-4">
                                        <?php
                                        if($count1==1){
                                            echo "<select name='group' class='col-xs-10 col-sm-5' autocomplete='off' required>";
                                            echo "<option value = ".$row1['GroupID'].">".$row1['GroupName']."</option>";
                                            echo "</select>";
                                        }
										else{
                                            echo "<select name='group' id = 'group' class='col-xs-10 col-sm-5' autocomplete='off' required>";
                                                
                                            echo "</select>";
                                        }                           
									    ?>
                                        </div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member Address </label>

										<div class="col-sm-7">
											<textarea type="text" id="form-field-1" name="address" placeholder="Enter Address" class="col-xs-10 col-sm-5" autocomplete="off" required></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Mobile No. </label>

										<div class="col-sm-4">
											<input type="text" id="mobile" name="cell" placeholder="Mobile Number" minlength="10" maxlength="10" class="col-xs-10 col-sm-5" autocomplete="off" required />
											<div id = "mobilestatus">Mobile number already exits</div>
										</div>
                                    </div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Bank Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="bankname" placeholder="Enter Bank Name" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Bank IFSC</label>

										<div class="col-sm-7">
											<input type="text" id="bankifsc" name="bankifsc" minlength="11" maxlength="11" placeholder="Enter Bank IFSC" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Bank Account No.</label>

										<div class="col-sm-7">
											<input type="text" id="bankaccountno" name="bankaccountno" placeholder="Enter Account No." class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Bank Address</label>

										<div class="col-sm-7">
											<textarea type="text" id="form-field-1" name="bankaddress" placeholder="Enter Bank Address" class="col-xs-10 col-sm-5" autocomplete="off" required></textarea>
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nominee Name </label>

										<div class="col-sm-7">
											<input type="text" id="form-field-1" name="nomineename" placeholder="Enter Nominee Name" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Relationship with member</label>

										<div class="col-sm-5">
											<input type="text" id="form-field-1" name="nomineerelation" placeholder="Enter Relationship" class="col-xs-10 col-sm-5" autocomplete="off" required />
										</div>
                                    </div>
									
									
									<div class="clearfix form-group">
										<div class="col-md-offset-3 col-md-9">
											<button id="submit" class="btn btn-success" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
                                            <?php
                                                if($count==1){											
											        echo '<a href="pdt_group_det.php?groupid='.$groupid.'"><button class="btn btn-info" type="button">';
                                                }
                                                else{
                                                    echo '<a href="president_member.php"><button class="btn btn-info" type="button">';
                                                }
                                            ?>    
											
												<i class="ace-icon fa fa-arrow-left bigger-110"></i>
												Back
											</button></a>											
										</div>
									</div>
								</form>	
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<script>		
		$(document).ready(function()
		{ 	$("#mobilestatus").hide();
			$('#mobile').keyup(function() 
			{   
				var mobile = $("#mobile").val();					
				if(mobile.length <= 10 )
				{
					$.ajax({  
						type: "POST",  
						url: "memphchecknew.php",  
						data: "mobile="+ mobile, 
						success: function(count){ 																				
							if(count == 1)
 							{	
								$("#mobilestatus").show();								
								$("#submit").addClass("disabled");
							}  
							else {
								$("#mobilestatus").hide();								
								$("#submit").removeClass("disabled");
							}								
						} 
					}); 
				}				
			return false;
			});			
		});
</script>

<script>
    $(document).ready(function(){
        $('#mobile,#aadhaar,#bankaccountno').keypress(function(e) {
	      if(isNaN(this.value+""+String.fromCharCode(e.charCode))) return false;
        })
        .on("cut copy paste",function(e){
	    e.preventDefault();
        });
    });
</script>
<script>		
		$(document).ready(function()
		{ 	
			$('#cluster').change(function() 
			{   
                $("#group").text('');
                var cluster = $("#cluster").val();
                $.ajax({  
					type: "POST",  
					url: "groupselect.php",  
					data: "cluster="+ cluster, 
					success: function(response){
                        //alert(response);
                        $("#group").html(response);								
					} 
				}); 
				return false;
			});			
		});
</script>
<?php 
	include("footer.php");    
?>			