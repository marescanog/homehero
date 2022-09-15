<?php

session_start();
if (!isset($_SESSION["token"])) {
    header("Location: ../../");
    exit();
}

$level = "../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";


require_once dirname(__FILE__) . "/$level/components/head-meta.php";

//SQL Queries
//1-4. Job status by worker (today union 7 days union 30 days union total)
require_once("$level/db/conn.php");
// CREATE query
$sql = "SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'1' as x FROM job_order WHERE worker_id=:id AND created_on BETWEEN NOW() - INTERVAL 1 DAY AND NOW()
        UNION
        SELECT CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'2' as x FROM job_order WHERE worker_id=:id AND created_on BETWEEN NOW() - INTERVAL 7 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'3' as x FROM job_order WHERE worker_id=:id AND created_on BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'4' as x FROM job_order WHERE worker_id=:id
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'5' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=3 AND date_time_closed BETWEEN NOW() - INTERVAL 1 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'6' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=3 AND date_time_closed BETWEEN NOW() - INTERVAL 7 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'7' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=3 AND date_time_closed BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'8' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=3
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'9' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=2 AND date_time_closed BETWEEN NOW() - INTERVAL 1 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'10' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=2 AND date_time_closed BETWEEN NOW() - INTERVAL 7 DAY AND NOW()
        UNION
        SELECT CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'11' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=2 AND date_time_closed BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'12' as x FROM job_order WHERE worker_id=:id AND job_order_status_id=2
        ";

// Prepare statement
$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

$accepted1 = $result[0]['count'];
$accepted2 = $result[1]['count'];
$accepted3 = $result[2]['count'];
$accepted4 = $result[3]['count'];
$cancelled1 = $result[4]['count'];
$cancelled2 = $result[5]['count'];
$cancelled3 = $result[6]['count'];
$cancelled4 = $result[7]['count'];
$completed1 = $result[8]['count'];
$completed2 = $result[9]['count'];
$completed3 = $result[10]['count'];
$completed4 = $result[11]['count'];

$sql = "SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'1' as x FROM bill WHERE worker_id=:id AND is_received_by_worker=1 AND created_on BETWEEN NOW() - INTERVAL 1 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'2' as x FROM bill WHERE worker_id=:id AND is_received_by_worker=1 AND created_on BETWEEN NOW() - INTERVAL 7 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'3' as x FROM bill WHERE worker_id=:id AND is_received_by_worker=1 AND created_on BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
        UNION
        SELECT  CASE WHEN COUNT(id) IS NULL THEN 0 ELSE COUNT(id) END as `count`,'4' as x FROM bill WHERE worker_id=:id AND is_received_by_worker=1;";

$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
$bill1 = $result[0]['count'];
$bill2 = $result[1]['count'];
$bill3 = $result[2]['count'];
$bill4 = $result[3]['count'];

//5-8. Ratings 
// CREATE query
$sql = "SELECT IFNULL(AVG(overall_quality), 0) as `a`, IFNULL(AVG(professionalism), 0) as `b`, IFNULL(AVG(reliability), 0) as `c`, IFNULL(AVG(punctuality), 0) as `d` 
        FROM rating WHERE rated_worker=:id AND created_on BETWEEN NOW() - INTERVAL 1 DAY AND NOW()
        UNION
        SELECT IFNULL(AVG(overall_quality), 0) as `a`, IFNULL(AVG(professionalism), 0) as `b`, IFNULL(AVG(reliability), 0) as `c`, IFNULL(AVG(punctuality), 0) as `d` 
        FROM rating WHERE rated_worker=:id AND created_on BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
        UNION
        SELECT IFNULL(AVG(overall_quality), 0) as `a`, IFNULL(AVG(professionalism), 0) as `b`, IFNULL(AVG(reliability), 0) as `c`, IFNULL(AVG(punctuality), 0) as `d` 
        FROM rating WHERE rated_worker=:id AND created_on BETWEEN NOW() - INTERVAL 60 DAY AND (NOW() - INTERVAL 31 DAY)
        ";

// Prepare statement
$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

$a1 = round($result2[0]['a'],2);
$b1 = round($result2[0]['b'],2);
$c1 = round($result2[0]['c'],2);
$d1 = round($result2[0]['d'],2);
$e1 = round((($a1+$b1+$c1+$d1)/4),2);

$a2 = round($result2[1]['a'],2);
$b2 = round($result2[1]['b'],2);
$c2 = round($result2[1]['c'],2);
$d2 = round($result2[1]['d'],2);
$e2 = round((($a2+$b2+$c2+$d2)/4),2);

