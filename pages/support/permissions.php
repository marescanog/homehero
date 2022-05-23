<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}

// ONLY SUPERVISORS & MANAGERS HAVE ACCESS TO THIS PAGE
if(!isset($_SESSION["role"]) || ($_SESSION["role"]!=4 && $_SESSION["role"]!=7)){
    header("Location: ../support/home.php");
    exit();
}

// Disable Admin Account - To speed up completion of project (Module is Extra work & system is still functional without these additional features)
if(!isset($_SESSION["role"]) || ($_SESSION["role"]==6 && $_SESSION["role"]==5)){
    header("Location: ../../");
    exit();
}


$escalationsRole = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

// CURL STARTS HERE
$level ="../../";

// NEWLINKDEV
// Declare variables to be used in this page
$codesRes = [];
$managerRes = [];

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
        $managerRes = $output->response->managerRes;
    } else {
        // $err_stat = $output->response->status;
        // $message= $output->response->message;
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

    <?php 
        // var_dump($codesRes->DEFAULT_3);
        // var_dump($output);
    ?>

<!-- ====================================== -->
<!-- SUPERVISORS PERMISSION CODE GENERATORS -->
<!-- ====================================== -->
<?php 
    if($escalationsRole == 4){
        // var_dump($codesRes);
?>

    <div style="width: 30rem;">
        <p><i>Manage your request codes and reset permissions. You can generate a new permission code or request a code from your manager.</i></p>
    </div>

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
            <b>Manager Authorization Codes</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">
                        External Transfer Request
                    </div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="btn-see-transfer-code_b" class="btn-secondary input-group-text">
                                    <i id="b-3-key_b" class="fa fa-key"></i>
                                    <i id="b-3-eye_b" class="far fa-eye d-none"></i>
                                </span>
                            </div>
                            <input readonly id="input-see-transfer-code_b" type="password" class="form-control" placeholder="No code saved" value="<?php echo isset($codesRes->DEFAULT_1)?$codesRes->DEFAULT_1:"";?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer_b" class="btn btn-sm btn-outline-secondary" type="button">Request New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
<?php 
    }
?>


<!-- ====================================== -->
<!-- MANAGERS PERMISSION CODE GENERATORS -->
<!-- ====================================== -->
<?php 
    if($escalationsRole == 7){
?>
    <div style="width: 30rem;">
        <p><i>Manage your request codes and reset permissions. You can generate a new permission code to approve the actions of supervisors.</i></p>
    </div>  

    <?php 
        $list_of_sup = isset($managerRes->list_of_sup) ? $managerRes->list_of_sup : null;
        $external_transfer_codes = isset($managerRes->extTransfer_1) ? $managerRes->extTransfer_1 : null;
        $external_reassign_codes = isset($managerRes->extReassign_2) ? $managerRes->extReassign_2 : null;
        $transfer_codes = isset($managerRes->transfer_3) ? $managerRes->transfer_3 : null;
        // "list_of_sup" // "extTransfer_1" // "extReassign_2" //"transfer_3"
        // var_dump($managerRes->transfer_3);
    ?>

    <!-- For Managers, it is an approval code based on the supervisor ID that they can only edit and the supervisor cannot edit. This is for tracking purposes -->
    <div class="card mb-4 ml-2" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Approve External Transfer</b>
        </div>
        <ul class="list-group list-group-flush">
            <!-- ----------------------------------------------------- -->
            <?php 
                if(count($list_of_sup) == 0){ // no supervisors available
            ?>
            <!-- ----------------------------------------------------- -->
                <li class="list-group-item">
                    <div class="ticket-title">There are no active supervisors currently listed. Contact the admin to addd supervisor accounts.</div>
                </li>
            <!-- ----------------------------------------------------- -->
            <?php 
                } else {  // list f supervisors available
            ?>
            <!-- ----------------------------------------------------- -->
            <!-- ----------------------------------------------------- -->
                <?php 
                    for($eee = 0; $eee < count($list_of_sup); $eee++){
                        $supObj = $list_of_sup[$eee];
                ?>
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-4 col-lg-4 border-right ticket-title supList">
                                <?php 
                                    $sup_extT_ID = $supObj->sup_id;
                                    echo "E.ID ".str_pad($sup_extT_ID, 3, "0", STR_PAD_LEFT)." - ".$supObj->full_name;
                                ?>
                            </div>
                            <div class="col-8 col-lg-8 align-items-center"> 
                                <?php 
                                    // Extract the data
                                    $ids = array_column($external_transfer_codes, 'sup_id');
                                    $found_key = array_search($supObj->sup_id, $ids);
                                    $appr_obj = $found_key === false ? null : $external_transfer_codes[$found_key];
                                    $appr_code = $appr_obj == null ? "" : $appr_obj->override_code;
                                    // $permission_id = $appr_obj == null ? null : $appr_obj->permissions_id;
                                    // $permissions_owner_id = $appr_obj == null ? null : $appr_obj->permissions_owner_id;
                                    $is_void = $appr_obj == null ? null : $appr_obj->is_void;
                                    $owner_can_change = $appr_obj == null ? null : $appr_obj->owner_can_change;
                                    // var_dump($external_transfer_codes);
                                    // var_dump($ids);
                                    // var_dump($found_key);
                                    // var_dump($appr_code);
                                    // var_dump($appr_obj);
                                    // var_dump($owner_can_change);
                                ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span id="<?php echo "btn_see-".$sup_extT_ID.'-1';?>" class="btn-secondary input-group-text">
                                            <i id="<?php echo "b_key-".$sup_extT_ID.'-1';?>" class="fa fa-key"></i>
                                            <i id="<?php echo "b_eye-".$sup_extT_ID.'-1';?>" class="far fa-eye d-none"></i>
                                        </span>
                                    </div>
                                    <input readonly id="input-<?php echo $sup_extT_ID; ?>-1" type="password" class="form-control" placeholder="No code saved" 
                                    value="<?php echo $appr_code;?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                                    <div class="input-group-append">
                                        <button id="<?php echo "btn_gen-".$sup_extT_ID.'-1';?>" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                                    </div>
                                    <!-- Voiding codes on hold for now -->
                                    <!-- <div class="input-group-append ml-3">
                                        <button id="<?php //echo "btn_del-".$sup_extT_ID.'-1';?>" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-danger" type="button"> X </button>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </li>
                <?php 
                    }
                ?>
            <!-- ----------------------------------------------------- -->
            <!-- ----------------------------------------------------- -->
            <?php 
                }
            ?>
        </ul>
    </div>

    <!-- <div class="card mb-4 ml-2" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Approve Override</b>
        </div>
        <ul class="list-group list-group-flush"> -->
            <!-- ----------------------------------------------------- -->
            <?php 
                // if(count($list_of_sup) == 0){ // no supervisors available
            ?>
            <!-- ----------------------------------------------------- -->
                <!-- <li class="list-group-item">
                    <div class="ticket-title">There are no active supervisors currently listed. Contact the admin to addd supervisor accounts.</div>
                </li> -->
            <!-- ----------------------------------------------------- -->
            <?php 
                // } else {  // list f supervisors available
            ?>
            <!-- ----------------------------------------------------- -->
            <!-- ----------------------------------------------------- -->
                <?php 
                    // for($eee = 0; $eee < count($list_of_sup); $eee++){
                ?>
                    <!-- <li class="list-group-item">
                        <div class="col-4 col-lg-4 border-right ticket-title">
                            <?php 
                                echo "E.ID ".str_pad($supObj->sup_id, 3, "0", STR_PAD_LEFT)." - ".$supObj->full_name;
                            ?>
                        </div>
                        <div class="col-8 col-lg-8 align-items-center">

                        </div>
                    </li> -->
                <?php 
                    // }
                ?>
            <!-- ----------------------------------------------------- -->
            <!-- ----------------------------------------------------- -->
            <?php 
                // }
            ?>
        <!-- </ul>
    </div> -->






        <!-- Template -->
        <!-- <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Supervisor A</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="btn-see-man-extrernal-transfer-generate" class="btn-secondary input-group-text">
                                    <i id="b-3-key-man-extrernal" class="fa fa-key"></i>
                                    <i id="b-3-eye-man-extrernal" class="far fa-eye d-none"></i>
                                </span>
                            </div>
                            <input readonly id="input-see-man-extrernal-transfer-generate" type="password" class="form-control" placeholder="No code saved" value="<?php // echo isset($codesRes->DEFAULT_1)?$codesRes->DEFAULT_1:"";?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                            <div class="input-group-append">
                                <button id="btn_gen_man_extrernal_transfer_generate" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul> -->


    <!-- <div class="card mb-4 ml-2" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Approve Override</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Supervisor B</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="btn-see-man-approve-override" class="btn-secondary input-group-text">
                                    <i id="b-3-key-man-approve-override" class="fa fa-key"></i>
                                    <i id="b-3-eye-man-approve-override" class="far fa-eye d-none"></i>
                                </span>
                            </div>
                            <input readonly id="input-see-man-approve-override" type="password" class="form-control" placeholder="No code saved" value="<?php //echo isset($codesRes->DEFAULT_1)?$codesRes->DEFAULT_1:"";?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                            <div class="input-group-append">
                                <button id="btn_gen_man_approve_override" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div> -->
<?php 
    }
?>


<!-- ====================================== -->
<!-- ADMIN PERMISSION CODE GENERATORS -->
<!-- ====================================== -->
<?php 
    if($escalationsRole == 6 || $escalationsRole == 5){
?>
    <!-- <div style="width: 30rem;">
        <p><i>Manage your request codes and reset permissions. You can generate a new permission code or request a code from your manager.</i></p>
    </div>
    <div class="card mb-4 ml-2" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Admin Permission Codes</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">General Override Approval</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span id="btn-see-transfer-code-0" class="btn-secondary input-group-text">
                                    <i id="b-3-key-0" class="fa fa-key"></i>
                                    <i id="b-3-eye-0" class="far fa-eye d-none"></i>
                                </span>
                            </div>
                            <input readonly id="input-see-transfer-code-0" type="password" class="form-control" placeholder="No code saved" value="<?php // echo isset($codesRes->DEFAULT_1)?$codesRes->DEFAULT_1:"";?>" aria-label="Transfer_code" aria-describedby="Transfer_code">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div> -->
<?php 
    }
?>

















<!-- TEMPLATE FOR MANAGER IF ADDING A NEW PERMISSION -->
<div class="card mb-4 ml-2 d-none" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Approve External Transfer</b>
        </div>
        <ul class="list-group list-group-flush">
            <!-- ----------------------------------------------------- -->
            <?php 
                //if(count($list_of_sup) == 0){ // no supervisors available
            ?>
            <!-- ----------------------------------------------------- -->
                <li class="list-group-item">
                    <div class="ticket-title">There are no active supervisors currently listed.</div>
                </li>
            <!-- ----------------------------------------------------- -->
            <?php 
                //} else {  // list f supervisors available
            ?>
            <!-- ----------------------------------------------------- -->
            <!-- ----------------------------------------------------- -->
                <?php 
                   // for($eee = 0; $eee < count($list_of_sup); $eee++){
                ?>

                <?php 
                  //  }
                ?>
            <!-- ----------------------------------------------------- -->
            <!-- ----------------------------------------------------- -->
            <?php 
              //  }
            ?>
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




















