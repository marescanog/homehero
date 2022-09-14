<?php

session_start();
$status = 400;
$message = '';
$data = [];

//PARAMETERS:

$cityArray = $_REQUEST['cityArray'];
$workerID = $_SESSION['id'];

require("../../../db/conn.php");

//ERROR HANDLING: If post has been recently deleted or taken by another worker when the search is executed

try {
    //PART 1
    if (count($cityArray) == 0) {
        $message = "You did not list any skill!";
    } else {
        //PART 2
        $sql = "SET @@session.time_zone = '+08:00'; 
            BEGIN;
            DELETE FROM city_preference WHERE worker_id=:workerID;
            INSERT INTO city_preference (worker_id,city_id) VALUES";

        for ($x = 0; $x < count($cityArray); $x++) {
            $sql .= "(" . $workerID . "," . $cityArray[$x] . "),";
        }
        $sql = rtrim($sql, ','); //remove final comma
        $sql.= "; COMMIT;";

        $stmt =  $conn->prepare($sql);

        if ($stmt !== false) {
            $stmt->bindparam(':workerID', $workerID);
            if ($stmt->execute()) {
                $status = 200;
                $message = 'Success';
            }
        }
        $stmt = null;
    }
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