$a3 = round($result2[2]['a'],2);
$b3 = round($result2[2]['b'],2);
$c3 = round($result2[2]['c'],2);
$d3 = round($result2[2]['d'],2);
$e3 = round((($a3+$b3+$c3+$d3)/4),2);


$sql = "SELECT IFNULL(AVG(overall_quality), 0) as `a`, IFNULL(AVG(professionalism), 0) as `b`, IFNULL(AVG(reliability), 0) as `c`, IFNULL(AVG(punctuality), 0) as `d` 
        FROM `rating` WHERE `rated_worker`=:id;
        ";

// Prepare statement
$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $result3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

$a4 = round($result3[0]['a'],2);
$b4 = round($result3[0]['b'],2);
$c4 = round($result3[0]['c'],2);
$d4 = round($result3[0]['d'],2);
$e4 = round((($a4+$b4+$c4+$d4)/4),2);

//9. job completed by categories

//10. 3 recently completed






?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/header-worker.css">
<link rel="stylesheet" href="../../css/headers/worker-side-nav.css">
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<!-- <link rel="stylesheet" href="../../css/pages/homeowner/homeowner-create-project.css"> -->
<!-- === Link your custom CSS  pages above here ===-->
</head>

<body class="container-fluid m-0 p-0  w-100 bg-light">
    <!-- Add your Header NavBar here-->
    <?php
    $headerLink_Selected = 4;
    require_once dirname(__FILE__) . "/$level/components/headers/worker-signed-in.php";
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
        <!-- === Your Custom Page Content Goes Here below here === -->

        <?php
        $current_nav_side_tab = "Metrics";
        require_once dirname(__FILE__) . "/$level/components/headers/worker-side-nav.php";
        ?>
        <div class="col-md-10">
            <div class="container container-full  w-100 m-lg-0 p-0 min-height ml-3">
                <main role="main" class="col-md-9 col-lg-10 pt-3 px-4 " style="margin-left:30%; margin-right:40%">

                    <h1 class="text-center">My Metrics</h1>
                    <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                        View your work statistics and ratings to see your performance.
                    </h5>

                    <hr color="yellow" style="height:5px">

                    <h4 class="mb-4 mt-2 mx-2">Job Summary</h4>
                    <table class="table table-bordered">

                        <thead class="text-center thead-light">
                            <th style="width:35%">Statistics</th>
                            <th style="width:15%">Today</th>
                            <th style="width:15%">Past 7 Days</th>
                            <th style="width:15%">Past 30 Days</th>
                            <th style="width:20%">Overall Total</th>
                        </thead>

                        <tbody>
                            <tr>
                                <th>Jobs accepted</th>
                                <td class="text-center"><?php echo $accepted1 == null ? 0 : $accepted1;?></td>
                                <td class="text-center"><?php echo $accepted2 == null ? 0 : $accepted2;?></td>
                                <td class="text-center"><?php echo $accepted3 == null ? 0 : $accepted3;?></td>
                                <th class="text-center" style="font-size:large"><?php echo $accepted4 == null ? 0 : $accepted4;?></th>
                            </tr>

                            <tr>
                                <th>Jobs completed</th>
                                <td class="text-center"><?php echo $completed1 == null ? 0 : $completed1;?></td>
                                <td class="text-center"><?php echo $completed2 == null ? 0 : $completed2;?></td>
                                <td class="text-center"><?php echo $completed3 == null ? 0 : $completed3;?></td>
                                <th class="text-center" style="font-size:large"><?php echo $completed4 == null ? 0 : $completed4;?></th>
                            </tr>


                            <tr>
                                <th>Jobs cancelled</th>
                                <td class="text-center"><?php echo $cancelled1 == null ? 0 : $cancelled1;?></td>
                                <td class="text-center"><?php echo $cancelled2 == null ? 0 : $cancelled2;?></td>
                                <td class="text-center"><?php echo $cancelled3 == null ? 0 : $cancelled3;?></td>
                                <th class="text-center" style="font-size:large"><?php echo $cancelled4 == null ? 0 : $cancelled4;?></th>
                            </tr>


                            <tr>
                                <th>Bills fulfilled</th>
                                <td class="text-center"><?php echo $bill1 == null ? 0 : $bill1;?></td>
                                <td class="text-center"><?php echo $bill2 == null ? 0 : $bill2;?></td>
                                <td class="text-center"><?php echo $bill3 == null ? 0 : $bill3;?></td>
                                <th class="text-center" style="font-size:large"><?php echo $bill4 == null ? 0 : $bill4;?></th>
                            </tr>

                            <tr>
                                <th>Revenue generated</th>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <th class="text-center" style="font-size:large"></th>
                            </tr>

                        </tbody>
                    </table>

                    <hr color="yellow" style="height:5px">

                    <h4 class="mb-4 mt-2 mx-2">Rating Summary</h4>
                    <table class="table table-bordered">

                        <thead class="text-center thead-light">
                            <th style="width:35%">Criteria</th>
                            <th style="width:15%">Today</th>
                            <th style="width:15%">Past 1-30 days</th>
                            <th style="width:15%">Past 31-60 days</th>
                            <th style="width:20%">Overall Average</th>
                        </thead>

                        <tbody>
                            <tr>
                                <th>Overall quality</th>
                                <td class="text-center"><?php echo $a1 == null ? "N/A" : $a1;?></td>
                                <td class="text-center"><?php echo $a2 == null ? "N/A" : $a2;?></td>
                                <td class="text-center"><?php echo $a3 == null ? "N/A" : $a3;?></td>
                                <th class="text-center"><?php echo $a4 == null ? "N/A" : $a4;?></th>
                            </tr>

                            <tr>
                                <th>Professionalism</th>
                                <td class="text-center"><?php echo $b1 == null ? "N/A" : $b1;?></td>
                                <td class="text-center"><?php echo $b2 == null ? "N/A" : $b2;?></td>
                                <td class="text-center"><?php echo $b3 == null ? "N/A" : $b3;?></td>
                                <th class="text-center"><?php echo $b4 == null ? "N/A" : $b4;?></th>
                            </tr>

                            <tr>
                                <th>Reliability</th>
                                <td class="text-center"><?php echo $c1 == null ? "N/A" : $c1;?></td>
                                <td class="text-center"><?php echo $c2 == null ? "N/A" : $c2;?></td>
                                <td class="text-center"><?php echo $c3 == null ? "N/A" : $c3;?></td>
                                <th class="text-center"><?php echo $c4 == null ? "N/A" : $c4;?></th>
                            </tr>


                            <tr>
                                <th>Punctuality</th>
                                <td class="text-center"><?php echo $d1 == null ? "N/A" : $d1;?></td>
                                <td class="text-center"><?php echo $d2 == null ? "N/A" : $d2;?></td>
                                <td class="text-center"><?php echo $d3 == null ? "N/A" : $d3;?></td>
                                <th class="text-center"><?php echo $d4 == null ? "N/A" : $d4;?></th>
                            </tr>


                            <tr>
                                <th>General Average</th>
                                <th class="text-center" style="font-size:large"><?php echo $e1 == null ? "N/A" : $e1;?></th>
                                <th class="text-center" style="font-size:large"><?php echo $e2 == null ? "N/A" : $e2;?></th>
                                <th class="text-center" style="font-size:large"><?php echo $e3 == null ? "N/A" : $e3;?></th>
                                <th class="text-center" style="font-size:larger"><?php echo $e4 == null ? "N/A" : $e4;?></th>
                            </tr>


                        </tbody>
                    </table>
                    
                    <br>
                            <br>
                            <br>
                            <br>
