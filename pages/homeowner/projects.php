<?php 

session_start();
if(!isset($_SESSION["token"])){
    header("Location: ../../");
    exit();
}

// Declare variables to be used in this page
$level ="../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest"; // used by header
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU"; // used by header

$currentTab = isset($_GET['tab']) ? $_GET['tab'] : "open"; // used to direct to closed tab

 // CHANGELINKDEVPROD
// Curl request to get data to fill projects page

   $url = "http://localhost/slim3homeheroapi/public/homeowner/get-projects"; // DEV
  // $url = "https://slim3api.herokuapp.com//homeowner/get-projects"; // PROD

$headers = array(
    "Authorization: Bearer ".$_SESSION["token"],
    'Content-Type: application/json',
);

// 1. Initialize
$ch = curl_init();

// 2. set options
    // URL to submit to
    curl_setopt($ch, CURLOPT_URL, $url);

    // Return output instead of outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Type of request = GET
    curl_setopt($ch, CURLOPT_HTTPGET, 1);

    // Set headers for auth
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    // Moved inside Modal Body for better display of error messages
    $mode = "PROD"; // DEV to see verbose error messsages, PROD for production build
    $curl_error_message = null;

    // ERROR HANDLING 
    if($output === FALSE){
        $curl_error_message = curl_error($ch);
    }

    curl_close($ch);

    // $output =  json_decode(json_encode($output), true);
    $output =  json_decode($output);
    
    // Declare variables to be used in this page
    $ongoingJobPosts = [];
    $ongoingProjects = [];
    $closedProjects = [];

    if(is_object($output) && $output->success == true){
        $ongoingJobPosts = $output->response->ongoingJobPosts;
        $ongoingProjects = $output->response->ongoingProjects;
        $closedProjects = $output->response->closedProjects;
    }


// HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../../css/headers/header-homeowner.css">
<link rel="stylesheet" href="../../css/footer.css">
<link rel="stylesheet" href="../../css/pages/homeowner/projects.css">
<!-- === Link your custom CSS  pages above here ===-->
</head>
<body class="container-fluid m-0 p-0  w-100 min-body-height">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/ho-signed-in.php"; 
    ?>
    <div class="min-body-height d-flex flex-column justify-content-between <?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <!-- Main Content -->
    <div class="container w-100 m-0 p-0 min-body-height h-100 ml-auto mr-auto gray-font d-flex flex-column">
        <?php //--------------- PHP ZONE ------------------------
        // ERROR HANDLING - Curl Error
        if($curl_error_message !== null){
        ?><!-------------------------------------------------->
         <!-- HTML ZONE : CURL ERROR HANDLING & MESSAGE DISPLAY -->

        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <div>
                <strong>Curl Error!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p><?php echo $curl_error_message;?></p>
        </div>

        <?php //--------------- PHP ZONE ------------------------
        } // closing bracket for if

        // ERROR HANDLING - 404 curl URL
        if(!$curl_error_message &&  !is_object($output)){
        ?><!-------------------------------------------------->
        <!-- HTML ZONE : 404 ERROR HANDLING & MESSAGE DISPLAY -->


        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <div>
                <b>SERVER ERROR!</b>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p>Server issue or API link not found!</p>
        </div>
    

        <?php //--------------- PHP ZONE ------------------------
        }// closing bracket for if

        // ERROR HANDLING - SERVER ERRORS
        if(!$curl_error_message &&  is_object($output) && ($output->success == false)){
        ?><!-------------------------------------------------->
        <!-- HTML ZONE : SERVER ERROR HANDLING & MESSAGE DISPLAY -->


        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <!-- TITLE -->
            <div>
                <h5>
                    <?php
                        if ($output->response && $output->response->status == 500 ){
                            echo "SERVER ERROR 500";
                        } else if ($output->response && $output->response->status == 401 ){
                            echo "SERVER ERROR 401: NOT FOUND";
                        } else {
                            echo "SERVER ERROR";
                        }
                    ?>
                </h5>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- BODY -->
            <div>
                <?php 
                    if($output->response->status){
                        echo $output->response->message;
                    }
                ?>
                </div>
        </div>





        <?php //--------------- PHP ZONE ------------------------
        }// closing bracket for if

        //echo var_dump($output);

        ?><!-------------------------------------------------->
        <!-- HTML ZONE - MAIN CONTENT -->

        <div> <!-- FOR TESTING -->
        <p>
            <?php 
                // $test = date($output->response->ongoingProjects[0]->preferred_date_time);
                 // echo var_dump($output->response->closedProjects);
            ?>
        </p>
        </div> <!-- FOR TESTING -->




        <!-- MAIN CONTENT -->
        <div class="h-100">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mx-2 mx-lg">
                    <li class="breadcrumb-item" aria-current="page"><a href="#">All projects</a></li>
                </ol>
            </nav>
            <div class="mt-0 mb-2 d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mx-2 mx-lg-0 mt-0 mb-2">Your Projects</h1>
                </div>
                <!-- <div class="sidelink">
                    <p class="mt-3 mr-3 mr-lg-0 text-danger">CANCEL</p>
                </div> -->
            </div>
        </div>
        <div class="h-100">
            <div  id="tabs" class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <!-- ============= -->
                        <!-- Job Posts Tab -->
                        <!-- ============= -->
                        <p class="nav-item nav-link 
                        <?php 
                            if($currentTab != null){
                                switch($currentTab){
                                    case "closed":
                                    break;
                                    case "orders":
                                    break;
                                    default:
                                        echo "active";
                                }
                            } else {
                                echo "active";
                            }
                        ?>" 
                            id="nav-hire-tab" data-toggle="tab" href="#nav-hire" role="tab" aria-controls="nav-hire" 
                            aria-selected="<?php 
                            if($currentTab != null){
                                switch($currentTab){
                                    case "closed":
                                    break;
                                    case "orders":
                                    break;
                                    default:
                                        echo "true";
                                }
                            } else {
                                echo "true";
                            }
                            ?>">
                            Job Posts
                        </p>
                        <!-- ============= -->
                        <!-- Job Orders Tab -->
                        <!-- ============= -->
                        <p class="nav-item nav-link 
                            <?php 
                                if($currentTab != null){
                                    if($currentTab == "orders"){
                                        echo "active";
                                    }
                                }
                            ?>" 
                            id="nav-work-tab" data-toggle="tab" href="#nav-orders" role="tab" aria-controls="nav-orders" aria-selected="
                            <?php 
                                if($currentTab != null){
                                    if($currentTab == "orders"){
                                        echo "true";
                                    }
                                }
                            ?>">
                            Ongoing Projects
                        </p>
                        <!-- ==================== -->
                        <!-- Closed Projects Tab -->
                        <!-- ==================== -->
                        <p class="nav-item nav-link 
                            <?php 
                                if($currentTab != null){
                                    if($currentTab == "closed"){
                                        echo "active";
                                    }
                                }
                            ?>" 
                            id="nav-work-tab" data-toggle="tab" href="#nav-work" role="tab" aria-controls="nav-work" aria-selected="
                            <?php 
                                if($currentTab != null){
                                    if($currentTab == "closed"){
                                        echo "true";
                                    }
                                }
                            ?>">
                            Closed Projects
                        </p>
                    </div>
                </nav>
                <div class="tab-content pt-1 pb-2 px-2  px-lg-3" id="nav-tabContent">
