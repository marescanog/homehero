<?php
    date_default_timezone_set('Asia/Manila');
    session_start();

    // Initialize and set necessary variables
    $output = null; 

    // Declare variables to be used in this modal
    $reasons = [];
    $supName = "";
    $supID = null;

    // curl to get the needed modal information
    // CHANGELINKDEVPROD
    // Make curl for the personal inforation pagge information vv
    $url = "http://localhost/slim3homeheroapi/public/support/get-sup-reason"; // DEV
    // $url = ""; // PROD (No Prod link)

    $headers = array(
        "Authorization: Bearer ".$_SESSION["token_support"],
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $_SESSION["email"]
        // 'email' => 'mdenyys@support.com'
    );

    // 1. Initialize
    $ch = curl_init();

    // 2. set options
        // URL to submit to
        curl_setopt($ch, CURLOPT_URL, $url);

        // Return output instead of outputting it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Type of request = POST
        curl_setopt($ch, CURLOPT_POST, 1);

        // Adding the post variables to the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

        // Set headers for auth
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Execute the request and fetch the response. Check for errors
        $output = curl_exec($ch);

        // Moved inside Modal Body for better display of error messages
        $mode = "PROD"; // DEV to see verbose error messsages, PROD for production build
        $curl_error_message = null;

        // ERROR HANDLING 
        if($output === FALSE || $output === NULL){
            $curl_error_message = curl_error($ch);
        }

        curl_close($ch);

        // $output =  json_decode(json_encode($output), true);
        $output =  json_decode($output);

        // Set the declare variables (refer at the top)
        if(is_object($output) && $output != null && $output->success == true){
            $supID = $output->response->supID;
            $supName = $output->response->sup_name;
            $transReasons = $output->response->transReasons;
        } else {
            $err_stat = $output->response->status;
            $message= $output->response->message;
        }
    

        $data = isset($_POST['data']) ? $_POST['data'] : null;
        $transferType = null; // 1-transfer, 2-request override, 3-escalte to supervisor
        $transferType = null;
        $agent_id = null;
        $agent_name = null;
        $assigned_on = null;
        if($data != null){
            $transferType = isset($_POST['data']['transferType']) ? $_POST['data']['transferType'] : null;
            $agent_id = isset($_POST['data']['assigned_agent_id']) ? $_POST['data']['assigned_agent_id'] : null;
            $agent_name = isset($_POST['data']['assigned_agent_name']) ? $_POST['data']['assigned_agent_name'] : null;
            $assigned_on = isset($_POST['data']['assigned_on']) ? $_POST['data']['assigned_on'] : null;
        }
        $transReasonsArr = [];
        $transReasonsArr[0]["id"] = 1;
        $transReasonsArr[0]["reason"] = "Wrong Department";
        $transReasonsArr[1]["id"] = 2;
        $transReasonsArr[1]["reason"] = "Supervisor Escalation";
        $transReasonsArr[2]["id"] = 3;
        $transReasonsArr[2]["reason"] = "Leave of Absence";
        $transReasonsArr[3]["id"] = 4;
        $transReasonsArr[3]["reason"] = "Admin Escalation";
        $transReasonsArr[4]["id"] = 5;
        $transReasonsArr[4]["reason"] = "Other";
        $transReasonFinal = count($transReasons) == 0 ? $transReasonsArr : $transReasons;

        $ticket_id = isset($_POST['data']) && isset($_POST['data']["ticket_id"]) ? $_POST['data']["ticket_id"] : null;
?>
<div class="modal-content">
    <?php 
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
            // var_dump($output);
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

        <?php 
            if($transferType == 1 ){   
        ?>
            <div class="card  mb-3">
                <div class="card-body">
                    <h5 class="card-title">Current Agent</h5> 
                    <div class="d-flex flex-row justify-content-between">
                        <div style="width:49%">
                            <h6 class="card-subtitle mb-2 text-muted">Name</h6>
                            <p class="card-text"><?php echo $agent_name;?></p>
                        </div>
                        <div style="width:49%">
                            <h6 class="card-subtitle mb-2 text-muted">Assigned On</h6> 
                            <p class="card-text"><?php $date = new DateTime($assigned_on);
                                    echo $date->format('M j,Y g:i A');?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>

        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">Details</h5>
                <div class="">
                    <select class="custom-select mb-2" name="transfer_reason">
                        <option selected disabled>Transfer Reason</option>
                        <?php 
                        if(count($transReasonFinal) == 0){
                            //$transReasonFinal
                            for($trans_indx=0; $trans_indx<count($transReasonFinal); $trans_indx++){
                        ?>
                            <option value="<?php echo $transReasonFinal[$trans_indx]["id"]?>">
                                <?php echo $transReasonFinal[$trans_indx]["reason"]?>
                            </option>
                        <?php 
                            }
                        } else {
                        ?>
                            <option value="1">Wrong Department</option>
                            <option value="2">Supervisor Escalation</option>
                            <option value="3">Leave of Absence</option>
                            <option value="4">Admin Escalation</option>
                            <option value="5">Other</option>
                        <?php }?>
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
                    <?php
                        // $hideSelectSup = true;

                        $hideSelectSup = ($supID==null||$supID=="");

                        if(!$hideSelectSup){
                    ?>
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
                                    <p class="text-center mb-0 pb-0"><?php echo $supName;?></p>
                                    <input type="hidden" value="<?php echo $supID; ?>" name="my_sup">
                                </div>
                                <div class="input-group pt-2 pb-2 pl-3 pr-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inpt-trans-code-1">Transfer Code</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Transfer Code" aria-describedby="inpt-trans-code-1"  name="trans_code_1">
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row desc" id="trans_UI_2" <?php echo $hideSelectSup ? "" : "style='display: none;'";?>>
                        <div class="col p-0">
                            <input type="hidden" name="transfer_type" value="<?php echo htmlentities($transferType);?>">
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
        <?php 
            // var_dump($_POST);
        ?>

        <input type="hidden" value="<?php echo $ticket_id??'';?>" name="ticket_id">

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
