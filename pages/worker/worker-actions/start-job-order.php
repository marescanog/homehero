<?php

session_start();
$status = 400;
$message = '';
$data = [];

//PARAMETERS:

$hoID = test_input($_REQUEST['hoID']);
$postID = test_input($_REQUEST['postID']);
$workerID = $_SESSION['id'];

require("../../../db/conn.php");

//ERROR HANDLING: If post has been recently deleted or taken by another worker when the search is executed

//1. check first if order exists and if at the correct type (to avoid duplicate acceptance of job posts)
//2. then check if it is the correct worker starting
//2. then update the given job post and create job order
try {
    //PART 1
    $sql = "SELECT worker_id, job_order_status_id as `status` FROM job_order
            WHERE job_post_id=:postID AND homeowner_id=:hoID AND worker_id=:workerID AND job_order_status_id=1 AND is_deleted=0";

    $stmt =  $conn->prepare($sql);

    if ($stmt !== false) {
        $stmt->bindparam(':postID', $postID);
        $stmt->bindparam(':hoID', $hoID);
        $stmt->bindparam(':workerID', $workerID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $stmt = null;
    if (count($result) == 0) {
        $message = "Sorry, job post not found. It may have been recently cancelled or has been already started.";
    } else if ($result['worker_id'] != $workerID) {
        $message = "Looks like you are not the worker accepting this job order!";
    } else {
        //PART 2
        $sql = "SET @@session.time_zone = '+08:00'; 
                BEGIN;
                UPDATE job_order SET date_time_start=CURRENT_TIMESTAMP
                WHERE job_post_id=:postID AND homeowner_id=:hoID AND worker_id=:workerID;
                COMMIT;";

        $stmt =  $conn->prepare($sql);

        if ($stmt !== false) {
            $stmt->bindparam(':postID', $postID);
            $stmt->bindparam(':hoID', $hoID);
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
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>