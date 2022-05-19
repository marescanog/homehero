<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}
if(!isset($_SESSION["role"]) || ($_SESSION["role"]!=4 && $_SESSION["role"]!=7 && $_SESSION["role"]!=6 && $_SESSION["role"]!=8)){
    header("Location: ../support/home.php");
    exit();
}

// CURL STARTS HERE
$level ="../../";

// NEWLINKDEV
// Declare variables to be used in this page
$codesRes = [];

$url = "http://localhost/slim3homeheroapi/public/support/get-my-codes"; // DEV
// $url = ""; // NO PROD LINK

$headers = array(
    "Authorization: Bearer ".$_SESSION["token_support"],
    'Content-Type: application/json',
);

$post_data = array(
    'email' => $_SESSION["email"]
    // 'email' => 'mdenyys@support.com'
);

// 1. Initialize
$ch = curl_init();

// 2. set options
    // URL to submit to
    curl_setopt($ch, CURLOPT_URL, $url);

    // Return output instead of outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Type of request = POST
    curl_setopt($ch, CURLOPT_POST, 1);

    // Adding the post variables to the request
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

    // Set headers for auth
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    // Moved inside Modal Body for better display of error messages
    $mode = "PROD"; // DEV to see verbose error messsages, PROD for production build
    $curl_error_message = null;

    // ERROR HANDLING 
    if($output === FALSE || $output === NULL){
        $curl_error_message = curl_error($ch);
    }

    curl_close($ch);

    // $output =  json_decode(json_encode($output), true);
    $output =  json_decode($output);

    // Set the declare variables (refer at the top)
    if(is_object($output) && $output != null && $output->success == true){
        $codesRes = $output->response->codesRes;
    } else {
        $err_stat = $output->response->status;
        $message= $output->response->message;
    }


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
        $current_side_tab = "Permissions";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Permissions</h1>
    </div>
    <div style="width: 30rem;">
        <p><i>Manage your request codes and reset permissions. You can generate a new permission code or request a code from your manager.</i></p>
    </div>

    <?php 
        // var_dump($codesRes->DEFAULT_3);
        // var_dump($output);
    ?>

    <div class="card mb-4 ml-2 mt-3" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>My Permission Codes</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Transfer</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="btn-see-transfer-code" class="btn-secondary input-group-text">
                                    <i id="b-3-key" class="fa fa-key"></i>
                                    <i id="b-3-eye" class="far fa-eye d-none"></i>
                                </span>
                            </div>
                            <input readonly id="input-see-transfer-code" type="password" class="form-control" placeholder="No code saved" value="<?php echo isset($codesRes->DEFAULT_3)?$codesRes->DEFAULT_3:"";?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <!-- <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Transfer</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input readonly type="text" class="form-control" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul> -->
    </div>

    <div class="card mb-4 ml-2" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Manager Permission Codes</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Test</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="btn-see-transfer-code-0" class="btn-secondary input-group-text">
                                    <i id="b-3-key-0" class="fa fa-key"></i>
                                    <i id="b-3-eye-0" class="far fa-eye d-none"></i>
                                </span>
                            </div>
                            <input readonly id="input-see-transfer-code-0" type="password" class="form-control" placeholder="No code saved" value="<?php echo isset($codesRes->DEFAULT_1)?$codesRes->DEFAULT_1:"";?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

</main>
    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
<script src="../../js/pages/sup-permissions.js"></script>

</body>
</html>