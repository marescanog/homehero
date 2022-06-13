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
//2. then update the given job post and create job order
try {
    //PART 1
    $sql = "SELECT job_post_status_id as `status` FROM job_post
            WHERE id=:postID AND homeowner_id=:hoID AND is_deleted=0";


    $stmt =  $conn->prepare($sql);

    if ($stmt !== false) {
        $stmt->bindparam(':postID', $postID);
        $stmt->bindparam(':hoID', $hoID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $stmt = null;
    if (count($result) == 0) {
        $message = "Sorry, job post not found. It may have been recently removed by the project owner.";
    } else if ($result['status'] != 1) {
        $message = "Sorry, a job order has already been created for this project. Please click the button to explore the remaining active posts.";
    } else {
        //PART 2
        $sql = "SET @@session.time_zone = '+08:00'; 
                BEGIN;
                UPDATE job_post SET job_post_status_id=2, date_time_closed=CURRENT_TIMESTAMP WHERE id=:postID AND homeowner_id=:hoID;
                UPDATE homeowner_notification SET is_deleted=1 WHERE post_id=:postID;
                INSERT INTO job_order (job_post_id, worker_id, homeowner_id, job_order_status_id) 
                    VALUES (:postID, :workerID, :hoID, 1);
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
