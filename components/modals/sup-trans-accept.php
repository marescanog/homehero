<?php
date_default_timezone_set('Asia/Manila');
session_start();
// // Initialize and set necessary variables
$output = null;
$data = isset($_POST['data']) ? $_POST['data'] : null;
$ticketID = null;
$notifID = null;
$trans_type = null;
$agent_list = [];

if($data != null){  
    $ticketID = isset($_POST['data']['ticketID']) ? $_POST['data']['ticketID'] : null;
    $notifID = isset($_POST['data']['notifID']) ? $_POST['data']['notifID'] : null;
    $trans_type = isset($_POST['data']['trans_type']) ? $_POST['data']['trans_type'] : null; // escalation or transfer
}

if($ticketID != null){
// curl to get the needed modal information
// CHANGELINKDEVPROD
// Make curl for the list of agents
  $url = "http://localhost/slim3homeheroapi/public/ticket/get-agents-applicable-for-transfer/".$notifID; // DEV
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

    // // Set the declare variables (refer at the top)
    if(is_object($output) && $output != null && isset($output->success) && $output->success == true){
        $data = isset($output->response) &&  isset($output->response->data) ? $output->response->data : null;
        $agent_list =   $data == null ? [] : $data->agents_list;
    //     $Addressess = $data->address_list;
    } else {
        // $err_stat = $output->response->status;
        // $message = $output->response->message;
    }

}


?>
<div class="modal-content">
    <?php 
        // var_dump($output->response->data->agents_list);
        // var_dump($_POST);
        // var_dump($agent_list);
        // var_dump($notifID);
        // var_dump($trans_type);

        if($ticketID == null || $output == null){
    ?> 
        <!-- Error handler -->
        <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>Error Loading Modal For Accepting Requests!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div>
    <?php 
        }else if($output->success == false){
    ?>
        <!-- Error handler -->
        <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>Error Loading Modal For Accepting Requests!</b>
                </div>
                <p><?php var_dump($output->response); ?></p>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div>
    <?php
        } else{
    ?>

    <?php 
        // var_dump($output->response);
    ?>


    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">ACCEPT <?php echo $trans_type==3?"ECALATION":"TRANSFER";?> REQUEST</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-trans-accept"  method="POST">
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

        <?php 
            $show = count($agent_list) > 0;
            if($show){
        ?>
        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title m-0 p-0 mb-2">Select Agent</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col p-0">
                                <p class="font-italic m-0 p-0 mb-1 pb-1">Select an agent who can accept the transfer.</p>
                                <div class="d-flex flex-column">
                                    <div class="ml-3 form-check">
                                        <input class="form-check-input" type="radio" name="agent_type" id="agent_type_1" value="1" checked>
                                        <label class="form-check-label" for="agent_type_1">
                                            My Team
                                        </label>
                                    </div>
                                    <div class="ml-3 form-check">
                                        <input class="form-check-input" type="radio" name="agent_type" id="agent_type_2" value="2">
                                        <label class="form-check-label" for="agent_type_2">
                                            A Different Team
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <?php 
            }
        ?>

        <div class="container">
            <?php 
                if($show){
            ?>
            <div class="row desc" id="trans_UI_1">
                <div class="col p-0 justify-content-center">
                    <hr class="mb-3">
                    <h6 class="ml-2 pl-3 card-subtitle pt-2 mb-2 text-muted">Select Agent's Name</h6>
                    <div class="d-flex justify-content-center">
                        <div  class="mb-0" style="width:85%">
                            <select class="custom-select" name="agent_ID_UI_1">
                            <option selected disabled>From your team</option> 
                            <?php 
                                for($hehe=0; $hehe<count($agent_list); $hehe++){
                            ?>
                                <option value="<?php echo $agent_list[$hehe]->id;?>">
                                    E.ID <?php echo $agent_list[$hehe]->id;?> - <?php echo $agent_list[$hehe]->full_name;?> 
                                </option>
                            <?php 
                                }
                            ?>
                            <!-- <option value="1">E.ID 123 - Cameron, Jose </option> -->
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <?php 
                }
            ?>

            <?php if(!$show){?>
            <h5 class="card-title m-0 p-0 mb-2" style="transform: translateX(-9px);">Select Agent</h5>
            <p class="font-italic m-0 p-0 mb-1 pb-1" style="font-size:0.9rem !important;">You currently don't have any agents assigned to you. Select an agent within the same team who can accept the transfer. Transferring the ticket to another agent in a different team will require a manager's approval code.</p>
            <?php }?>

            <div class="row desc" id="trans_UI_2" <?php echo !$show ? "" : "style='display: none;'";?>>
                <div class="col p-0">
                    <!-- <input type="hidden" name="agent_type" value="<?php //echo htmlentities($transferType);?>"> -->
                    
                    <?php if($show){?><hr class="mb-3"><?php }?>
                    
                    <h6 class="ml-2 card-subtitle pt-2 mb-2 text-muted">From a Different Team</h6>
                    
                    <div class="input-group pt-2 pb-2 pl-3 pr-3">
                        <div class="input-group-prepend col-4 w-100 pl-0 pr-0">
                            <span class="input-group-text" style="width:100% !important;" id="Agent_ID_UI_2" >Employee ID</span>
                        </div>
                        <input type="text" class="form-control" aria-label="Agent ID" aria-describedby="Agent_ID_UI_2" name="Agent_ID_UI_2">
                    </div>

                    <div class="input-group pt-2 pb-2 pl-3 pr-3">
                        <div class="input-group-prepend col-4 w-100 pl-0 pr-0">
                            <span class="input-group-text" style="width:100% !important;" id="inpt-code" >Approval Code</span>
                        </div>
                        <input type="text" class="form-control" aria-label="Approval Code" aria-describedby="inpt-code" name="approval_code">
                    </div>
                </div>
            </div>

        </div>
        
        <input type="hidden" name="support_ticket_no" value="<?php  echo $ticketID;?>"> 
        <input type="hidden" name="notif_no" value="<?php  echo $notifID;?>">
        <input type="hidden" name="trans_type" value="<?php  echo $trans_type;?>"> 

        <p class="text-center mb-0 mt-3" style="font-size:0.8rem;">** Transferring a ticket to another agent in a different team will require a manager's approval code. Transfers are only permitted within teams or including the team of the supervisor the agent has sent the request to.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 47%">
                        <span id="RU-submit-btn-txt">NEXT</span>
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
        }
    ?>
</div>
<script src="../../js/components/modal-validation/modal-sup-trans-accept.js"></script>
