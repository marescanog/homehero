<?php 
    session_start(); 

    $curlResult = "";
    $retVal = "";
    $status = 400;
    $data = []; 
    $isValid = true;

// Grab the token from the session & other variables from GET or POST
$supportToken = isset($_SESSION['token_support']) ? $_SESSION['token_support'] : null;
$email = isset($_SESSION['email ']) ? $_SESSION['email '] : null;
$ticketID = isset($_POST['form_id']) && is_numeric($_POST['form_id']) ? $_POST['form_id'] : null;
$type= isset($_POST['form_action']) && is_numeric($_POST['form_action']) ? $_POST['form_action'] : null;
$comment= isset($_POST['form_comment']) ? $_POST['form_comment'] : null;

    $bill_resolved = isset($_POST['bill_resolved']) ? $_POST['bill_resolved'] : null;

$job_order_status = isset($_POST['job_order_status']) ? $_POST['job_order_status'] : null;
$jo_start_date_time = isset($_POST['jo_start_date_time']) ? $_POST['jo_start_date_time'] : null;
$jo_end_date_time = isset($_POST['jo_end_date_time']) ? $_POST['jo_end_date_time'] : null;
$home_ID = isset($_POST['jo_address_submit']) ? $_POST['jo_address_submit'] : null;

// Check if the user has a support token set
if($isValid == true && $supportToken == null){
    $isValid = false;
    $status = 401;
    $retVal = "Your session timed out. Please log into your support account";
}

// Check if there is a ticket ID
if($isValid == true && $ticketID  == null){
    $isValid = false;
    $status = 401;
    $retVal = "There was an error pulling up the ticket. Please try again.";
}

// Check if there are values submitted
if($isValid == true && $type == 1 && $job_order_status  == null && $jo_start_date_time  == null && $jo_end_date_time  == null && $home_ID == null){
    $isValid = false;
    $status = 401;
    $retVal = "Please update any of the following: order status, start date, end date or address.";
}

if($isValid == true && $type == 5 && $bill_resolved == null){
    $isValid = false;
    $status = 401;
    $retVal = "Please indicate if the ticket was resolved.";
}

// If token still valid, send a curl request to process the worker registration
if($isValid){
    // // Curl pre-initialization - Api Call to assign ticket to an <agent></agent>
    // // NOLINKDEVPROD
    $url = "http://localhost/slim3homeheroapi/public/ticket/process-job-issue/+$ticketID"; // DEV
    
    $headers = array(
        "Authorization: Bearer ".$supportToken,
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $_SESSION['email'],
        'type' => $type,
    );

    if($comment!= null){
        $post_data['comment'] = $comment;
    }

    if($job_order_status!= null && $job_order_status!= ""){
        $post_data['job_order_status'] = $job_order_status;
    }

    if($jo_start_date_time != null && $jo_start_date_time != ""){
        $post_data['jo_start_date_time'] = $jo_start_date_time;
    }

    if($jo_end_date_time != null && $jo_end_date_time != ""){
        $post_data['jo_end_date_time'] = $jo_end_date_time;
    }

    if($home_ID != null && $home_ID != ""){
        $post_data['home_ID'] = $home_ID;
    }

    if($bill_resolved != null){
        $post_data['bill_resolved'] = $bill_resolved;
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
    $retVal = $curlResult->response->message ?? "An error occured while updating the bill issue details please try again.";
}

// If Curl was successful, update current token to reflect that registration is complete
if($isValid){
    $retVal = (isset($curlResult->response) && isset($curlResult->response->data) ? $curlResult->response->data->message : null) ?? "Successfully Updated Bill!";
    $status = 200;
}

$myObj = array(
    'status' => $status,
    'message' => $retVal,
    // 'curlResult' =>$output,
    // 'message' =>$curlResult->response->message,
    // 'comment' =>$_POST['form_comment'],
    // 'id' =>$_POST['form_id'],
    // 'action' =>$_POST['form_action'],
    'POST' => $_POST
);
    
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;



















?>