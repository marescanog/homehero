<?php

session_start();
$status = 400;
$message = '';
$data = [];

//PARAMETERS:

$hoID = test_input($_REQUEST['hoID']);
$postID = test_input($_REQUEST['postID']);
$workerID = $_SESSION['id'];
$mode = test_input($_REQUEST['mode']); //1 - cash - confirm received, 2 - credit, wait for payment
if ($mode==2){
    $paymentStatus=2; //paid
    $isReceived=1; //confirmed receipt
    $paidTime = date("Y-m-d H:i:sa", strtotime("+6 hours",time()));
} else {
    $paymentStatus=1; //pending
    $isReceived=0; //not confirmed receipt
    $paidTime = null;
}
$amount = test_input($_REQUEST['amount']);


require("../../../db/conn.php");

//ERROR HANDLING: If post has been recently deleted or taken by another worker when the search is executed

//1. check first if order exists and if at the correct type (to avoid duplicate acceptance of job posts)
//2. then check if it is the correct worker starting
//2. then update the given job post and create job order
try {
    //PART 1
    $sql = "SELECT worker_id, job_order_status_id as `status`, date_time_start FROM job_order
            WHERE id=:postID AND homeowner_id=:hoID AND worker_id=:workerID AND job_order_status_id=1 AND is_deleted=0";

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
    } else if ($result['status'] != 1) {
        $message = "Looks like you have already completed this project!";
    } else if ($result['date_time_start'] == null) {
        $message = "Looks like you have not started this project yet!";
    } else {
        //PART 2
        $sql = "SET @@session.time_zone = '+08:00'; 
                BEGIN;
                UPDATE job_order SET date_time_closed=NOW(), job_order_status_id=2
                WHERE id=:postID AND homeowner_id=:hoID AND worker_id=:workerID;
                INSERT INTO bill (job_order_id,worker_id,homeowner_id,payment_method_id,bill_status_id,total_price_billed,is_received_by_worker,created_on,date_time_completion_paid)
                    VALUES (:postID,:workerID,:hoID,:mode,:status,:amount,:isReceived,CURRENT_TIMESTAMP,:paidTime);
                COMMIT;";

        $stmt =  $conn->prepare($sql);

        if ($stmt !== false) {
            $stmt->bindparam(':postID', $postID);
            $stmt->bindparam(':hoID', $hoID);
            $stmt->bindparam(':workerID', $workerID);
            $stmt->bindparam(':mode', $mode);
            $stmt->bindparam(':status', $paymentStatus);
            $stmt->bindparam(':amount', $amount);
            $stmt->bindparam(':isReceived', $isReceived);
            $stmt->bindparam(':paidTime', $paidTime);
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