<!-- ==================== -->
<!-- Job Posts DISPLAY -->
<!-- ==================== -->
                    <div class="tab-pane fade 
                        <?php 
                            if($currentTab != null){
                                switch($currentTab){
                                    case "closed":
                                    break;
                                    case "orders":
                                    break;
                                    default:
                                        echo " show active";
                                }
                            } else {
                                echo " show active";
                            }
                        ?>" 
                        id="nav-hire" role="tabpanel" aria-labelledby="nav-hire-tab">
                        <?php //--------------- PHP ZONE ------------------------
                        // JOB POSTS DISPLAY - ONGOING
                        if(count($ongoingJobPosts) == 0 || $ongoingJobPosts == null){
                        ?><!-------------------------------------------------->
                        <!-- HTML ZONE: PROJECT DISPLAY - ONGOING -->

                            <h5 class="jumbotron-h1 mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                                You have no job posts.
                            </h5>

                        <?php //--------------- PHP ZONE ------------------------
                            } else {
                                // Loop through current data
                                for($p = 0 ; $p < count($ongoingJobPosts); $p++){
                                    
                                    // Grab address value
                                    $address = $ongoingJobPosts[$p]->complete_address;
                                    
                                    // Grab Schedule value
                                    $pref_sched = $ongoingJobPosts[$p]->preferred_date_time;
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
                                    $job_order_size = $ongoingJobPosts[$p]->job_order_size;
                                    // Grab job description
                                    $job_desc = $ongoingJobPosts[$p]->job_description;
                                    // Grab job title
                                    $job_title = $ongoingJobPosts[$p]->job_post_name;
                                    // Grab job status
                                    $job_status = $ongoingJobPosts[$p]->job_post_status_id;
                                    // Grab rate_offer 
                                    $rate_offer = $ongoingJobPosts[$p]->rate_offer;
                                    // Grab project type 
                                    $project_type = $ongoingJobPosts[$p]->project_type;
                                        
                                    $is_rated = null;
                                    $job_order_id = null;
                                    $cancellation_reason = null;

                                    $tab_link = "";
                                    $phone_no = null;

                                    // For edit modal
                                        // Grab rate_type_id
                                        $rate_type_id = $ongoingJobPosts[$p]->rate_type_id;
                                        // Grab job_size_id
                                        $job_size_id = $ongoingJobPosts[$p]->job_size_id;
                                        // Grab home_id
                                        $home_id = $ongoingJobPosts[$p]->home_id;
                                        // Grab job id
                                        $job_id = $ongoingJobPosts[$p]->id;

                                    // For billing undefined variable (This data array has none of these attibutes)
                                    $isRated = null;
                                    $total_price_billed  = null ;
                                    $date_time_completion_paid = null;
                                    $computedRating = 0;

                                    include dirname(__FILE__)."/".$level.'/components/cards/project-homeowner.php';
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

                                if( count($ongoingProjects) > 3){
                                ?><!-------------------------------------------------->
                                <!-- HTML ZONE: SHOW MORE PROJECTS -->
                                 <!-- Used for pagination / show more ajax (Disabled for now) -->
                                    <!-- <div id="show-more"></div>
                                    <div  class="d-flex mt-4 justify-content-center text-align-center">
                                        <button class="btn btn-lg text-white btn-warning">
                                            SHOW MORE
                                        </button>
                                    </div> -->
                                <?php //--------------- PHP ZONE ------------------------
                                }
                            }
                        ?>
                    </div>


<!-- =================== -->
<!-- Job orders display -->
<!-- =================== -->
                    <div class="tab-pane fade 
                    <?php 
                        if($currentTab != null){
                            if($currentTab == "orders"){
                                echo " show active";
                            }
                        }
                    ?>" 
                    id="nav-orders" role="tabpanel" aria-labelledby="nav-orders-tab">
                        <?php //--------------- PHP ZONE ------------------------
                        // PROJECT DISPLAY - CLOSED
                        if(count( $ongoingProjects) == 0 ||  $ongoingProjects == null){
                        ?><!-------------------------------------------------->
                        <!-- HTML ZONE: PROJECT DISPLAY - ONGOING -->

                            <h5 class="jumbotron-h1 mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                                You have no ongoing/pending projects.
                            </h5>

                        <?php //--------------- PHP ZONE ------------------------
                            } else {
                                // Loop through current data
                                for($p = 0 ; $p < count( $ongoingProjects); $p++){
                                    
                                    // Grab address value
                                    $address =  $ongoingProjects[$p]->complete_address;
                                    
                                    // Grab Schedule value
                                    $pref_sched =  $ongoingProjects[$p]->preferred_date_time;
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
                                    $job_order_size =  $ongoingProjects[$p]->job_order_size;
                                    // Grab job description
                                    $job_desc =  $ongoingProjects[$p]->job_description;
                                    // Grab job title
                                    $job_title =  $ongoingProjects[$p]->job_post_name;
                                    // Grab job status
                                    $job_status =  $ongoingProjects[$p]->job_post_status_id;
                                    // Grab job id
                                    $job_id =  $ongoingProjects[$p]->id;
                                    // Grab home_id
                                    $home_id = $ongoingProjects[$p]->home_id;
                                    // Grab rate_type_id
                                    $rate_type_id =  $ongoingProjects[$p]->rate_type_id;
                                    // Grab job_size_id
                                    $job_size_id = $ongoingProjects[$p]->job_size_id;

                                    // Grab job_orderid
                                    $job_order_id = $ongoingProjects[$p]->job_order_id;

                                    // Grab job order status
                                    $job_order_status_id =  $ongoingProjects[$p]->job_order_status_id;
                                     // Grab assigned to
                                     $assigned_to =  $ongoingProjects[$p]->assigned_to;

                                     $jo_start_time = $ongoingProjects[$p]->date_time_start;

                                    // Grab rate_offer 
                                    $rate_offer = $ongoingProjects[$p]->rate_offer;

                                    // Grab project type 
                                    $project_type = $ongoingProjects[$p]->project_type;

                                     $today = new \DateTime();
                                    // Check if the date is beyond today's date & not have job order. Otherwise mark it as expired.
                                    if(  $job_status != 2 && $today>$d){
                                        $job_status = 3;
                                    }
                                    
                                    $tab_link = "&tab=orders";

                                    // For billing undefined variable (This data array has none of these attibutes)
                                    $isRated = null;
                                    $total_price_billed  = null ;
                                    $date_time_completion_paid = null;
                                    $computedRating = 0;

                                    $phone_no = $ongoingProjects[$p]->phone_no;

                                    include dirname(__FILE__)."/".$level.'/components/cards/project-homeowner.php';
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

                                if( count($closedProjects) > 3){
                                ?><!-------------------------------------------------->
                                <!-- HTML ZONE: SHOW MORE PROJECTS -->
                                 <!-- Used for pagination / show more ajax (Disabled for now) -->
                                    <!-- <div id="show-more"></div>
                                    <div  class="d-flex mt-4 justify-content-center text-align-center">
                                        <button class="btn btn-lg text-white btn-warning">
                                            SHOW MORE
                                        </button>
                                    </div> -->
                                <?php //--------------- PHP ZONE ------------------------
                                }
                            }
                        ?>
                    </div>



<!-- ========================== -->
<!-- Closed/ Cancelled display -->
<!-- ========================== -->
                    <div class="tab-pane fade 
                    <?php 
                        if($currentTab != null){
                            if($currentTab == "closed"){
                                echo " show active";
                            }
                        }
                    ?>" 
                    id="nav-work" role="tabpanel" aria-labelledby="nav-work-tab">
                    <?php //--------------- PHP ZONE ------------------------
                        // PROJECT DISPLAY - CLOSED
                        if(count($closedProjects) == 0 || $closedProjects == null){
                        ?><!-------------------------------------------------->
                        <!-- HTML ZONE: PROJECT DISPLAY - ONGOING -->

                            <h5 class="jumbotron-h1 mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                                You have no closed/cancelled projects.
                            </h5>

                        <?php //--------------- PHP ZONE ------------------------
                            } else {
                                // Loop through current data
                                for($p = 0 ; $p < count($closedProjects); $p++){
                                    
                                    // Grab address value
                                    $address = $closedProjects[$p]->complete_address;
                                    
                                    // Grab Schedule value
                                    $pref_sched = $closedProjects[$p]->preferred_date_time;
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
                                    $job_order_size = $closedProjects[$p]->job_order_size;
                                    // Grab job description
                                    $job_desc = $closedProjects[$p]->job_description;
                                    // Grab job title
                                    $job_title = $closedProjects[$p]->job_post_name;
                                    // Grab job status
                                    $job_status = $closedProjects[$p]->job_post_status_id;
                                    // Grab job id
                                    $job_id = $closedProjects[$p]->id;
                                    // Grab home_id
                                    $home_id = $closedProjects[$p]->home_id;
                                    // Grab rate_type_id
                                    $rate_type_id = $closedProjects[$p]->rate_type_id;
                                    // Grab job_size_id
                                    $job_size_id = $closedProjects[$p]->job_size_id;


                                    // Grab is_rated
                                    $isRated = $closedProjects[$p]->isRated;
                                    // Grab job_orderid
                                    $job_order_id = $closedProjects[$p]->job_order_id;
                                    // Grab cancellation reason
                                    $cancellation_reason =  $closedProjects[$p]->cancellation_reason;
                                    // Grab job order status
                                    $job_order_status_id = $closedProjects[$p]->job_order_status_id;
                                     // Grab assigned to
                                     $assigned_to = $closedProjects[$p]->assigned_to;
                                     // Grab bill status
                                     $bill_status_id = $closedProjects[$p]->bill_status_id;
                                     // Grab project type 
                                    $project_type = $closedProjects[$p]->project_type;

                                     $today = new \DateTime();
                                    // Check if the date is beyond today's date & not have job order. Otherwise mark it as expired.
                                    if(  $job_status != 2 && $today>$d){
                                        $job_status = 3;
                                    }

                                    $tab_link = "&tab=closed";
                  
                                    // Grab date time completion paid status
                                    $date_paid = $closedProjects[$p]->date_time_completion_paid;

                                    // Grab rate_offer 
                                    $rate_offer = $closedProjects[$p]->rate_offer;

                                    // Grab cancelled_by 
                                    $cancelled_by = $closedProjects[$p]->cancelled_by;
                                    // Grab homeowner_id
                                    $homeowner_id = $closedProjects[$p]->homeowner_id;
                                    // Grab order_cancellation_reason
                                    $order_cancellation_reason = $closedProjects[$p]->order_cancellation_reason;

                                    // For billing
                                    $total_price_billed  = $closedProjects[$p]->total_price_billed ?? null ;
                                    $date_time_completion_paid = $closedProjects[$p]->date_time_completion_paid ?? null;
                                    $computedRating = 0;
                                    if($isRated != null){
                                        $computedRating = ($closedProjects[$p]->overall_quality
                                                        + $closedProjects[$p]->professionalism
                                                        + $closedProjects[$p]->reliability
                                                        + $closedProjects[$p]->punctuality)/4.0;
                                    }
                                   

                                    $phone_no = null;

                                    
                                    include dirname(__FILE__)."/".$level.'/components/cards/project-homeowner.php';
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

                                if( count($closedProjects) > 3){
                                ?><!-------------------------------------------------->
                                <!-- HTML ZONE: SHOW MORE PROJECTS -->
                                <!-- Used for pagination / show more ajax (Disabled for now) -->
                                    <!-- <div id="show-more"></div>
                                    <div  class="d-flex mt-4 justify-content-center text-align-center">
                                        <button class="btn btn-lg text-white btn-warning">
                                            SHOW MORE
                                        </button>
                                    </div> -->
                                <?php //--------------- PHP ZONE ------------------------
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">

        </div>
        <!-- <div class="separator"></div>
        <div class="mx-2 mx-lg">
            <h4>Recommended HomeHeroes</h4>
            <p>You have no current projects. Add a project to get recommendations!</p>
        </div> -->
    </div>
    <!-- Footer Links -->
    <?php 
        require_once dirname(__FILE__)."/$level/components/footer.php"; 
    ?>
    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <!-- <script src="../../js/pages/user-projects.js"></script> -->
    <script>
        const summonZeSpinner = () => {
            Swal.fire({
                title: "",
                imageUrl: getDocumentLevel()+"/images/svg/Spinner-1s-200px.svg",
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

        function resetSelection() {
            document.getElementById("category").selectedIndex = 0;
            document.getElementById("categorySelect").selectedIndex = 0;
        }

        function makeSubmenu(value) {
            // console.log(value)
            let cat = document.getElementById("category")
            let subcat = document.getElementById("categorySelect");
            subcat.removeAttribute("disabled");
            subcat.selectedIndex = 0;
            // reset to d-none for all
            // $('.A').each((index, element)=>{
            //     console.log(element)
            // });

            // A BG-1
            if( cat.getAttribute("data-prev") == "0"){
                let className = ".A.BG-"+value
                $(className).each((index, element)=>{
                    element.classList.remove("d-none");
                });
            } else {
                //console.log( cat.getAttribute("data-prev"));
                // disable previous
                let previous = ".A.BG-"+cat.getAttribute("data-prev");
                $(previous).each((index, element)=>{
                    element.classList.add("d-none");
                });

                let current = ".A.BG-"+value
                // enable current
                $(current).each((index, element)=>{
                    element.classList.remove("d-none");
                });
            }

            cat.setAttribute("data-prev", value);

    
        }

        const getTodayDate = () => {
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth() + 1; //January is 0!
            let yyyy = today.getFullYear();

            if (dd < 10) {
            dd = '0' + dd;
            }

            if (mm < 10) {
            mm = '0' + mm;
            } 
                
            today = yyyy + '-' + mm + '-' + dd;

            return today;
        }

// ====================
// Modal Code & Data
// ====================

    const changeAddress = (projectID, homeaddr, homeaddr_id) =>{
        console.log(`Your current home is ${projectID} and you want to change to a new address.`);
        let submitButton = document.getElementById("RU-submit-btn")

        // set to load while ajax fetch
        $("#address-change-content").load("../../components/cards/spinner.php");
        // disable submit button while fetching
        submitButton.setAttribute("disabled", "true");
        submitButton.classList.remove("btn-warning");
        submitButton.classList.add("btn-secondary");

         // CHANGELINKDEVPROD
        // Ajax to get the bearer token
        $.ajaxSetup({cache: false})
        $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
            // console.log(data)
            const parsedSession = JSON.parse(data);
            const token = parsedSession['token'];
            console.log(token);
                // Ajax to get list of addresses
                // Attach JWT token
                $.ajax({
                    type: 'GET',
                    // url : 'https://slim3api.herokuapp.com/homeowner/get-all-addresses', // prod
                    url: 'http://localhost/slim3homeheroapi/public/homeowner/get-all-addresses', // dev
                    contentType: false,
                    processData: false,
                    headers: {
                        "Authorization": `Bearer ${token}`
                    },
                    success : function(response) {
                        console.log(response);
                        // console.log(response.response.allAddress);
                        let obj = {};
                        obj['addr_arr'] = response.response.allAddress;
                        obj['home_address'] = homeaddr;
                        obj['home_id'] = homeaddr_id;

                        // Load the address data into select box
                        $("#address-change-content").load("../../components/forms/change-add-small.php",obj);

                        // enable submit button
                        submitButton.removeAttribute("disabled");
                        submitButton.classList.remove("btn-secondary");
                        submitButton.classList.add("btn-warning");
                    },
                    error: function (response) {
                        console.log(response);
                        // console.log(response.responseJSON);
                        //   console.log(response.responseJSON['message']);
                        if(response.responseJSON['success'] == false){
                            let message = response.responseJSON['response']['message'];
                            let JWTisssue = false;
                            if(message.substring(0, 3) == "JWT"){
                                message = "Token expired or not recognized. Please try logging into your account again.";
                                JWTisssue = true;
                            }
                            Swal.fire({
                            title: 'An error occurred',
                            text: message,
                            icon: 'error'
                            }).then((result) => {
                                // logout if token expired
                                if(JWTisssue == true){
                                    $.ajax({
                                    type : 'GET',
                                    url : '../../auth/signout_action.php',
                                    success : function(response) {
                                        var res = JSON.parse(response);
                                        if(res["status"] == 200){
                                            window.location = '../../';
                                        }
                                    }
                                    });
                                } else {
                                    // Enable buttons & close Modal
                                    $('#modal').modal('hide');
                                }
                            })
                        } else {
                            // Inform user
                            Swal.fire({
                            title: 'An error occurred',
                            text: 'Please try again',
                            icon: 'error'
                            });
                            // Enable buttons & close Modal
                            $('#modal').modal('hide');
                        }
                    }
                });

        });


    };

        // DONE UI/UX, lacks ajax, TODO ADD PROJECT TYPE FOR BLANK JOB DESC, disable close on submit
        const editProject = (projectID, jobPostName, prefSched, jobSize, rateOffer, rateType, description, home_id, address) => {
            summonZeSpinner();
            let data={};
            data['projectID'] = projectID;
            data['job_post_name'] = jobPostName;
            data['preferred_date_time'] = prefSched;
            data['job_size_id'] = jobSize;
            data['rate_offer'] = rateOffer;
            data['rate_type_id'] = rateType;
            data['job_description'] = description;
            data['home_id'] = home_id;
            data['home_address_label'] = address;
            console.log(data);
            loadModal("edit-project", modalTypes,()=>{
                killZeSpinner();
                document.getElementById("date").setAttribute("min", getTodayDate());
            }, getDocumentLevel(),  data);
        }

        // DONE UI/UX, lacks ajax, disable close on submit
        const cancelJobPost = (projectID, jobPostName, project_type_name, address) => {
            let data={};
            data['projectID'] = projectID;
            data['job_post_name'] = jobPostName;
            data['project_type_name'] = project_type_name;
            data['home_address_label'] = address;
            loadModal("cancel-post", modalTypes,()=>{}, getDocumentLevel(),  data);
        }

        // DONE UI/UX, lacks ajax, disable close on submit
        const cancelProject = (projectID, jobPostName, project_type_name, address, assigned_to) => {
            console.log(projectID);
            let data={};
            data['projectID'] = projectID;
            data['job_post_name'] = jobPostName;
            data['project_type_name'] = project_type_name;
            data['home_address_label'] = address;
            data['assigned_to'] = assigned_to;
            loadModal("cancel-project", modalTypes,()=>{}, getDocumentLevel(),  data);
        }

        // DONE UI/UX, lacks ajax, disable close on submit
        const cancelandRepost = (jobOrderID, date, jobPostName, project_type_name, address) => {
            console.log(jobOrderID);
            let data={};
            data['jobOrderID'] = jobOrderID;
            data['old_date_time'] = date;
            data['job_post_name'] = jobPostName;
            data['project_type_name'] = project_type_name;
            data['home_address_label'] = address;
            loadModal("cancel-and-repost", modalTypes,()=>{
                document.getElementById("date").setAttribute("min", getTodayDate());
            }, getDocumentLevel(),  data);
        }

        const reportNoShow = (job_order_id, assigned_to) => {
            // console.log(job_order_id);
            summonZeSpinner();
            let data={};
            data['job_order_id'] = job_order_id;
            data['assigned_to'] = assigned_to;
            loadModal("report-worker", modalTypes,()=>{
                killZeSpinner();
            }, getDocumentLevel(),  data);
        }

        // Disabled for now
        // const reportProject = (projectID) => {
        //     console.log(projectID);
        //     let data={};
        //     data['projectID'] = projectID;
        //     loadModal("report", modalTypes,()=>{}, getDocumentLevel(),  data);
        // }

        const reportBill = (job_order_id, $address, $assigned_to) => {
            console.log(job_order_id);
            summonZeSpinner();
            let data={};
            data['job_order_id'] = job_order_id;
            data['address'] = $address;
            data['assigned_to'] = $assigned_to;
            loadModal("report-bill", modalTypes,()=>{
                killZeSpinner();
            }, getDocumentLevel(),  data);
        }

        const reportProblem = (job_order_id, jobPostName, project_type_name, address, assigned_to) => {
            // console.log(job_order_id);
            summonZeSpinner();
            let data={};
            data['job_order_id'] = job_order_id;
            data['job_post_name'] = jobPostName;
            data['project_type_name'] = project_type_name;
            data['home_address_label'] = address;
            data['assigned_to'] = assigned_to;
            loadModal("report-problem", modalTypes,()=>{
                killZeSpinner();
            }, getDocumentLevel(),  data);
        }

        const completePayment = (jobOrderID) => {
            console.log(jobOrderID);

            summonZeSpinner();

            // NODEPLOYEDPRODLINK
            // Ajax to get the bearer token
            $.ajaxSetup({cache: false})
            $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
                //console.log(data)
                const parsedSession = JSON.parse(data);
                const token = parsedSession['token'];
                console.log(token);

                // No need to append data, since no data is requested for this route

                $.ajax({
                    type: 'POST',
                    // url : '', // prod (no prod link)
                    url: 'http://localhost/slim3homeheroapi/public/homeowner/confirm-payment/'+jobOrderID, // dev
                    contentType: false,
                    processData: false,
                    headers: {
                        "Authorization": `Bearer ${token}`
                    },
                    // data : none, // no data needed
                    success : function(response) {
                        console.log("your response after payment completion is:")
                        console.log(response);

                        // Inform user & refresh page
                        Swal.fire({
                            title: 'Thank You!',
                            text: 'Job Order has paid!',
                            icon: 'success'
                            }).then( result =>{
                                window.location = getDocumentLevel()+'/pages/homeowner/projects.php?tab=closed';
                            });
                            // Enable buttons & close Modal
                            $('#modal').modal('hide');
                    },

                    // START - ON SERVER ERROR OR JWT ERROR
                    error: function (response) {
                        console.log(response);
                        console.log(response.responseJSON);
                        console.log(response.responseJSON['message']);
                        if(response.responseJSON['success'] == false){
                            let message = response.responseJSON['response']['message'];
                            let JWTisssue = false;
                            if(message != undefined && message != null && message != "" && message.substring(0, 3) == "JWT"){
                                message = "Token expired or not recognized. Please try logging into your account again.";
                                JWTisssue = true;
                            }
                            Swal.fire({
                            title: 'An error occurred',
                            text: message,
                            icon: 'error'
                            }).then((result) => {
                                // logout if token expired
                                if(JWTisssue == true){
                                    $.ajax({
                                    type : 'GET',
                                    url : '../../auth/signout_action.php',
                                    success : function(response) {
                                        var res = JSON.parse(response);
                                        if(res["status"] == 200){
                                            window.location = '../../';
                                        }
                                    }
                                    });
                                } else {
                                    // Enable buttons & close Modal
                                    $('#modal').modal('hide');
                                }
                            })
                        } else {
                            
                            // Inform user
                            Swal.fire({
                            title: 'An error occurred',
                            text: 'Please try again',
                            icon: 'error'
                            });
                            // Enable buttons & close Modal
                            $('#modal').modal('hide');
                        }
                    }
                    // END - ON SERVER ERROR OR JWT ERROR

                }); // closing ajax for request to confirm session

            }); // closing for ajax setup get session token

            // Since its only cash-option, no need to create a payment modal to confirm payment
            // loadModal("template", modalTypes,()=>{}, getDocumentLevel(),  data);

        } // closing for complete payment function

        // DONE UI/UX, lacks ajax, disable close on submit
        const reschedule = (projectID, date) => {
            //console.log(projectID);
            let data={};
            data['projectID'] = projectID;
            data['old_date_time'] = date;
            loadModal("reschedule", modalTypes,()=>{
                document.getElementById("date").setAttribute("min", getTodayDate());
            }, getDocumentLevel(),  data);
        }
        
        // DONE UI/UX, lacks ajax, disable close on submit
        const rateProject = (projectID) => {
            console.log(projectID);
            let data={};
            data['projectID'] = projectID;
            loadModal("rate", modalTypes,()=>{
                const er1 = document.getElementById("Ov-error");
                const er2 = document.getElementById("Pr-error");
                const er3 = document.getElementById("Re-error");
                const er4 = document.getElementById("Pu-error");

                // =================================================================
                // OVERALL QUALITY CODE
                // =================================================================
                let ovRatedIndex = -1;
                const overall_qual_feild = document.getElementById("overall_quality_feild");
                const ov = document.getElementsByClassName("Ov");
                const ov_arr = Array.from(ov);
                const resetOverall = () => {
                    ov_arr.forEach(el=>{
                        if( el.classList.contains("star-green")){
                            el.classList.remove("star-green")
                        }
                        el.classList.add("star-gray");
                    });
                }

                resetOverall();

                ov_arr.forEach(el=>{
                    el.addEventListener("mouseover", ()=>{
                        resetOverall();
                        let currentIndex = parseInt(el.getAttribute("data-index"));
                        ov_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= currentIndex){
                                el2.classList.add("star-green");
                            }
                        });

                    });

                    el.addEventListener("mouseleave", ()=>{
                        resetOverall();
                        if (ovRatedIndex != -1){
                            ov_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= ovRatedIndex){
                                el2.classList.add("star-green");
                            }
                        });
                        }
                    });

                    el.addEventListener("click", ()=>{
                        ovRatedIndex = parseInt(el.getAttribute("data-index"));
                        overall_qual_feild.value = ovRatedIndex;
                        if( !er1.classList.contains("d-none")){
                            er1.classList.add("d-none")
                        }
                    });
                });
                
                
                // =================================================================
                // Professionalism CODE
                // =================================================================
                let prRatedIndex = -1;
                const prof_feild = document.getElementById("professionalism_feild");
                const pr = document.getElementsByClassName("Pr");
                const pr_arr = Array.from(pr);
                const resetProf = () => {
                    pr_arr.forEach(el=>{
                        if( el.classList.contains("star-green")){
                            el.classList.remove("star-green")
                        }
                        el.classList.add("star-gray");
                    });
                }

                resetProf();

                pr_arr.forEach(el=>{
                    el.addEventListener("mouseover", ()=>{
                        resetProf();
                        let currentIndex = parseInt(el.getAttribute("data-index"));
                        pr_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= currentIndex){
                                el2.classList.add("star-green");
                            }
                        });

                    });

                    el.addEventListener("mouseleave", ()=>{
                        resetProf();
                        if (prRatedIndex != -1){
                            pr_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= prRatedIndex){
                                el2.classList.add("star-green");
                            }
                        });
                        }
                    });

                    el.addEventListener("click", ()=>{
                        prRatedIndex = parseInt(el.getAttribute("data-index"));
                        prof_feild.value = prRatedIndex;
                        if( !er2.classList.contains("d-none")){
                            er2.classList.add("d-none")
                        }
                    });
                });


                // =================================================================
                // Reliability CODE
                // =================================================================
                let reRatedIndex = -1;
                const rel_feild = document.getElementById("reliability_feild");
                const re = document.getElementsByClassName("Re");
                const re_arr = Array.from(re);
                const resetRel = () => {
                    re_arr.forEach(el=>{
                        if( el.classList.contains("star-green")){
                            el.classList.remove("star-green")
                        }
                        el.classList.add("star-gray");
                    });
                }
                resetRel();

                re_arr.forEach(el=>{
                    el.addEventListener("mouseover", ()=>{
                        resetRel();
                        let currentIndex = parseInt(el.getAttribute("data-index"));
                        re_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= currentIndex){
                                el2.classList.add("star-green");
                            }
                        });

                    });

                    el.addEventListener("mouseleave", ()=>{
                        resetRel();
                        if (reRatedIndex != -1){
                            re_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= reRatedIndex){
                                el2.classList.add("star-green");
                            }
                        });
                        }
                    });

                    el.addEventListener("click", ()=>{
                        reRatedIndex = parseInt(el.getAttribute("data-index"));
                        rel_feild.value = reRatedIndex;
                        if( !er3.classList.contains("d-none")){
                            er3.classList.add("d-none")
                        }
                    });
                    
                });

                // =================================================================
                // Punctuality CODE
                // =================================================================
                let puRatedIndex = -1;
                const punc_feild = document.getElementById("punctuality_feild");
                const pu = document.getElementsByClassName("Pu");
                const pu_arr = Array.from(pu);
                const resetPunc = () => {
                    pu_arr.forEach(el=>{
                        if( el.classList.contains("star-green")){
                            el.classList.remove("star-green")
                        }
                        el.classList.add("star-gray");
                    });
                }
                resetPunc();
                
                pu_arr.forEach(el=>{
                    el.addEventListener("mouseover", ()=>{
                        resetPunc();
                        let currentIndex = parseInt(el.getAttribute("data-index"));
                        pu_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= currentIndex){
                                el2.classList.add("star-green");
                            }
                        });

                    });

                    el.addEventListener("mouseleave", ()=>{
                        resetPunc();
                        if (puRatedIndex != -1){
                            pu_arr.forEach(el2=>{
                            let otherIndex = parseInt(el2.getAttribute("data-index"));
                            if(otherIndex <= puRatedIndex){
                                el2.classList.add("star-green");
                            }
                        });
                        }
                    });

                    el.addEventListener("click", ()=>{
                        puRatedIndex = parseInt(el.getAttribute("data-index"));
                        punc_feild.value = puRatedIndex;
                        if( !er4.classList.contains("d-none")){
                            er4.classList.add("d-none")
                        }
                    });
                });



            }, getDocumentLevel(),  data);
        }





    </script>
</body>
</html>