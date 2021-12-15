<?php 

session_start();
// if(isset($_SESSION["token"])){
//     header("Location: ./pages/homeowner/home.php");
//     exit();
// }

$level ="../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- Add your custom CSS below -->
<link rel="stylesheet" href="../../css/headers/user.css">
<link rel="stylesheet" href="../../css/landing.css">
<link rel="stylesheet" href="../../css/footer.css">
<!-- Add your custom CSS above -->
</head>
 <body class="container-fluid m-0 p-0 main-container">  
    <!-- Add your Header NavBar here-->
    <?php 
        // require_once dirname(__FILE__)."/$level/components/headers/user.php";
        if(isset($_SESSION["token"])){
            require_once dirname(__FILE__)."/$level/components/headers/ho-signed-in.php";
        } else {
            require_once dirname(__FILE__)."/$level/components/headers/user.php";
        }
    ?>
    <div class="<?php echo $hasHeader ?? ''; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->
<div class="jumbotron container">
<?php

//  add a curl request to get list of data to itereate and render for the page
// NODEPLOYEDPRODLINK
// Make curl for the personal inforation pagge information vv
 $url = "http://localhost/slim3homeheroapi/public/get-service-areas"; // DEV
// $url = ""; // NO DEPLOYED PROD LINK


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

    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    if($output === FALSE){
        echo "cURL Error:" . curl_error($ch);
    }

    curl_close($ch);

    // $output =  json_decode(json_encode($output), true);
    $output =  json_decode($output);

    $cities = [];
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
            // echo var_dump($output);
            $cities = $output->response->All_Areas;
            // echo var_dump($cities[0]);
        }
    }

    
?>
    <div class="pt-5 pb-5">
        <h1>Cities</h1>
        <p class="pt-3">Homehero services 12 different cities with 360 different barangays.</p>
        <img src="../../images/pages/cities/allCities.jpg" class="img-fluid" alt="Responsive image">
        <h2 class="mt-5">List of Cities & Barangays</h2>
        <ul class="mt-3">
            <?php 
                for($cc = 0; $cc < count($cities); $cc++){
            ?>
            <li class="container mt-3">
                 <h3><?php echo htmlentities($cities[$cc]->city);?></h3>
                 <ul class="row">
                    <?php 
                        for($bb = 0; $bb < count($cities[$cc]->barangays); $bb++){
                    ?>
                     <div class="col-12 col-md-6 col-lg-4">
                        <li><?php echo htmlentities($cities[$cc]->barangays[$bb]);?></li>
                     </div>
                     <?php 
                        }
                    ?>
                 </ul>
            </li>
            <?php 
                }
            ?>
        </ul>
    </div>
</div>
      

<?php 
    require_once dirname(__FILE__)."/$level/components/footer.php"; 
?>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script src="./js/pages/landing.js"></script>
</body>
</html>