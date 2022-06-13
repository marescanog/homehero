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

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/header-worker.css">
<link rel="stylesheet" href="../../css/headers/worker-side-nav.css">
<link rel="stylesheet" href="../../css/pages/homeowner/projects.css">
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<!-- <link rel="stylesheet" href="../../css/pages/homeowner/homeowner-create-project.css"> -->
<!-- === Link your custom CSS  pages above here ===-->
</head>

<body class="container-fluid m-0 p-0  w-100 bg-light">
    <!-- Add your Header NavBar here-->
    <?php
    $headerLink_Selected = 0;
    require_once dirname(__FILE__) . "/$level/components/headers/worker-signed-in.php";
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
        <!-- === Your Custom Page Content Goes Here below here === -->

        <?php
        $current_nav_side_tab = "Interested Clients";
        require_once dirname(__FILE__) . "/$level/components/headers/worker-side-nav.php";
        ?>
        <div class="col-md-10">
            <div class="container container-full  w-100 m-lg-0 p-0 min-height ml-3">
                <main role="main" class="col-md-9 col-lg-10 pt-3 px-4 " style="margin-left:30%; margin-right:40%">

                    <h1 class="text-center">Interested Clients</h1>

                    <?php
                    /* Database search: List of active job postings that matches the worker's skillset */
                    require_once("$level/db/conn.php");
                    // CREATE query
                    $sql = " SELECT jp.id, jp.homeowner_id, hh.user_id, hh.first_name, hh.last_name, hh.phone_no, h.street_no, h.street_name, b.barangay_name, c.city_name, jp.job_size_id, jp.job_post_status_id, jos.job_order_size, pt.type as `project_type`, e.expertise, jp.job_description, jp.rate_offer, jp.rate_type_id, jp.preferred_date_time, jp.created_on, jp.job_post_name
                FROM job_post jp, hh_user hh, home h, homeowner ho, barangay b, city c, job_order_size js, project_type pt, expertise e, rate_type rt, job_order_size jos, homeowner_notification hn
                WHERE jp.homeowner_id=ho.id
                AND jp.job_size_id = jos.id
                AND jp.rate_type_id = rt.id
                AND ho.id=hh.user_id
                AND jp.home_id=h.id
                AND h.barangay_id=b.id
                AND b.city_id=c.id
                AND jp.required_expertise_id=pt.id
                AND pt.expertise=e.id

                AND hn.post_id=jp.id
                
            
                AND jp.job_post_status_id=1
                AND jp.is_deleted=0
                AND c.id IN (SELECT city_id FROM city_preference WHERE worker_id=:id)
                AND jp.required_expertise_id IN (SELECT skill FROM skillset WHERE worker_id=:id) 
                AND jp.id NOT IN (SELECT post_id FROM worker_decline_post WHERE worker_id=:id)  

                AND hn.worker_id=:id
                AND hn.is_deleted=0

                GROUP BY jp.id ";

                    // Prepare statement
                    $stmt =  $conn->prepare($sql);
                    $result = "";

                    // Only fetch if prepare succeeded
                    if ($stmt !== false) {
                        $stmt->bindparam(':id', $_SESSION['id']);
                        $stmt->execute();
                        $ongoingJobPosts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    }

                    $stmt = null;
                    if (count($ongoingJobPosts) == 0 || $ongoingJobPosts == null) {
                    ?>

                        <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                            You have no notifications yet from homeowners. See more job postings in <a href="./home.php">Opportunites</a>.
                        </h5>
                        <?php
                    } else { 
                    ?>
                        <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                            Below are the homeowners who picked you as their preferred workers for their projects.
                        </h5>

                    <?php
                        for ($p = 0; $p < count($ongoingJobPosts); $p++) {
                        // Grab address value
                        $address = $ongoingJobPosts[$p]['street_no']." ".$ongoingJobPosts[$p]['street_name'].", ".$ongoingJobPosts[$p]['barangay_name'].", ".$ongoingJobPosts[$p]['city_name'];
                                    
                        // Grab Schedule value
                        $pref_sched = $ongoingJobPosts[$p]['preferred_date_time'];
                            // Instantiate a DateTime with microseconds.
                            $d = new DateTime($pref_sched);
                            // Custom date time formatting
                            $d_parsed = $d->format(DateTimeInterface::RFC7231);
                            $d_array = explode(" ", $d_parsed);
                            $t = $d_array[4];
                            $hours = substr($t, 0, 2);
                            $minutes = substr($t, 3, 2);
                            $end =  $hours >= 12 ? 'PM' : 'AM';
                            $hours_formatted =  $hours > 12 ? $hours - 12 : (int) $hours;
                            $t_formatted =  $hours_formatted.':'.$minutes.' '.$end;
                            $d_formatted = $d_array[0].' '.$d_array[2].' '.$d_array[1].' at '.$t_formatted;

                        // Grab job order size
                        $job_order_size = $ongoingJobPosts[$p]['job_order_size'];
                        // Grab job description
                        $job_desc = $ongoingJobPosts[$p]['job_description'];
                        // Grab job title
                        $job_title = $ongoingJobPosts[$p]['job_post_name'];
                        // Grab job status
                        $job_status = $ongoingJobPosts[$p]['job_post_status_id'];
                        // Grab rate_offer 
                        $rate_offer = $ongoingJobPosts[$p]['rate_offer'];
                        // Grab project type 
                        $project_type = $ongoingJobPosts[$p]['project_type'];
                        // Grab homeowner name
                        $posted_by = $ongoingJobPosts[$p]['first_name']." ".$ongoingJobPosts[$p]['last_name'];
                        // Grab phone number 
                        $phone_no = $ongoingJobPosts[$p]['phone_no'];
                            
                        $is_rated = null;
                        $job_order_id = null;
                        $cancellation_reason = null;

                        $tab_link = "";

                        // For edit modal
                            // Grab rate_type_id
                            $rate_type_id = $ongoingJobPosts[$p]['rate_type_id'];
                            // Grab job_size_id
                            $job_size_id = $ongoingJobPosts[$p]['job_size_id'];
                            // Grab home_id
                            $homeowner_id = $ongoingJobPosts[$p]['homeowner_id'];
                            // Grab job id
                            $job_id = $ongoingJobPosts[$p]['id'];

                        // For billing undefined variable (This data array has none of these attibutes)
                        $isRated = null;
                        $total_price_billed  = null ;
                        $date_time_completion_paid = null;
                        $computedRating = 0;

                        include dirname(__FILE__)."/".$level.'/components/cards/project-worker.php';
                        }
                    }
                    ?>












                </main>
            </div>
        </div>
    </div>


    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
    <?php require_once dirname(__FILE__) . "/$level/components/foot-meta.php"; ?>
    <!-- Custom JS Scripts Below -->
    <!-- <script src="../../js/pages/user-home.js"></script> -->
    <script src="./worker-actions/worker-actions.js"></script>
    <script>

        

    </script>
</body>

</html>