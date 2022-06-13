<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}

// Disable Admin Account - To speed up completion of project (Module is Extra work & system is still functional without these additional features)
if(!isset($_SESSION["role"]) || ($_SESSION["role"]==6 && $_SESSION["role"]==5)){
    header("Location: ../../");
    exit();
}

$role_acc = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

$level ="../../";

$supportToken = isset($_SESSION['token_support']) ? $_SESSION['token_support'] : null;
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

// Do a cURL request to get the necessary info
// // NOLINKDEVPROD
$url = "http://localhost/slim3homeheroapi/public/support/get-account-details"; // DEV
    
$headers = array(
    "Authorization: Bearer ".$supportToken,
    'Content-Type: application/json',
);

$post_data = array(
    'email' => $email
);

// 1. Initialize
$ch = curl_init();

// 2. set options
    // URL to submit to
    curl_setopt($ch, CURLOPT_URL, $url);

    // Return output instead of outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Type of request = POST
    curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPGET, 1);

    // Set headers for auth
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Adding the post variables to the request
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    // // $output =  json_decode(json_encode($output), true);
    $output =  json_decode($output);

    if($output === FALSE){
        $curlResult =  curl_error($ch);
        $isValid = false;
        $status = 500;
        $retVal = "There was a problem with the curl request.";
    } 

    $data = null;
    $specific = null;
    $acc = null;
    if($output != null && isset($output->success) && $output->success == true){
        $data = $output->response->data;
        $specific = $data->specific;
        $acc = $data->acc;
    }
    curl_close($ch);


require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
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
        $current_side_tab = "Account Settings";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Account Settings</h1>
    </div>

    <!-- ACCOUNT SETTINGS -->
    <div class="separator"></div>

    <?php 
        // var_dump($specific);
        // var_dump($acc);
    ?>


    <div class="container pt-3 ">
        <div class="row">
            <div class="separator"></div>
            <div class="container col coll-lg-8 mb-5">
                <div class="card w-100">
                    <div class="card-header" style="background-color: #FFF9E6">
                        <b>Account Details</b>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <b>Name:</b>
                            <?php  echo htmlentities($acc->acc_full_name);?>
                        </li>
                        <li class="list-group-item">
                            <b>Work Email:</b>
                            <?php  echo htmlentities($acc->email);?>
                        </li>
                        <li class="list-group-item">
                            <b>Joined On:</b>
                            <?php 
                                $date_join=date_create($acc->date_joined);
                                echo date_format($date_join,"M d, Y");
                            ?>
                        </li>
                        <?php 
                            switch($role_acc){
                                case 7: // Manager
                        ?>
                        <!-- ---------------------------------------------------------- -->
                            <li class="list-group-item">
                                <b>Total Staff in Operations:</b>
                                <?php  echo htmlentities($specific->{'total'});?>
                            </li>
                            <li class="list-group-item">
                                <b>Total Supervisors:</b>
                                <?php  echo htmlentities($specific->{'Supervisor'});?>
                            </li>
                            <li class="list-group-item">
                                <b>Total Agents:</b>
                                <?php  echo htmlentities($specific->{'Customer Support'}+$specific->{'Verification'});?>
                            </li>
                            <li class="list-group-item">
                                <b>Total Customer Support Agents:</b>
                                <?php  echo htmlentities($specific->{'Customer Support'});?>
                            </li>
                            <li class="list-group-item">
                                <b>Total Verification Support Agents:</b>
                                <?php  echo htmlentities($specific->{'Verification'});?>
                            </li>
                        <!-- ---------------------------------------------------------- -->
                        <?php
                                    break;
                                case 4: // Supervisor
                        ?>
                        <!-- ---------------------------------------------------------- -->
                            <li class="list-group-item">
                                <b>Manager:</b>
                                <?php  echo htmlentities($acc->sup_full_name);?>
                            </li>
                            </ul>
                            <div class="card-header" style="background-color: #FFF9E6">
                                <b>Summary</b>
                            </div>
                            <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <b>Total Team Members:</b>
                                <?php  echo htmlentities($specific->{'Customer Support'}+$specific->{'Verification'});?>
                            </li>
                            <li class="list-group-item">
                                <b>Total Customer Support Members in Team:</b>
                                <?php  echo htmlentities($specific->{'Customer Support'});?>
                            </li>
                            <li class="list-group-item">
                                <b>Total Verification Support Members in Team:</b>
                                <?php  echo htmlentities($specific->{'Verification'});?>
                            </li>
                        <!-- ---------------------------------------------------------- -->
                        <?php
                                    break;
                                    ?>
                        <?php
                                default: // Agent
                        ?>
                        <!-- ---------------------------------------------------------- -->
                                <li class="list-group-item">
                                    <b>Your Supervisor:</b>
                                    <?php  echo htmlentities($acc->sup_full_name);?>
                                </li>
                            </ul>
                            <div class="card-header" style="background-color: #FFF9E6">
                                <b>Summary</b>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Total Tickets Processed:</b>
                                    <?php  echo htmlentities($specific->total);?>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Tickets Closed:</b>
                                    <?php  echo htmlentities($specific->Closed);?>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Tickets Resolved:</b>
                                    <?php  echo htmlentities($specific->Resolved);?>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Current Ongoing Tickets:</b>
                                    <?php  echo htmlentities($specific->Ongoing);?>
                                </li>
                        <!-- ---------------------------------------------------------- -->
                        <?php
                                    break;
                            }
                        ?>
                    </ul>
                </div>                          
            </div>
            <div class="col-lg-4"></div>
            
        </div>
        <div class="row">
            <div class="separator"></div>
        </div>
        <div class="row">
            <div class="container col coll-lg-8 mb-5">
                <h5 class="mb-3">Main Settings</h5>
                <h6 class="pt-2">Change password</h6>
                <form id="profile-change-password" class="pt-2 card-width-profile pl-3">
                    <div class="form-group pt-3">
                        <label for="current_pass">Current Password</label>
                        <input type="password" class="form-control" id="current_pass" placeholder="Password" name="current_pass">
                    </div>
                    <div class="form-group">
                        <label for="new_pass">New Password</label>
                        <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="Password" minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="confirm_pass">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" placeholder="Password">
                    </div>
                    <button id="CPs-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg">
                            <span id="CPs-submit-btn-txt">CHANGE</span>
                            <div id="CPs-submit-btn-load" class="d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                            </div>
                    </button>
                </form>
            </div>
            <div class="col-lg-4 pb-5"></div>
        </div>
    </div>

</main>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
<script src="../../js/pages/sup-account-settings.js"></script>
    <script>

    </script>
</body>
</html>