<?php

session_start();
$status = 400;
$message = '';
$data = [];

//PARAMETERS:

$hoID = secure($_REQUEST['hoID']);
$orderID = secure($_REQUEST['orderID']);
$workerID = $_SESSION['id'];

require("../../../db/conn.php");

//ERROR HANDLING: If post has been recently deleted or taken by another worker when the search is executed

//1. check first if bill exists and if at the correct type (to avoid duplicate acceptance of records)
//2. then check if it is the correct worker accepting
//3. then update the bill and its status
try {
    //PART 1
    $sql = "SELECT worker_id, job_order_id, payment_method_id, bill_status_id, is_received_by_worker
            FROM bill
            WHERE job_order_id=:orderID AND homeowner_id=:hoID AND is_deleted=0";

    $stmt =  $conn->prepare($sql);

    if ($stmt !== false) {
        $stmt->bindparam(':orderID', $orderID);
        $stmt->bindparam(':hoID', $hoID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $stmt = null;
    if (count($result) == 0) {
        $message = "Sorry, bill not found. It may have been recently removed or there is still no bill generated for this job order.";
    } else if ($result['worker_id'] != $workerID) {
        $message = "Looks like you are not the worker handling this job order!";
    } else if ($result['bill_status_id'] != 2) {
        $message = "Looks like your customer has not submitted a payment yet!";
    } else if ($result['is_received_by_worker'] != 0) {
        $message = "You have already confirmed the payment!";
    }else {
        //PART 2
        $sql = "SET @@session.time_zone = '+08:00'; 
                BEGIN;
                UPDATE bill SET is_received_by_worker=1
                WHERE job_order_id=:orderID AND homeowner_id=:hoID AND worker_id=:workerID AND is_deleted=0;
                COMMIT;";

        $stmt =  $conn->prepare($sql);

        if ($stmt !== false) {
            $stmt->bindparam(':orderID', $orderID);
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


function secure($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>