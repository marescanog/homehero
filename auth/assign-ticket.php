<?php
    session_start(); 

    $curlResult = "";
    $retVal = "";
    $status = 400;
    $data = []; 
    $isValid = true;

// Grab the token from the session & other variables from GET
$supportToken = isset($_SESSION['token_support']) ? $_SESSION['token_support'] : null;
$ticketID = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;

// Check if the user has a support token set
if($supportToken == null){
    $isValid = false;
    $status = 401;
    $retVal = "Your session timed out. Please log into your support account";
}

// Check if there is a ticket ID
if($ticketID  == null){
    $isValid = false;
    $status = 401;
    $retVal = "There was an error pulling up the ticket. Please try again.";
}

// If token still valid, send a request to mark user as registered and submit a support ticket so agent can review worker's records
if($isValid){
    // Curl pre-initialization - Api Call to assign ticket to an agent
    // NOLINKDEVPROD
    $url = "http://localhost/slim3homeheroapi/public/ticket/assign/+$ticketID"; // DEV
    
    $headers = array(
        "Authorization: Bearer ".$supportToken,
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $_SESSION['email']
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
        }

        curl_close($ch);

        // $output =  json_decode(json_encode($output), true);
        $curlResult =  json_decode($output);

}

if($curlResult->success == false && property_exists($curlResult->response, 'email')){
    $retVal = "Please try logging in again.";
    $status = 401;
    $isValid = false;
}

if($curlResult->success == false && $curlResult->response=="Ticket already assgined to another agent."){
    $retVal = $curlResult->response;
    $status = 403;
    $isValid = false;
}

// If Curl was successful, update current token to reflect that registration is complete
if($isValid){
    $retVal = "Successfully Assigned Ticket!";
    $status = 200;
}
    
$myObj = array(
    'status' => $status,
    'message' => $retVal,
    'curlResult' =>$curlResult,
);
    
    $myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
    echo $myJSON;
?>