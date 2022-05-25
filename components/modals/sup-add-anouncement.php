<?php
    session_start();
    date_default_timezone_set('Asia/Manila');

    $supportToken = isset($_SESSION['token_support']) ? $_SESSION['token_support'] : null;
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
    $role_anouncement = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    $output = null;

if(  $role_anouncement == 7){
    // // Initialize and set necessary variables
// Do a cURL request to get the necessary info
// // NOLINKDEVPROD
$url = "http://localhost/slim3homeheroapi/public/support/get-team-details"; // DEV
    
$headers = array(
    "Authorization: Bearer ".$supportToken,
    'Content-Type: application/json',
);

$post_data = array(
    'email' => $email
);

// 1. Initialize
$ch = curl_init();

// 2. set options
    // URL to submit to
    curl_setopt($ch, CURLOPT_URL, $url);

    // Return output instead of outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Type of request = POST
    curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPGET, 1);

    // Set headers for auth
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Adding the post variables to the request
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    // // $output =  json_decode(json_encode($output), true);
    $output =  json_decode($output);

    if($output === FALSE){
        $curlResult =  curl_error($ch);
        $isValid = false;
        $status = 500;
        $retVal = "There was a problem with the curl request.";
    } 
    // $output->response->data->agentsList
    $data = null;
    $agentsList = [];
    // $acc = null;
    if($output != null && isset($output->success) && $output->success == true){
        $data = $output->response->data;
        $agentsList = $data->agentsList;
        // $acc = $data->acc;
    }
    curl_close($ch);
}
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $notifID = null;
    $trans_type = null;
    $sup_List = [];
    if(  $output != null && isset($output->response) && isset($output->response->data)){
        $sup_List = $output->response->data->agentsList;
    }
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
        <h5 class="modal-title" id="signUpModalLabel">ADD AN ANOUNCEMENT</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-sup-anouncement"  method="POST">
      <div class="modal-body">
        <?php 
            // var_dump($role_anouncement);
            // var_dump($output);
            // var_dump($sup_List);
        ?>
        <div class="form-group">
            <div class="form-group">
                <label for="title">Post Title:</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="title" placeholder="Enter a title">
            </div>
            <label for="content">Anouncement Details:</label>
            <textarea name="content" class="form-control" id="deccontentline_notes" rows="3" placeholder="Write your anouncement content here"></textarea>
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Optional Post Restrictions</h6>
                <?php 
                    if($role_anouncement == 7 && count($sup_List) != 0){
                ?>
                    <select class="custom-select mt-2" name="team_restrict">
                        <option disabled selected value="0">Restrict By Team</option>
                        <?php 
                            for($xa=0; $xa<count($sup_List); $xa++){
                        ?>
                            <option value="<?php echo $sup_List[$xa]->id?>"><?php echo "E.ID ".str_pad($sup_List[$xa]->id, 3, "0", STR_PAD_LEFT)." - ".$sup_List[$xa]->full_name?></option>
                        <?php 
                            }
                        ?>
                    </select>
                <?php 
                    }
                ?>
                    <select class="custom-select mt-4"  name="role_restrict">
                        <option disabled selected value="0">Restrict By Role</option>
                        <option value="4">Supervisor</option>
                        <option value="2">Customer Support</option>
                        <option value="1">Verification Support</option>
                    </select>
                </div>
            </div>
        </div>

        <p class="text-center mb-0" style="font-size:0.8rem;">** Optionally, you can restrict who is able to view this post by choosing a restriction.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 47%">
                        <span id="RU-submit-btn-txt">POST ANOUNCEMENT</span>
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
<script src="../../js/components/modal-validation/modal-sup-add-anouncement.js"></script>
