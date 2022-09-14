<?php

session_start();
$status = 400;
$message = '';
$data = [];

//PARAMETERS:

$oldPass = test_input($_REQUEST['oldPass']);
$newPass = test_input($_REQUEST['newPass']);
$confirmPass = test_input($_REQUEST['confirmPass']);
$workerID = $_SESSION['id'];

require("../../../db/conn.php");

//ERROR HANDLING:
//  1. Check existing password if match
//  2. Check if new passwords match

try {
    //PART 1
    $sql = "SELECT password FROM hh_user
            WHERE user_id=:workerID";

    $stmt =  $conn->prepare($sql);

    if ($stmt !== false) {
        $stmt->bindparam(':workerID', $workerID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $stmt = null;
    if (count($result) == 0) {
        $message = "Sorry, user not found. It may have been recently removed in the system.";
    } else if (!password_verify($oldPass,$result['password'])) {
        $message = "Your current password is incorrect!";
    } else if (strcmp($newPass,$confirmPass) != 0) {
        $message = "New password and confirm password do not match.";
    } else {
        //PART 2
        $hash = password_hash($newPass,PASSWORD_DEFAULT);
        $sql = "UPDATE hh_user SET `password`=:pass WHERE `user_id`=:workerID;";


        $stmt =  $conn->prepare($sql);

        if ($stmt !== false) {
            $stmt->bindparam(':pass', $hash);
            $stmt->bindparam(':workerID', $workerID);
            if ($stmt->execute()){
                $status = 200;
                $message = 'Success';
            }
        }
        $stmt = null;
    }
} catch (\PDOException $e) {
    $message = "Error excecuting database commands. Please try again. (Error: ".$e->getMessage();
}


$myObj = array(
    'status' => $status,
    'message' => $message,
);

$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;


function test_input($data)
{
    $data = trim($data);
    return $data;
}