<!-- 
                    <hr color="yellow" style="height:5px">

                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">

                                <h4 class="mb-4 mt-2 mx-2">Jobs completed by category</h4>
                                <table class="table table-bordered">

                                    <thead class="text-center thead-light">
                                        <th style="width:60%">Category</th>
                                        <th style="width:40%">Completed</th>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>


                                <hr color="yellow" style="height:5px">
                                <h4 class="mb-4 mt-2 mx-2">Recently completed jobs</h4>
                                <table class="table table-bordered">

                                    <thead class="text-center thead-light">
                                        <th style="width:60%">Category</th>
                                        <th style="width:40%">Completed</th>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="col-md-6">

                                <h4 class="mb-4 mt-2 mx-2">Jobs completed by location</h4>
                                <table class="table table-bordered">

                                    <thead class="text-center thead-light">
                                        <th style="width:60%">Location/City</th>
                                        <th style="width:40%">Completed</th>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>


                                <hr color="yellow" style="height:5px">
                                <h4 class="mb-4 mt-2 mx-2">Top rated jobs</h4>
                                <table class="table table-bordered">

                                    <thead class="text-center thead-light">
                                        <th style="width:60%">Category</th>
                                        <th style="width:40%">Completed</th>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> -->

                </main>
            </div>
        </div>


        <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
    <?php require_once dirname(__FILE__) . "/$level/components/foot-meta.php"; ?>
    <!-- Custom JS Scripts Below -->
    <!-- <script src="../../js/pages/user-home.js"></script> -->
    <script>



    </script>
</body>

</html>