<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}
if(!isset($_SESSION["role"]) || !(($_SESSION["role"]==5) || ($_SESSION["role"]==6))){
    header("Location: ../support/home.php");
    exit();
}

$level ="../../";
// // CURL STARTS HERE


// // NEWLINKDEV
// // Declare variables to be used in this page
// $codesRes = [];

// $url = "http://localhost/slim3homeheroapi/public/support/get-my-codes"; // DEV
// // $url = ""; // NO PROD LINK

// $headers = array(
//     "Authorization: Bearer ".$_SESSION["token_support"],
//     'Content-Type: application/json',
// );

// $post_data = array(
//     'email' => $_SESSION["email"]
//     // 'email' => 'mdenyys@support.com'
// );

// // 1. Initialize
// $ch = curl_init();

// // 2. set options
//     // URL to submit to
//     curl_setopt($ch, CURLOPT_URL, $url);

//     // Return output instead of outputting it
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//     // Type of request = POST
//     curl_setopt($ch, CURLOPT_POST, 1);

//     // Adding the post variables to the request
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

//     // Set headers for auth
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
//     // Execute the request and fetch the response. Check for errors
//     $output = curl_exec($ch);

//     // Moved inside Modal Body for better display of error messages
//     $mode = "PROD"; // DEV to see verbose error messsages, PROD for production build
//     $curl_error_message = null;

//     // ERROR HANDLING 
//     if($output === FALSE || $output === NULL){
//         $curl_error_message = curl_error($ch);
//     }

//     curl_close($ch);

//     // $output =  json_decode(json_encode($output), true);
//     $output =  json_decode($output);

//     // Set the declare variables (refer at the top)
//     if(is_object($output) && $output != null && $output->success == true){
//         $codesRes = $output->response->codesRes;
//     } else {
//         $err_stat = $output->response->status;
//         $message= $output->response->message;
//     }


// // HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<script src="<?php echo $level;?>/js/components/loadModal.js"></script>
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body class="container-fluid m-0 p-0">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/support.php"; 
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <?php
        $current_side_tab = 'Modify User Priveledges';
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Modify User Priveledges</h1>
    </div>
    <div style="width: 30rem;">
        <p><i>Mange proviledges.</i></p>
    </div>

    <?php 
        // var_dump($codesRes->DEFAULT_3);
        // var_dump($output);
    ?>

    
</main>
    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
<!-- <script src="../../js/pages/sup-permissions.js"></script> -->

</body>
</html>