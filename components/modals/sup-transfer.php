<?php
    date_default_timezone_set('Asia/Manila');
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $transferType = null; // 1-transfer, 2-request override, 3-escalte to supervisor
    // $projectID = null;
    // $oldSched = null;
    // $changedVal = null;
    // $today = isset($_POST['data']) && isset($_POST['data']['today']) ? $_POST['data']['today'] : date("YYYY-MM-DD H:i:s");
    // $reformattedTime = null;
    // $changedDate = "1212-12-12";
    // $changedTime = "00:00:00";
    if($data != null){
        $transferType = isset($_POST['data']['transferType']) ? $_POST['data']['transferType'] : null;
    //     // $projectID = isset($_POST['data']['projectID']) ? $_POST['data']['projectID'] : null;
    //     $oldSched = isset($_POST['data']['old_date_time']) ? $_POST['data']['old_date_time'] : null;
    //     $changedVal = isset($_POST['data']['changedValue']) ? ($_POST['data']['changedValue'] == "" ? null : $_POST['data']['changedValue']) : null;

    //     if($changedVal != null){
    //         $explodedChangedDate = explode(" ",$changedVal);
    //         $changedTime = $explodedChangedDate[1];
    //         $changedDate = $explodedChangedDate[0];
    //     }
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
        <h5 class="modal-title" id="signUpModalLabel">
            <?php 
                $transferTypeArr = array("TRANSFER TICKET","REQUEST TICKET REASSIGNMENT","ESCALATE TICKET");
                $transferType = $transferType <= 3 && $transferType >= 1 ? $transferType : null;
                echo $transferType == null ? "TRANSFER TICKET" : $transferTypeArr[$transferType-1];
            ?>
        </h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-transfer"  method="POST">
        <div class="modal-body">
        <?php 
            // Get the reformatted time for the input variable & display
            // $monthArr = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
            //  $setDate = $oldSched != null ? $oldSched : $today;
            //     $explodedDate = explode(" ",$setDate);
            //     $time = explode(":", $explodedDate[1]);
            //     $date = explode("-", $explodedDate[0]);
            //     $reformattedDate =  $monthArr[$date[1]-1].' '.$date[2].', '.$date[0];

            //     $period =  $time[0] > 12 ? "PM" : "AM";
            //     $hours = $time[0] > 12 ?  $time[0]-12 :  $time[0]+0;
            //     $reformattedTime = $hours.':'.$time[1].' '.$period ;
        ?>

        <!-- <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">Current Agent</h5> 
                <div class="d-flex flex-row justify-content-between">
                    <div style="width:49%">
                        <h6 class="card-subtitle mb-2 text-muted">Name</h6>
                        <p class="card-text"><?php //echo $reformattedDate;?></p>
                    </div>
                    <div style="width:49%">
                        <h6 class="card-subtitle mb-2 text-muted">Assigned On</h6>
                        <p class="card-text"><?php //echo $reformattedTime;?></p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">Details</h5>
                <div class="">
                    <select class="custom-select mb-2" name="transfer_reason">
                        <option selected disabled>Transfer Reason</option>
                        <option value="1">Wrong Department</option>
                        <option value="2">Supervisor Escalation</option>
                        <option value="3">Leave of Absence</option>
                        <option value="4">Admin Escalation</option>
                        <option value="5">Other</option>
                    </select>
                    <div class="form-group">
                        <label for="trans_agent_notes">Notes:</label>
                        <textarea name="agent_notes" class="form-control" id="trans_agent_notes" rows="3" placeholder="Explain on your reason for transfer"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title m-0 p-0 mb-2">Approval Request</h5>
                <div class="container">
                    <div class="row mb-2">
                        <div class="col p-0">
                            <p class="font-italic m-0 p-0 mb-1 pb-1">Select a supervisor who is currently available</p>
                            <div class="d-flex flex-column">
                                <div class="ml-3 form-check">
                                    <input class="form-check-input" type="radio" name="supervisor_type" id="supervisor_type_1" value="1" checked>
                                    <label class="form-check-label" for="supervisor_type_1">
                                       My Supervisor
                                    </label>
                                </div>
                                <div class="ml-3 form-check">
                                    <input class="form-check-input" type="radio" name="supervisor_type" id="supervisor_type_2" value="2">
                                    <label class="form-check-label" for="supervisor_type_2">
                                        Different Supervisor
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row desc" id="trans_UI_1">
                        <div class="col p-0">
                            <hr class="mb-3">
                            <h6 class="card-subtitle pt-2 mb-2 text-muted text-center">Supervisor's Name</h6>
                            <div  class="mb-0">
                                <p class="text-center mb-0 pb-0">Your Supervisor</p>
                                <input type="hidden" value="" name="my_sup">
                            </div>
                            <div class="input-group pt-2 pb-2 pl-3 pr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inpt-trans-code-1">Transfer Code</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Transfer Code" aria-describedby="inpt-trans-code-1"  name="trans_code_1">
                            </div>
                        </div>
                    </div>

                    <div class="row desc" id="trans_UI_2" style="display: none;">
                        <div class="col p-0">
                            <hr class="mb-3">
                            <div class="input-group pt-2 pb-2 pl-3 pr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inpt-sup-id" >Supervisor ID</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Supervisor ID" aria-describedby="inpt-sup-id" name="sup_ID">
                            </div>
                            <div class="input-group pt-2 pb-2 pl-3 pr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"  id="inpt-trans-code-2" >Transfer Code</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Transfer Code" aria-describedby="inpt-trans-code-2" name="trans_code_2">
                            </div>
                        </div>
                    </div>
          
                </div>
            </div>
        </div>

        <p class="text-center mb-0" style="font-size:0.8rem;">** Ticket transfers are pending supervisor's approval.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 47%">
                        <span id="RU-submit-btn-txt">SUBMIT REQUEST</span>
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
<script src="../../js/components/modal-validation/modal-sup-transfer.js"></script>
