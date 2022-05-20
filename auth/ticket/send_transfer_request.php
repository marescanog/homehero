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
    $transfer_code = isset($_POST['transfer_code']) ? $_POST['transfer_code'] : null;
    $transfer_reason = isset($_POST['transfer_reason']) ? $_POST['transfer_reason'] : null;
    $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
    $sup_id = isset($_POST['sup_id']) ? $_POST['sup_id'] : null;
    $permission_code = isset($_POST['permission_code']) ? $_POST['permission_code'] : null;
    $ticket_id = isset($_POST['ticket_id']) ? $_POST['ticket_id'] : null;

// $params = [];
// $params["transfer_code"] = $transfer_code;
// $params["transfer_reason"] = $transfer_reason;
// $params["comments"] = $comments;
// $params["sup_id"] = $sup_id;
// $params["permission_code"] = $permission_code;
// $params["ticket_id"] = $ticket_id;

// Check if the user has a support token set
if($isValid == true && $supportToken == null){
    $isValid = false;
    $status = 401;
    $retVal = "Your session timed out. Please log into your support account";
}

// Check if complete details
if($isValid == true && $transfer_code  == null && $transfer_reason  == null && $comments  == null && $sup_id  == null && $permission_code  == null){
    $isValid = false;
    $status = 400;
    $retVal = "Incomplete Details! Please make sure all details are entered.";
}

// Check if other details are available
if($isValid == true && $ticket_id  == null && $email  == null){
    $isValid = false;
    $status = 400;
    $retVal = "Something went wrong, please refresh your page and try again";
}

// If token still valid, send a curl request to process the worker registration
if($isValid){
    // // Curl pre-initialization - Api Call to assign ticket to an <agent></agent>
    // // NOLINKDEVPROD
    $url = "http://localhost/slim3homeheroapi/public/ticket/request-transfer/".$ticket_id; // DEV
    
    $headers = array(
        "Authorization: Bearer ".$supportToken,
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $email,
        'transfer_code' => $transfer_code,
        'transfer_reason' => $transfer_reason,
        'comments' => $comments,
        'sup_id' => $sup_id,
        'permission_code' => $permission_code
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
    // 'params' => $params,
    // 'post' =>$_POST
    'data' => $curlResult

    // 'message' =>$curlResult->response->message,
    // 'comment' =>$_POST['form_comment'],
    // 'id' =>$_POST['form_id'],
    // 'action' =>$_POST['form_action'],
);
    
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;



















?>