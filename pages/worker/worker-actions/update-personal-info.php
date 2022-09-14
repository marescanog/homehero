<?php

session_start();
$status = 400;
$message = '';
$data = [];

//PARAMETERS:

$fname = test_input($_REQUEST['fname']);
$lname = test_input($_REQUEST['lname']);
$mobile = test_input($_REQUEST['mobile']);
$workerID = $_SESSION['id'];

require("../../../db/conn.php");

//ERROR HANDLING: If post has been recently deleted or taken by another worker when the search is executed

//1. check first if order exists and if at the correct type (to avoid duplicate acceptance of job posts)
//2. then check if it is the correct worker starting
//2. then update the given job post and create job order
try {
    //PART 1
    $sql = "UPDATE hh_user
            SET first_name=:fname, last_name=:lname, phone_no=:mobile 
            WHERE user_id=:workerID";

    $stmt =  $conn->prepare($sql);

    if ($stmt !== false) {
        $stmt->bindparam(':fname', $fname);
        $stmt->bindparam(':lname', $lname);
        $stmt->bindparam(':mobile', $mobile);
        $stmt->bindparam(':workerID', $workerID);
        if ($stmt->execute()) {
            $status = 200;
            $message = 'Success';
            $_SESSION["first_name"] = $fname;
        }
    }
    $stmt = null;
} catch (\PDOException $e) {
    $message = "Error excecuting database commands. Please try again. (Error: " . $e->getMessage();
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
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
