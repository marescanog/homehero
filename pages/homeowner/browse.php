<?php 

session_start();
if(!isset($_SESSION["token"])){
    header("Location: ../../");
    exit();
}

$level ="../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";

// NODEPLOYEDPRODLINK
// Curl request to get data to fill projects page

$url = "http://localhost/slim3homeheroapi/public/homeowner/get-homeheroes"; // DEV
// $url = ""; // NO PROD LINK

// $headers = array(
//   "Authorization: Bearer ".$_SESSION["token"],
//   'Content-Type: application/json',
// );

// 1. Initialize
$ch = curl_init();

// 2. set options
  // URL to submit to
  curl_setopt($ch, CURLOPT_URL, $url);

  // Return output instead of outputting it
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  // Type of request = GET
  curl_setopt($ch, CURLOPT_HTTPGET, 1);

//   // Set headers for auth
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  // Execute the request and fetch the response. Check for errors
  $output = curl_exec($ch);

  $curl_error_message = null;

  // ERROR HANDLING 
  if($output === FALSE){
      $curl_error_message = curl_error($ch);
  }

  curl_close($ch);

  // $output =  json_decode(json_encode($output), true);
  $output =  json_decode($output);
  
  // Declare variables to be used in this page
//   $ongoingJobPosts = [];
//   $ongoingProjects = [];
//   $closedProjects = [];

//   if(is_object($output) && $output->success == true){
//       $ongoingJobPosts = $output->response->ongoingJobPosts;
//       $ongoingProjects = $output->response->ongoingProjects;
//       $closedProjects = $output->response->closedProjects;
//   }


// HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../../css/headers/header-homeowner.css">
<link rel="stylesheet" href="../../css/footer.css">
<link rel="stylesheet" href="../../css/pages/homeowner/projects.css">
<link rel="stylesheet" href="../../css/pages/homeowner/browse.css">
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
        <div class="h-100">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mx-2 mx-lg">
                    <li class="breadcrumb-item"><a href="#">Browse</a></li>
                    <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data</li> -->
                </ol>
            </nav>
            <div class="mt-0 mb-2 d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mx-2 mx-lg-0 mt-0 mb-2">Browse HomeHeroes</h1>
                </div>
                <div class="sidelink">
                    <!-- <p class="mt-3 mr-3 mr-lg-0 text-danger">CANCEL</p> -->
                </div>
            </div>
        </div>
        <div class="h-100">
        <div class="separator"></div>

<div class="container mb-5">
<?php 
    $workers = null;
    if($output != null){
        $workers = $output->response;
    }
    $rate_types = ["/hr", "/day", "/week", "/project"];
?>

<?php 
    for($wIndx = 0; $wIndx < count($workers); $wIndx++){
        // echo var_dump($workers[$wIndx]);
        $fname = $workers[$wIndx]->worker_info->first_name;
        $lname = $workers[$wIndx]->worker_info->last_name;
        $name = $fname." ".$lname;
        $winitials = substr($fname, 0, 1).substr($lname, 0, 1);

        $default_rate = $workers[$wIndx]->worker_info->default_rate;
        $default_rate_type = $workers[$wIndx]->worker_info->default_rate_type == 0 ? 0 : $rate_types[$workers[$wIndx]->worker_info->default_rate_type - 1];
        $pricing = $default_rate.$default_rate_type;
        
        $rating_average = $workers[$wIndx]->worker_info->rating_average;
        $total_ratings = $workers[$wIndx]->worker_info->total_ratings;

        $completed_jobs = $workers[$wIndx]->worker_info->completed_jobs;

        $city_info = $workers[$wIndx]->city_info == null ? "Cebu" : $workers[$wIndx]->city_info;

        $worker_id = $workers[$wIndx]->worker_info->user_id;

        $skill_list = $workers[$wIndx]->skillset_info == null ? null : $workers[$wIndx]->skillset_info;

        $profile_pic = false;

        if(property_exists($workers[$wIndx], "profile_pic")){
            $profile_pic =  $workers[$wIndx]->profile_pic;
        }
        

        include "$level/components/cards/browse-hero-card.php";
    }
?>


</div>




        </div>
        <!-- <div class="separator"></div>
        <div class="mx-2 mx-lg">
            <h4>Recommended Matches</h4>
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
    <script src="../../js/pages/browse-heroes.js"></script>
</body>
</html>