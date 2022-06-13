<?php
    session_start(); 

    $retVal = "";
    $status = 400;
    $data = []; 
    $isValid = true;

    // Check if token is generated
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $userType = isset($_POST['userType']) ? $_POST['userType'] : null;
    $email= isset($_POST['email']) ? $_POST['email'] : null;
    $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : null;
    $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : null;
    $role = isset($_POST['role']) ? $_POST['role'] : null;
    $profilePicture = isset($_POST['profile_pic_location']) ? $_POST['profile_pic_location'] : false;

    // Check if the user has a registration token set
    if($token == null || $userType == null){
        $isValid = false;
        $status = 401;
        $retVal = "No registration token found. Please login and re-submit application.";
    }

    if($isValid){
    // =======================================================================
        $_SESSION['token_support'] = $token;
        $_SESSION['userType'] =  $userType;
        $_SESSION['email'] =  $email;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['role'] = $role;
        $_SESSION['profile_pic_location'] = $profilePicture;

        $retVal = "Verified user token";
        $status = 200;
    }
    
    $myObj = array(
        'status' => $status,
        'message' => $retVal,
        'data' => $_SESSION['token_support'] , 
        //'curlResult' =>  $curlResult
    );

    $myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
    echo $myJSON;
?>