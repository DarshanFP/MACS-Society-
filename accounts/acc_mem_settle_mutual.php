<?php
    include("accounts_session.php");
	$_SESSION['curpage']="accounts_member";	
    
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST['id']; 
        
        $mutual = mysqli_query($connection,"SELECT mem_settle.*, SubHead FROM mem_settle, acc_subhead WHERE mem_settle.Id = '$id' AND mem_settle.SubHeadID = acc_subhead.SubID");
        $mutualhead = mysqli_fetch_assoc($mutual);
        $subhead = $mutualhead['SubHead'];        
        
        echo '<div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">                        
                            <h4 class="modal-title">Death Relief to be paid on :'.$subhead.'</h4>
                        </div>
                        <div class="modal-body">

                            <form class="form-horizontal" role="form" method = "post" action="acc_mem_settle.php">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Enter Interest &nbsp;</label>
                                    
                                    <div class="col-sm-7">    
                                        <input type="text" id="form-field-1" name="mutual" placeholder="Death Aid Amount" class="col-xs-10 col-sm-5" autocomplete="off" required />
                                        <input type="hidden" name="id" value="'.$id.'" class="col-xs-10 col-sm-5"/>
                                    </div>
                                    <div class="clearfix form-group">
                                        <button id="submit" class="btn btn-success" type="submit">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Submit
                                        </button>
                                    </div>
                                </div>    
                            </form>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>';
    }    
?>        