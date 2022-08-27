<?php

session_start();
if (!isset($_SESSION["token"])) {
    header("Location: ../../");
    exit();
}

$level = "../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";

$query = isset($_GET["query"]) ? "%" . trim(htmlentities($_GET["query"])) . "%" : null;
$query2 = isset($_GET["query"]) ? trim(htmlentities($_GET["query"])) : null;
$statusCheck = 0;
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
        $current_nav_side_tab = "Past Projects";
        require_once dirname(__FILE__) . "/$level/components/headers/worker-side-nav.php";
        ?>
        <div class="col-md-10">
            <div class="container container-full  w-100 m-lg-0 p-0 min-height ml-3">
                <main role="main" class="col-md-9 col-lg-10 pt-3 px-4 " style="margin-left:30%; margin-right:40%">

                    <h1 class="text-center">Past Projects</h1>

                    <form id="search-bar" method="get" action="<?php echo "$_SERVER[REQUEST_URI]"; ?>">
                        <div class="input-group rounded">
                            <input id="query" name="query" type="search" class="form-control rounded" placeholder="Search project title, type, description, status, name, street, barangay, city..." aria-label="Search" aria-describedby="search-addon" />
                            <span class="input-group-text border-0" id="search-addon">
                                <i id="submit" class="fas fa-search" style="cursor:pointer"></i>
                            </span>
                        </div>
                    </form>

                    <?php
                    /* Database search: List of active job postings that matches the worker's skillset */
                    require_once("$level/db/conn.php");
                    // CREATE query
                    $sql = "SELECT jp.id, jp.home_id, CONCAT(h.street_no,' ', h.street_name, ', ', b.barangay_name, ', ', c.city_name,' city') as `complete_address`, jp.job_size_id, jos.job_order_size, jp.required_expertise_id, pt.type as `project_type`, e.id as `expertise_id`, e.expertise, jp.job_post_status_id, jp.cancellation_reason,jp.job_description, jp.rate_offer, jp.rate_type_id, rt.type as `rate_type`, jp.preferred_date_time, jp.job_post_name, jo.id as `job_order_id`, jo.isRated, r.overall_quality, r.professionalism, r.reliability, r.punctuality, r.comment, CONCAT(u.first_name, ' ' ,u.last_name) as `posted_by`, jo.job_order_status_id, bill.bill_status_id, bill.total_price_billed, bill.date_time_completion_paid, jo.order_cancellation_reason, jo.cancelled_by, jo.homeowner_id as `homeowner_id`, u.phone_no
            FROM home h, barangay b, city c, job_order_size jos, project_type pt, expertise e, rate_type rt, job_post jp
            LEFT JOIN job_order jo on jp.id = jo.job_post_id 
            LEFT JOIN rating r on jo.id = r.job_order_id 
            LEFT JOIN hh_user u on jo.homeowner_id = u.user_id
            LEFT JOIN bill on jo.id = bill.job_order_id
            WHERE jp.home_id = h.id
            AND h.barangay_id = b.id
            AND b.city_id = c.id
            AND jp.job_size_id = jos.id
            AND jp.required_expertise_id = pt.id
            AND pt.expertise = e.id
            AND jp.rate_type_id = rt.id
            AND jp.is_deleted = 0
            AND jo.worker_id= :id ";

                    //check search query
                    if ($query != null) {



                        //hard coding the project status
                        if (
                            $query2 == "completed" || $query2 == "Completed" || $query2 == "COMPLETED" ||
                            $query2 == "complete"  || $query2 == "Complete"  || $query2 == "COMPLETE"
                        ) {
                            $statusCheck = 1;
                            $sql .= " AND jo.job_order_status_id = 2 ";
                        } else if (
                            $query2 == "cancelled" || $query2 == "Cancelled" || $query2 == "CANCELLED" ||
                            $query2 == "cancel"    || $query2 == "Cancel"    || $query2 == "CANCEL"
                        ) {
                            $statusCheck = 1;
                            $sql .= " AND (jo.job_order_status_id = 3 OR jp.job_post_status_id = 4 OR (jp.job_post_status_id = 1 AND jp.preferred_date_time < CURRENT_TIMESTAMP)) ";
                        } else {
                            $statusCheck = 0;
                            $sql .= " AND (jo.job_order_status_id = 2 OR jo.job_order_status_id = 3 OR jp.job_post_status_id = 4 OR (jp.job_post_status_id = 1 AND jp.preferred_date_time < CURRENT_TIMESTAMP)) ";
                            $sql .= " AND (jp.job_description LIKE :query
                             OR CONCAT(u.first_name, ' ' ,u.last_name) LIKE :query
                             OR h.street_name LIKE :query
                             OR b.barangay_name LIKE :query
                             OR c.city_name LIKE :query
                             OR pt.type LIKE :query
                             OR jp.job_post_name LIKE :query
                             ) ";
                        }
                    };


                    // Prepare statement
                    $stmt =  $conn->prepare($sql);
                    $result = "";

                    // Only fetch if prepare succeeded
                    if ($stmt !== false) {
                        $stmt->bindparam(':id', $_SESSION['id']);
                        if ($query != null && $statusCheck==0) {
                            $stmt->bindparam(':query', $query);
                        }

                        $stmt->execute();
                        $closedProjects = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    }

                    $stmt = null;
                    ?>


                    <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                        Showing <?php echo count($closedProjects) ?> Projects
                        <?php
                        if ($query != null) {
                            if (
                                $query2 == "completed" || $query2 == "Completed" || $query2 == "COMPLETED" ||
                                $query2 == "complete"  || $query2 == "Complete"  || $query2 == "COMPLETE"
                            ) {
                                echo "matching the project status \"COMPLETED\"";
                            } else if (
                                $query2 == "cancelled" || $query2 == "Cancelled" || $query2 == "CANCELLED" ||
                                $query2 == "cancel"    || $query2 == "Cancel"    || $query2 == "CANCEL"
                            ) {
                                echo "matching the project status \"CANCELLED\"";
                            } else {
                                echo "matching the search query \"$query2\"";
                            }
                        }
                        ?>
                    </h5>
                    <?php
                    if (count($closedProjects) == 0 || $closedProjects == null) {
                    ?>

                        <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                            You have no closed/cancelled projects.
                        </h5>
                    <?php
                    } else {
                        for ($p = 0; $p < count($closedProjects); $p++) {

                            // Grab address value
                            $address = $closedProjects[$p]['complete_address'];

                            // Grab Schedule value
                            $pref_sched = $closedProjects[$p]['preferred_date_time'];
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
                            $t_formatted =  $hours_formatted . ':' . $minutes . ' ' . $end;
                            $d_formatted = $d_array[0] . ' ' . $d_array[2] . ' ' . $d_array[1] . ' at ' . $t_formatted;

                            // Grab job order size
                            $job_order_size = $closedProjects[$p]['job_order_size'];
                            // Grab job description
                            $job_desc = $closedProjects[$p]['job_description'];
                            // Grab job title
                            $job_title = $closedProjects[$p]['job_post_name'];
                            // Grab job status
                            $job_status = $closedProjects[$p]['job_post_status_id'];
                            // Grab job id
                            $job_id = $closedProjects[$p]['id'];
                            // Grab home_id
                            $home_id = $closedProjects[$p]['home_id'];
                            // Grab rate_type_id
                            $rate_type_id = $closedProjects[$p]['rate_type_id'];
                            // Grab job_size_id
                            $job_size_id = $closedProjects[$p]['job_size_id'];


                            // Grab is_rated
                            $isRated = $closedProjects[$p]['isRated'];
                            // Grab job_orderid
                            $job_order_id = $closedProjects[$p]['job_order_id'];
                            // Grab cancellation reason
                            $cancellation_reason =  $closedProjects[$p]['cancellation_reason'];
                            // Grab job order status
                            $job_order_status_id = $closedProjects[$p]['job_order_status_id'];
                            // Grab posted by
                            $posted_by = $closedProjects[$p]['posted_by'];
                            // Grab bill status
                            $bill_status_id = $closedProjects[$p]['bill_status_id'];
                            // Grab project type 
                            $project_type = $closedProjects[$p]['project_type'];

                            $today = new \DateTime();
                            // Check if the date is beyond today's date & not have job order. Otherwise mark it as expired.
                            if ($job_status != 2 && $today > $d) {
                                $job_status = 3;
                            }

                            $tab_link = "&tab=closed";

                            // Grab date time completion paid status
                            $date_paid = $closedProjects[$p]['date_time_completion_paid'];

                            // Grab rate_offer 
                            $rate_offer = $closedProjects[$p]['rate_offer'];

                            // Grab cancelled_by 
                            $cancelled_by = $closedProjects[$p]['cancelled_by'];
                            // Grab homeowner_id
                            $homeowner_id = $closedProjects[$p]['homeowner_id'];
                            // Grab order_cancellation_reason
                            $order_cancellation_reason = $closedProjects[$p]['order_cancellation_reason'];

                            // For billing
                            $total_price_billed  = $closedProjects[$p]['total_price_billed'] ?? null;
                            $date_time_completion_paid = $closedProjects[$p]['date_time_completion_paid'] ?? null;
                            $computedRating = 0;
                            if ($isRated != null) {
                                $computedRating = ($closedProjects[$p]['overall_quality']
                                    + $closedProjects[$p]['professionalism']
                                    + $closedProjects[$p]['reliability']
                                    + $closedProjects[$p]['punctuality']) / 4.0;
                            }


                            $phone_no = $closedProjects[$p]['phone_no'];


                            include dirname(__FILE__) . "/" . $level . '/components/cards/project-worker.php';
                        }
                        // Clear values;
                        $address = null;
                        $d = null;
                        $d_parsed = null;
                        $d_array = null;
                        $t = null;
                        $hours = null;
                        $minutes = null;
                        $end = null;
                        $hours_formatted = null;
                        $t_formatted = null;
                        $d_formatted = null;
                        $pref_sched = null;
                        $job_order_size = null;
                        $job_desc = null;
                        $job_title = null;
                        $job_status = null;
                        $job_id = null;
                        $home_id = null;
                        $rate_type_id = null;
                        $job_size_id = null;
                        $is_rated = null;
                        $job_order_id = null;
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
        const summonZeSpinner = () => {
            Swal.fire({
                title: "",
                imageUrl: getDocumentLevel() + "/images/svg/Spinner-1s-200px.svg",
                imageWidth: 200,
                imageHeight: 200,
                imageAlt: 'Custom image',
                showCancelButton: false,
                showConfirmButton: false,
                background: 'transparent',
                allowOutsideClick: false
            });
        }
        const killZeSpinner = () => {
            swal.close();
        }

        const cancelProject = (projectID, jobPostName, project_type_name, address, assigned_to) => {
            console.log(projectID);
            let data = {};
            data['projectID'] = projectID;
            data['job_post_name'] = jobPostName;
            data['project_type_name'] = project_type_name;
            data['home_address_label'] = address;
            data['assigned_to'] = assigned_to;
            data['user_type'] = 'worker';
            loadModal("cancel-project", modalTypes, () => {}, getDocumentLevel(), data);
        }

        const reportBill = (job_order_id, $address, $assigned_to) => {
            console.log(job_order_id);
            summonZeSpinner();
            let data = {};
            data['job_order_id'] = job_order_id;
            data['address'] = $address;
            data['assigned_to'] = $assigned_to;
            data['user_type'] = 'worker';
            loadModal("report-bill", modalTypes, () => {
                killZeSpinner();
            }, getDocumentLevel(), data);
        }

        const reportProblem = (job_order_id, jobPostName, project_type_name, address, assigned_to) => {
            // console.log(job_order_id);
            summonZeSpinner();
            let data = {};
            data['job_order_id'] = job_order_id;
            data['job_post_name'] = jobPostName;
            data['project_type_name'] = project_type_name;
            data['home_address_label'] = address;
            data['assigned_to'] = assigned_to;
            data['user_type'] = 'worker';
            loadModal("report-problem", modalTypes, () => {
                killZeSpinner();
            }, getDocumentLevel(), data);
        }

        $("#submit").click(function() {
            if ($("#query").val() != null && $("#query").val() != "") {
                $("#search-bar").submit();
            } else {
                let path = window.location.href.split('?')[0];
                window.location.replace(path);
            }
        })
    </script>
</body>

</html>