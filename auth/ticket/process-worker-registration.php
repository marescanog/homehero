<?php 
    /*  DEFINITION FOR REFERENCE

        has_AuthorTakenAction = 0 -> Agent is processing ticket

        has_AuthorTakenAction = 1 -> New ticket/No action taken yet

        has_AuthorTakenAction = 2 -> Agent requested Cx follow up

        has_AuthorTakenAction = 3 -> Cx requested agent follow up

        has_AuthorTakenAction = 4 -> Closed/Resolved ticket
    */
    
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

// If token still valid, send a curl request to process the worker registration
if($isValid){
    // Curl pre-initialization - Api Call to assign ticket to an <agent></agent>
    // NOLINKDEVPROD
    $url = "http://localhost/slim3homeheroapi/public/ticket/update-worker-register/+$ticketID"; // DEV
    
    $headers = array(
        "Authorization: Bearer ".$supportToken,
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $_SESSION['email'],
        'type' => $type,
    );
    if($comment != null){
        $post_data['comment'] = $comment;
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




// If Curl was successful, update current token to reflect that registration is complete
if($isValid){
    $retVal = "Successfully Updated Ticket!";
    $status = 200;
}
    
$myObj = array(
    'status' => $status,
    'message' => $retVal,
    'curlResult' =>$curlResult,
    'comment' =>$_POST['form_comment'],
    'id' =>$_POST['form_id'],
    'action' =>$_POST['form_action'],
);
    
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;



















?>