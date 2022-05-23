<?php 
    session_start(); 

    $curlResult = "";
    $retVal = "";
    $status = 400;
    $data = []; 
    $isValid = true;

// Grab the token from the session & other variables from GET or POST
$supportToken = isset($_SESSION['token_support']) ? $_SESSION['token_support'] : null;
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        $transfer_to_agent_id = isset($_POST['transfer_to_agent_id']) ? $_POST['transfer_to_agent_id'] : null;
        $notif_ID = isset($_POST['notif_ID']) ? $_POST['notif_ID'] : null;
        $agent_type = isset($_POST['agent_type']) ? $_POST['agent_type'] : null;
        $transfer_code = isset($_POST['approval_code']) ? $_POST['approval_code'] : null;
        $transfer_code = $transfer_code == "" ? null : $transfer_code;
// // For Debugging purposes
$params = [];
$params["agent_type"] = $agent_type;
$params["approval_code"] = $transfer_code;
$params["transfer_to_agent_id"] = $transfer_to_agent_id;
$params["notif_ID"] = $notif_ID;


// Check if the user has a support token set
if($isValid == true && $supportToken == null ){
    $isValid = false;
    $status = 401;
    $retVal = "Your session timed out. Please log into your support account";
}
// Check if the user has a support token set
if($isValid == true && $email == null ){
    $isValid = false;
    $status = 401;
    $retVal = "Your session timed out. Please log into your support account";
}
// Check if complete details
if($isValid == true  && $transfer_to_agent_id  == null &&  $notif_ID == null){
    $isValid = false;
    $status = 400;
    $retVal = "Incomplete Details! Please make sure all details are entered.";
}


// // If token still valid, send a curl request to process the worker registration
if($isValid){
    // // Curl pre-initialization - Api Call to assign ticket to an <agent></agent>
    // // NOLINKDEVPROD
    $url = "http://localhost/slim3homeheroapi/public/ticket/process-transfer/".$notif_ID; // DEV
    
    $headers = array(
        "Authorization: Bearer ".$supportToken,
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $email,
        'transfer_to_agent_id' => $transfer_to_agent_id,
    );

    if($agent_type != null && $agent_type == 2 && $transfer_code != null){
        $post_data["manager_approval_code"] = $transfer_code;
    }

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

        if($output === FALSE){
            $curlResult =  curl_error($ch);
            $isValid = false;
            $status = 500;
            $retVal = "There was a problem with the curl request.";
        } else {
            // $output =  json_decode(json_encode($output), true);
            $curlResult =  json_decode($output);
        }

        curl_close($ch);
}

if($isValid == true && isset($curlResult->success) && $curlResult->success == false){
    $isValid = false;
    $status = $curlResult->response->status ?? 500;
    $retVal = $curlResult->response->message ??$curlResult->response ?? "An error occured while updating the permission code please try again.";
}

// If Curl was successful, update current token to reflect that registration is complete
if($isValid){
    // $retVal = (isset($curlResult->response) && isset($curlResult->response->data) ? $curlResult->response->data->message : null) ?? "Successfully Generated Code!";
    $status = 200;
}


$myObj = array(
    'status' => $status,
    'message' => $retVal,
    'params' => $params,
    // 'post' =>$_POST
    // 'data' => $curlResult

    // 'message' =>$curlResult->response->message,
    // 'comment' =>$_POST['form_comment'],
    // 'id' =>$_POST['form_id'],
    // 'action' =>$_POST['form_action'],
);
    
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;



















?>