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
$permission_id = isset($_POST['permission_id']) && is_numeric($_POST['permission_id']) ? $_POST['permission_id'] : null;
$supervisor_id = isset($_POST['supervisor_id']) && is_numeric($_POST['supervisor_id']) ? $_POST['supervisor_id'] : null;
$password= isset($_POST['password']) ? $_POST['password'] : null;

// Check if the user has a support token set
if($isValid == true && $supportToken == null){
    $isValid = false;
    $status = 401;
    $retVal = "Your session timed out. Please log into your support account";
}

// Check if password
if($isValid == true && $password  == null){
    $isValid = false;
    $status = 400;
    $retVal = "Please enter your password";
}

// Check if other details are available
if($isValid == true && $permission_id  == null && $email  == null){
    $isValid = false;
    $status = 400;
    $retVal = "Something went wrong, please refresh your page and try again";
}

// If token still valid, send a curl request to process the worker registration
if($isValid){
    // // Curl pre-initialization - Api Call to assign ticket to an <agent></agent>
    // // NOLINKDEVPROD
    $url = "http://localhost/slim3homeheroapi/public/support/generate-permissions"; // DEV
    
    $headers = array(
        "Authorization: Bearer ".$supportToken,
        'Content-Type: application/json',
    );

    $post_data = array(
        'email' => $email,
        'password' => $password,
        'permission_code' => $permission_id
    );

    if($supervisor_id != null){
        $post_data['supervisor_id'] = $supervisor_id;
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
    $retVal = (isset($curlResult->response) && isset($curlResult->response->data) ? $curlResult->response->data->message : null) ?? "Successfully Generated Code!";
    $status = 200;
}


$myObj = array(
    'status' => $status,
    'message' => $retVal,
    // 'data' => $curlResult

    // 'message' =>$curlResult->response->message,
    // 'comment' =>$_POST['form_comment'],
    // 'id' =>$_POST['form_id'],
    // 'action' =>$_POST['form_action'],
);
    
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;



















?>