<?php 

session_start();
if(!isset($_SESSION["token"])){
    header("Location: ../../");
    exit();
}

$level ="../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";

require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/header-homeowner.css">
<link rel="stylesheet" href="../../css/pages/homeowner/homeowner-home.css">
<link rel="stylesheet" href="../../css/footer.css">
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/hot-sneaks/jquery-ui.css" /> -->
<link rel="stylesheet" href="../../css/jquery-ui.css">
<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body class="container-fluid m-0 p-0 main-container">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/ho-signed-in.php"; 
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->
<div class="container-fluid m-0 p-0 vh-100 bgc-lightgray">
<?php
//  add a curl request to get if the user already has a list of addresses and other data.

 // CHANGELINKDEVPROD
// Make curl for the personal inforation pagge information vv
 $url = "http://localhost/slim3homeheroapi/public/search-proj"; // DEV
// $url = "https://slim3api.herokuapp.com/search-proj"; // PROD


// $post_data = array(
//     'query' => 'some stuff',
//     'method' => 'post',
//     'ya' => 'boo'
// );

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

    // Type of request = POST
    // curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);

    // Set headers for auth
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // Adding the post variables to the request
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    if($output === FALSE){
        echo "cURL Error:" . curl_error($ch);
    }

    curl_close($ch);

    // $output =  json_decode(json_encode($output), true);
    $output =  json_decode($output);

    // Populate data from curl output
    if($output !== FALSE && $output !== null && $output !== "" && !empty($output)){
        if($output->success == false){
        ?>
            <div class="title-2-container alert alert-danger alert-dismissible fade show" role="alert">
                <strong>  <?php echo $output->response->status == 500 ? "500 SERVER ERROR": "401 NOT FOUND";?></strong> 
                <?php echo $output->response->message;?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else {
            // // Get Current Saved Skills
            // $savedSkills = (array) $output->response->expertiseList;
            // for($w = 0; $w < count($savedSkills); $w++){
            //     array_push($expertise_list, $savedSkills[$w]->id);
            // }
            // // Get Salary goal
            // $default_rate = $output->response->defaultRate_andType->default_rate;
            // $default_rate_type = $output->response->defaultRate_andType->default_rate_type;
            // // Get NBI information
            // $nbi_information = $output->response->nbi_information;
            // // Get NBI image Files
            // $savedFiles = (array) $output->response->nbi_files;
        }
    }

    
    // // Data for form rendering
    // for($x = 0; $x < count($formSelect_ratetype); $x++){
    //     $ratestring = strtolower($formSelect_ratetype[$x]); 
    //     $ratestring = "per ".$ratestring;
    //     array_push($formSelect_ratetype_option, $ratestring );
    // }

    // $formSelect_certification_type = $_POST["certification_type"] ?? [
    //     "TESDA Certificate", "Diploma", "T.O.R/O.T.R (Collegiate Record)", "Online Certificate", "Other"
    // ];
    
?>
<?php //echo var_dump($output->response->data[0]->id);?>
<?php //echo var_dump($output->response->data[0]->type);?>
    <div class="container h-100 d-flex pb-5">
        <div class="my-auto mx-auto pb-5">
            <h2 class="mb-3">Welcome Back, <?php echo $fistName;?></h2>
            <h2 class="mb-3"><span class="text-warning">Find a Hero</span> to help improve your home</h2>
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Create a new project</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Choose a category that best describes your project</h6>
                    <?php 
                        $jumb_id = "home";
                        $jumb_button_text = "GET STARTED";
                        include "$level/components/forms/user_dash_form.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Discontinued for now -->
<!-- Popular Posting -->
<?php   //require_once dirname(__FILE__)."/$level/components/sections/popular-posting.php"; ?>

<!-- Testimony -->
<?php   //require_once dirname(__FILE__)."/$level/components/sections/featured-heroes.php"; ?>















    




<?php 
    require_once dirname(__FILE__)."/$level/components/footer.php"; 
?>

    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script src="../../js/pages/user-home.js"></script>
</body>
</html>