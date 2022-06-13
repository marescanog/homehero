<?php
    date_default_timezone_set('Asia/Manila');
    // // Initialize and set necessary variables
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $notifID = null;
    $trans_type = null;
    if($data != null){  
        $ticketID = isset($_POST['data']['ticketID']) ? $_POST['data']['ticketID'] : null;
        $notifID = isset($_POST['data']['notifID']) ? $_POST['data']['notifID'] : null;
        $trans_type = isset($_POST['data']['trans_type']) ? $_POST['data']['trans_type'] : null; // escalation or transfer
    }
?>
<div class="modal-content">
    <?php 
        // var_dump($_POST);
        // if($projectID == null || $oldSched == null){
    ?> 
        <!-- Error handler (Not needed for this modal) -->
        <!-- <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>404 Project Not Found!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div> -->
    <?php 
        // }else {
    ?>
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">DECLINE <?php echo $trans_type==3?"ECALATION":"TRANSFER";?> REQUEST</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-trans-decline"  method="POST">
      <div class="modal-body">
        <?php 

        ?>
        <div class="form-group">
            <!-- <label for="trans_agent_notes">Notes:</label> -->
            <textarea name="decline_notes" class="form-control" id="decline_notes" rows="3" placeholder="Explain your reason for declining the transfer"></textarea>
            <input type="hidden" name="notif_no" value="<?php  echo $notifID;?>">
            <input type="hidden" name="trans_type" value="<?php  echo $trans_type;?>"> 
        </div>


        <p class="text-center mb-0" style="font-size:0.8rem;">** Once you have finished entering your reason, click on the button below to submit and decline the agent's request.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 47%">
                        <span id="RU-submit-btn-txt">SUBMIT & DECLINE</span>
                        <div id="RU-submit-btn-load" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                        </div>
                </button>
                <!-- <button type="button" class="btn btn-secondary" style="width: 47%" data-dismiss="modal">CLOSE</button> -->
            </div>
        </div>
    </form>
    <?php 
        // }
    ?>
</div>
<script src="../../js/components/modal-validation/modal-sup-trans-decline.js"></script>
