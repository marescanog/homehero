<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}

$level ="../../";

// CURL STARTS HERE
// NEWLINKDEV
// Curl request to get ticket data based on id
$idRef = isset($_GET["id"]) ? $_GET["id"] : null;

//check if the id is numeric
$idRef = is_numeric($_GET["id"]) ? $idRef : null;

// Declare variables to be used in this page
$base_info = 0;
$history = [];
$detailed_info = [];
$comments = [];
$assignment = [];
$authy = false;
$owny = null;
$err_stat = null;
$message= null;

if($idRef != null){
    $url = "http://localhost/slim3homeheroapi/public/ticket/get-info/".$idRef; // DEV
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
            $data = $output->response->data;
            $base_info =  $data->base_info;
            $history =  $data->history;
            $detailed_info =  $data->detailed_info;
            $comments =  $data->comments;
            $assignment =  $data->assignment_history;
            $authy =  $data->authorization;
            $owny =  $data->ownership;
        } else {
            $err_stat = $output->response->status;
            $message= $output->response->message;
        }
}

// HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/pages/support/support-ticket.css">
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
        $current_side_tab = "My Tickets";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Ticket <?php echo htmlentities($idRef); ?></h1>
    </div>

<!-- DECLARE 404 SERVER OR SERVER ERROR ALERTS HERE -->
    <?php // Test dump area
        // var_dump($_GET);
        // var_dump($output->response);
        // var_dump($idRef);
        // var_dump($_SESSION);
        // var_dump($base_info);
        // var_dump($history);
        // var_dump($detailed_info);
    ?>
    
    <?php 
        if(!isset($idRef) || $idRef == null){
    ?>
        <div class="alert alert-danger" role="alert" style="max-width:35em">
            <h4 class="alert-heading">404 Not found</h4>
            <p>The ticket ID was not found.</p>
        </div>
    <?php
        }    
    ?>

<?php 
if((isset($idRef) && $idRef != null) && $err_stat == null){
?>
    <!-- WHERE THE TICKET ACCEPT STARTS -->
    <?php 
        // Only the agent assigned to a certain role type can accept this ticket when new
        // Supervisors, Admins & superadmins can also accept this ticket when new
        if($authy == 'true' && $base_info->status == '1'){
    ?>
    <div class="alert alert-secondary" role="alert">
    <h4 class="alert-heading">This ticket is not assigned to an agent</h4>
        <p>Click the button below to accept this ticket or go back to the All New Tickets Dashboard.</p>
        <button type="button" class="btn btn-primary" id="tkt-accept-btn">Assign to me</button>
    <hr>
    <div class="flex flex-row">
        <button onclick="history.back()" type="button" id="tkt-goBack-btn" class="btn btn-secondary" >< Go Back</button>
    </div>
    </div>
    <?php 
        }
    ?>


    <!-- WHERE THE MAIN INFORMATION STARTS -->
    <?php
        if($authy || $_SESSION["role"] == 2 || $_SESSION["role"] == 4 || $_SESSION["role"] == 5 || $_SESSION["role"] == 6 ){
    ?>
    <div class="row" style="min-width:100%">
        <div class="col-12 col-lg-6" >
            <div class="card" style="width: 100%;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ticket Info</h5>
                </div>
                <input type="hidden" id="tkt-id" value="<?php echo htmlentities($idRef);?>">
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-4 col-lg-3 border-right ticket-title"> Created by</div>
                            <div class="col-8 col-lg-9"> <?php echo htmlentities($base_info->author_name)?> </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-4 col-lg-3 border-right ticket-title"> Status</div>
                            <div class="col-8 col-lg-9"> <?php echo htmlentities($base_info->status_text)?> </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-4 col-lg-3 border-right ticket-title"> Assigned to</div>
                            <div class="col-8 col-lg-9"> <?php echo $base_info->agent_name == null ?  "None" : htmlentities($base_info->agent_name);?> </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-4 col-lg-3 border-right ticket-title"> Created on</div>
                            <div class="col-8 col-lg-9"> <?php $date = new DateTime($base_info->created_on);
                                    echo $date->format('M j,Y g:i A');?> </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-4 col-lg-3 border-right ticket-title"> Updated on</div>
                            <div class="col-8 col-lg-9"> <?php $date = new DateTime($base_info->last_updated_on);
                                    echo $date->format('M j,Y g:i A');?> </div>
                        </div>
                    </li>
                </ul>
            </div>

    <?php 
// var_dump($nbi_info);
// var_dump($base_info);
?>
<!-- ================================= -->
<!--       SPECIFIC TICKET INFO        -->
<!-- ================================= -->
    <div class="card mt-4" style="width: 100%;">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo htmlentities($base_info->category_text)?> Info</h5>
            </div>
<!-- WORKER REGISTRATION DISPLAY START -->
            <?php 
                if($base_info->issue_id == 1){
                    if(count($detailed_info) > 0){
            ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> Worker Name</div>
                        <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info[0]->worker_name);?> </div>
                    </div>
                </li>
                <?php 
                    if($authy == true){
                ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> NBI ID</div>
                        <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info[0]->clearance_no);?> </div>
                    </div>
                </li>
                <?php 
                    }
                ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> Expiration Date</div>
                        <div class="col-8 col-lg-9"> <?php $date = new DateTime($detailed_info[0]->expiration_date);
                                echo $date->format('M j, Y');?> </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> Status</div>
                        <div class="col-8 col-lg-9"> 
                            <h3>
                                <span class="badge <?php 
                                // 0- No Grade, 1 - Verfified, -1 -Denied
                                    $statIndex = is_numeric($detailed_info[0]->is_verified) == false  ? 3 
                                    : (
                                        $detailed_info[0]->is_verified == -1 ? 2
                                        : ($detailed_info[0]->is_verified >=0 && $detailed_info[0]->is_verified <=3 ?
                                        $detailed_info[0]->is_verified  : 3)
                                    );

                                    $badgeColors = ["badge-secondary","badge-success","badge-danger","badge-secondary"];
                                    echo $badgeColors[$statIndex];?>">
                                    <?php 
                                        $statusArr = ["Pending Review","NBI Approved","NBI Denied","N/A"];
                                        echo htmlentities($statusArr[$statIndex]);
                                    ?> 
                                </span>
                            </h3>
                        </div>
                    </div>
                </li>
            </ul>
            <?php 
                    } else { ?>
                    <div class="p-2">
                        <p class="p-0 m-0 pl-3">No worker registration information available</p>
                    </div>
            <?php    
                    }
                }
            ?>
<!-- WORKER REGISTRATION DISPLAY END -->

<!-- BILLING INFORMATION DISPLAY START -->
            <?php 
                if($base_info->issue_id == 4){
                    if($detailed_info == null){
            ?>
                <div class="p-2">
                    <div class="alert alert-danger" role="alert" style="max-width:35em">
                        <h4 class="alert-heading">404 Not found</h4>
                        <p>Billing information not found! Please contact your administrator.</p>
                    </div>
                </div>
            <?php 
                    }   else {
            ?>
                <ul class="list-group list-group-flush">
                    <?php if(isset($detailed_info->bill_id)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Bill ID</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities(str_pad($detailed_info->bill_id, 5, "0", STR_PAD_LEFT));?> </div>
                            </div>
                        </li>
                    <?php }?>
                        

                    <?php if(isset($detailed_info->bill_id) && $owny != null & $owny != false){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Issue Details:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($base_info->author_Description);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->ho_lname) && isset($detailed_info->ho_fname)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Homeowner Name</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->ho_lname.', '.$detailed_info->ho_fname);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->ho_phone_no)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Homeowner Phone No.</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->ho_phone_no);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->worker_lname) && isset($detailed_info->worker_fname)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Worker Name</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->worker_lname.', '.$detailed_info->worker_fname);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->worker_phone_no)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Worker Phone No.</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->worker_phone_no);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->payment_method)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Payment Method</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->payment_method);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->total_price_billed)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Status</div>
                                <div class="col-8 col-lg-9">P <?php echo htmlentities($detailed_info->total_price_billed);?></div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->status)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title"> Status</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->status);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php 
                        if(isset($detailed_info->bill_status_id) && $detailed_info->bill_status_id == 2){
                    ?>
                        <?php if(isset($detailed_info->is_received_by_worker)){ ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-4 col-lg-3 border-right ticket-title"> Payment Received by Worker</div>
                                    <div class="col-8 col-lg-9"> <?php echo $detailed_info->is_received_by_worker == 1 ? "Yes" : "Pending Receipt from Worker.";?> </div>
                                </div>
                            </li>
                        <?php }?>
                        <?php if(isset($detailed_info->date_time_completion_paid)){ ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-4 col-lg-3 border-right ticket-title"> Bill Payment Date</div>
                                    <div class="col-8 col-lg-9"> <?php $date = new DateTime($detailed_info->date_time_completion_paid);
                                    echo $date->format('M j,Y g:i A');?> </div>
                                </div>
                            </li>
                        <?php }?>
                    <?php 
                        }
                    ?>

                <?php if(isset($detailed_info->bill_created_on)){ ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-4 col-lg-3 border-right ticket-title"> Bill Created On</div>
                            <div class="col-8 col-lg-9"> <?php $date = new DateTime($detailed_info->bill_created_on);
                                    echo $date->format('M j,Y g:i A');?> </div>
                        </div>
                    </li>
                <?php }?>
                </ul>

<!-- Template -->
<!-- <?php if(isset($detailed_info->bill_id)){ ?>
<?php }?> -->

                <?php 
                    if(isset($detailed_info->job_order_id ) && $detailed_info->job_order_id != null){
                ?>
                    <div class="card-header">
                        <h5 class="card-title mb-0">Job Order Details</h5>
                    </div>

                    <?php if(isset($detailed_info->job_time_start)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Time Start:</div>
                                <div class="col-8 col-lg-9"> <?php $date = new DateTime($detailed_info->job_time_start);
                                    echo $date->format('M j,Y g:i A');?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_time_end)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Time End:</div>
                                <div class="col-8 col-lg-9"> <?php $date = new DateTime($detailed_info->job_time_end);
                                    echo $date->format('M j,Y g:i A');?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_created_on)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Accepted On:</div>
                                <div class="col-8 col-lg-9"> <?php $date = new DateTime($detailed_info->job_created_on);
                                    echo $date->format('M j,Y g:i A');?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_order_status)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Status:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->job_order_status);?> </div>
                            </div>
                        </li>
                    <?php }?>
                <?php 
                    }
                ?>

                
                <?php 
                    if(isset($detailed_info->job_post_id) && $detailed_info->job_post_id != null){
                ?>
                    <div class="card-header">
                        <h5 class="card-title mb-0">Job Post Details</h5>
                    </div>

                    <?php if(isset($detailed_info->job_post_name)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Post Title:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->job_post_name);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->post_description)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Description:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->post_description);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_post_status)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Post Status:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->job_post_status);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->post_offer)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Initial offer:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->post_offer);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->post_rate_type)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Offer type:</div>
                                <div class="col-8 col-lg-9">Per <?php echo htmlentities($detailed_info->post_rate_type);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_order_size)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Size:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->job_order_size);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_type)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job SubCategory:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->job_type);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->job_expertise)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Category:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->job_expertise);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->street_name)){ 
                            $snum = isset($detailed_info->street_no) ? $detailed_info->street_no : null;
                            $sname = isset($detailed_info->street_name) ? $detailed_info->street_name : null;
                            $scity = isset($detailed_info->city_name) ? $detailed_info->city_name : null;
                            $sbarangay = isset($detailed_info->barangay_name) ? $detailed_info->barangay_name : null;
                            $scomplete = ($snum == null ? "" : $snum." ")
                                        .($sname == null ? "" : $sname)
                                        .( $scity  == null ? "" :  ", ".$sbarangay )
                                        .( $scity  == null ? "" :  ", ".$scity );
                    ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Job Address:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($scomplete);?> </div>
                            </div>
                        </li>
                    <?php }?>

                    <?php if(isset($detailed_info->post_created_on)){ ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right ticket-title">Posted On:</div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($detailed_info->post_created_on);?> </div>
                            </div>
                        </li>
                    <?php }?>


                <?php 
                    }
                ?>
                
            <?php 
                    }
                }
            ?>

<!-- BILLING INFORMATION DISPLAY END -->
</div>

<!-- ================================= -->
<!--             IMAGES                -->
<!-- ================================= -->
        <?php 
            if($base_info->has_images > 0 && count($detailed_info) > 0 && $authy == true){
        ?>
            <div class="card mt-4" style="width: 100%;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attached Image(s)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <img src="<?php echo $detailed_info[0]->file_path.$detailed_info[0]->file_name;?>" class="img-fluid" alt="Responsive image">
                    </li>
                </ul>
            </div>
            <div>

            </div>
        <?php 
            }
        ?>

<!-- ================================= -->
<!--            ACTIONS UI             -->
<!-- ================================= -->
<?php 

    if($base_info->status == 2){
?>
        <div class="card mt-4">
            <div class="card-header d-flex flex-row justify-content-between">
                <h5 class="card-title mb-0">Actions</h5>
                <?php 
                    if($_SESSION["email"] == $base_info->agent_email){
                ?>
                    <button type="button" class="btn btn-sm btn-danger">Transfer</button>
                <?php 
                    } else {
                ?>
                    <button type="button" class="btn btn-sm btn-danger">Request Override</button>
                <?php
                    }
                ?>
            </div>
            <ul class="list-group list-group-flush">
                <form name="submit-action" id="submit-action" method="post">
<!-- WORKER REGISTRATION DISPLAY START -->
                <?php 
                    if(($_SESSION["email"] == $base_info->agent_email || $owny) && $base_info->issue_id == 1){
                ?>
                    <li class="list-group-item" disabled>
                        <h6>Process Worker Registration</h6>
                        <p class="ml-2 mb-1 font-italic">Select an option</p>
                        <div class="ml-2">
                            <div class="d-flex flex-col flex-sm-row">
                                <button id="btn-worker-reg-approve" type="button" class="mr-2 btn btn-outline-info">Approve</button>
                                <button id="btn-worker-reg-reject" type="button" class="mr-2 btn btn-outline-info">Reject</button>
                                <button id="btn-worker-reg-notify" type="button" class="mr-2 btn btn-outline-info">Notify</button>
                                <button id="btn-worker-reg-comment" type="button" class="mr-2 btn btn-info">Add Note</button>
                            </div>
                        </div>
                    </li>
                <?php 
                    }
                ?>
<!-- WORKER REGISTRATION DISPLAY END -->

<!-- BILLING ISSUES DISPLAY START -->
                <?php 
                    if(($_SESSION["email"] == $base_info->agent_email || $owny) && $base_info->issue_id == 4){
                ?>
                    <li class="list-group-item">
                        <h6>Process Bill Issue</h6>
                        <p class="ml-2 mb-1 font-italic">Select an option</p>
                        <div class="ml-2">
                            <div class="d-flex flex-col flex-sm-row">
                                <button id="btn-bill-edit" type="button" class="mr-2 btn btn-outline-info">Edit Bill</button>
                                <button id="btn-bill-cancel" type="button" class="mr-2 btn btn-outline-info">Cancel Bill</button>
                                <button id="btn-bill-notify" type="button" class="mr-2 btn btn-outline-info">Notify</button>
                                <button id="btn-bill-comment" type="button" class="mr-2 btn btn-info">Add Note</button>
                            </div>
                        </div>
                    </li>
                <?php 
                    }
                ?>
<!-- BILLING ISSUES DISPLAY END -->

<!-- ================================= -->
<!--        FORMS & SUBMISSION         -->
<!-- ================================= -->
                    <li class="list-group-item">
                        <div class="row">
                            <!-- BILLING FORM START -->
                            <?php 
                                if(($_SESSION["email"] == $base_info->agent_email || $owny == true) && $base_info->issue_id == 4){
                            ?>  
                                    <div id="grp-bill-pay" class="input-group mb-3 ml-3 mr-3 hidden">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="payment_method" style="min-width:3em !important;">Payment Method</label>
                                        </div>
                                        <select disabled class="custom-select" id="inpt_bill_payment_method" name="payment_method">
                                            <option <?php echo $detailed_info->payment_method_id==1?"selected":"";?> value="1">Cash</option>
                                            <option <?php echo $detailed_info->payment_method_id==2?"selected":"";?> value="2">Credit</option>
                                            <option <?php echo $detailed_info->payment_method_id==3?"selected":"";?> value="3">Paypal</option>
                                        </select>
                                    </div>
                                    <div id="grp-bill-stat" class="input-group mb-3 ml-3 mr-3 hidden">
                                        <div class="input-group-prepend" >
                                            <label class="input-group-text" for="bill_status" style="min-width:9em !important;">Status</label>
                                        </div>
                                        <select disabled class="custom-select"  id="inpt_bill_status">
                                            <option <?php echo $detailed_info->bill_status_id==1?"selected":"";?> value="1">Pending</option>
                                            <option <?php echo $detailed_info->bill_status_id==2?"selected":"";?> value="2">Paid</option>
                                            <option <?php echo $detailed_info->bill_status_id==3?"selected":"";?> value="3">Cancelled</option>
                                        </select>
                                    </div>
                                    <div id="grp-bill-fee" class="input-group mb-3 ml-3 mr-3 hidden">
                                        <div class="input-group-prepend" >
                                            <span class="input-group-text" style="min-width:4em !important;">Fee Adjustment</span>
                                        </div>
                                        <input disabled name="fee_adjustment" type="number" class="form-control" id="inpt_bill_fee_adjustment" aria-describedby="fee_adjustment" placeholder="(Optional) Enter New Fee">
                                    </div>
                            <?php 
                                }
                            ?>
                            <!-- BILLING FORM END -->
                            <!-- GENERAL APPLICABLE TO ALL -->
                            <div>
                                <input type="hidden" id="form_action" name="form_action" value="<?php 
                                    // echo ($_SESSION["email"] == $base_info->agent_email || $owny == true) ? 4 : 0; 
                                    // Default comment 
                                    /* 
                                        0 For authorized but not owned - 0
                                        1 For worker registration - 4
                                        4 For billing - 4
                                    */
                                    switch($base_info->issue_id){
                                        case "1":
                                            echo 4;
                                        break;
                                        case "4":
                                            echo 4;
                                        break;
                                        default:
                                            echo 0;
                                        break;
                                    }
                                ?>">
                                <input type="hidden" id="form_issue" name="form_issue" value="<?php echo ($_SESSION["email"] == $base_info->agent_email || $owny == true) ?
                                    $base_info->issue_id : (
                                        $authy ? 0 : -1
                                    );
                                ?>">
                                <input type="hidden" id="form_id" name="form_id" value="<?php echo $base_info->id;?>">
                            </div>
                            <div class="col mb-3">
                                <label for="form_comment" class="h6">Comment</label>
                                <textarea name="form_comment" class="form-control" id="comment" placeholder="Add a note or relevant information for this ticket."></textarea>
                            </div>
                        </div>
                        <button id="RU-submit-btn" type="submit" class="btn btn-lg btn-primary font-weight-bold w-100 mb-3">
                            <span id="RU-submit-btn-txt">SUBMIT</span>
                            <div id="RU-submit-btn-load" class="d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </li>
                </form>
            </ul>
        </div>


<?php 
    }
?>

<div class= "mb-5"></div>
    </div>


<!-- ================================= -->
<!--            RIGHT TABS             -->
<!-- ================================= -->
    <div class="col-12 col-lg-6 mt-4 mt-lg-0" >
        <!-- Top Tab Selection -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="false">Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="assignment-tab" data-toggle="tab" href="#assignment" role="tab" aria-controls="assignment" aria-selected="false">Assignment History</a>
            </li>
        </ul>
        <!-- Tab Content -->
        <?php
            // var_dump($history);
        ?>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="card" style="width: 100%;">
                    <?php 
                        if(count($history) > 0){
                        for($chis = 0; $chis < count($history); $chis++){
                    ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right"> <?php $date = new DateTime($history[$chis]->action_date);
                                echo $date->format('M j,Y g:i A');?></div>
                                <div class="col-8 col-lg-9"> <?php echo htmlentities($history[$chis]->system_generated_description)?> </div>
                            </div>
                        </li>
                    <?php } 
                        } else { ?>
                        <div class="row">
                            <div class="col-12 m-2"><p class="pt-2 pb-0 mb-2" style="font-size:0.95rem">No available history</p></div>
                        </div>
                    <?php
                        }
                    ?>
                </div>    
            </div>
            <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                <div class="card" style="width: 100%;">
                    <ul class="list-group list-group-flush">
                        <?php 
                            if(count($comments) > 0){
                                for($ccom = 0; $ccom < count($comments) ; $ccom++){
                        ?>
                                    <li class="list-group-item">
                                        <div class="row">
                                        <div class="col-4 col-lg-3 border-right"> <?php $date = new DateTime($comments[$ccom]->action_date);
                                            echo $date->format('M j,Y g:i A');?></div>
                                            <div class="col-8 col-lg-9">
                                                <div class="row pl-2"> <?php echo htmlentities($comments[$ccom]->agent_notes)?> </div>
                                                <?php 
                                                    $pieces_comment = explode(" ", $comments[$ccom]->system_generated_description);
                                                    $num_comment = 0;
                                                    if(count($pieces_comment) >= 0 ){
                                                        $num_comment = explode("#", $pieces_comment[1]);
                                                        if(count( $num_comment) >= 0){
                                                ?>
                                                    <div class="row pl-2 mt-1 font-italic" style="font-size:0.8rem">By Agent #<?php 

                                                        echo htmlentities( $num_comment[1]);
                                                ?></div>
                                                <?php 
                                                    } }
                                                ?>
                                            </div>

                                        </div>
                                    </li>
                        <?php   }
                            } else { ?>
                                    <li class="list-group-item">
                                        <div class="row ml-1">
                                            No comments were found for this ticket
                                        </div>
                                    </li>
                        <?php }
                        ?> 
                    </ul>
                </div>  
            </div>
            <!-- $assignment -->
            <div class="tab-pane fade" id="assignment" role="tabpanel" aria-labelledby="assignment-tab">
                <div class="card" style="width: 100%;">
                    <ul class="list-group list-group-flush">

                            <?php 
                                if(count($assignment) > 0){
                                    for($casgn = 0; $casgn < count($assignment) ; $casgn++){
                            ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div id="ta-date-<?php echo htmlentities($assignment[$casgn]->id);?>" class="col-3 border-right"><?php $date = new DateTime($assignment[$casgn]->date_assigned);
                                            echo $date->format('M j,Y g:i A');?></div>
                                        <div id="ta-new-<?php echo htmlentities($assignment[$casgn]->id);?>" class="col-6 border-right">
                                            <?php echo htmlentities($assignment[$casgn]->new_agent_name);?> 
                                        </div>
                                        <input id="ta-prev-<?php echo htmlentities($assignment[$casgn]->id);?>" type="hidden" value="<?php echo htmlentities($assignment[$casgn]->previous_agent);?>">
                                        <input id="ta-reason-<?php echo htmlentities($assignment[$casgn]->id);?>" type="hidden" value="<?php echo htmlentities($assignment[$casgn]->reason_text);?>">
                                        <div onClick="popThePopper(<?php echo htmlentities($assignment[$casgn]->id);?>)"  class="col-3 cclicky" style="font-size:0.8rem" data-toggle="modal" data-target="#modal"> see more details </div>
                                    </button>
                                    </div>
                                </li>
                            <?php 
                                    }
                                } else {
                            ?>
                                <li class="list-group-item">
                                    <div class="row ml-1">
                                        No available agent assignment history for this ticket
                                    </div>
                                </li>
                            <?php } ?>
                    </ul>
                </div>  
            </div>
        </div>
    </div>
</div>


<?php 
// if authorized
} else {
    $issue_index = $base_info->issue_id;
    $role_sort = array(0,0,0,1,1,1,1,1,1,1,2,2,2,2,2,3);
    $dept_index = $role_sort[  $issue_index];
    $roles_dept = ["Verification","Customer Service","Technical support", "Escalations"];
    $dept_ext = ["203","433","120","243"];
    ?>
        <div class="alert alert-danger" role="alert" style="max-width:35em">
            <h4 class="alert-heading">401: Unauthorized Access</h4>
            <p><b>You are not authorized to view this information.</b></p>
            <p>If you are on a call with the concerned party inquiring about information, please transfer them to the 
                <b><?php echo   $roles_dept[$dept_index]; ?> Department</b> at <b>extension (<?php echo  $dept_ext[$dept_index]; ?>)</p>
    </b></div>
<?php 
}
?>

<?php
} // if idRef is blank or error
else {
    if($err_stat != null){
        switch($err_stat){
            case 404:
?>
            <div class="alert alert-danger" role="alert" style="max-width:35em">
                <h4 class="alert-heading">404: Ticket was not found</h4>
                <p>Please check your information.</p>
            </div>
<?php
            break;
            default:
?>
            <div class="alert alert-danger" role="alert" style="max-width:35em">
                <h4 class="alert-heading">An Error has Occured.</h4>
                <p>Please reload the page.</p>
            </div>
<?php
            break;
        } // switch bracket
    } // if errstat null
} // if idRef is blank or error
?>

</main>
    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
<script src="../../js/pages/ticket.js"></script>
    <script>

    </script>
    
    <?php 
    // if(is_object($output) && $output->success == false){
    //     $output_status = $output->response->status;
    //     $output_message = $output->response->message; // "JWT - Ex 1:Expired token"
    //     ?>
    //     <!-- <input type="hidden" id="output_status" value="<?php //echo $output_status;?>">
    //     <input type="hideen" id="output_message" value="<?php //echo $output_message ?>"> -->
    //      <script>
    //          let o_status  = "<?php //echo $output_status;?>";
    //          let o_message = "<?php //echo $output_message ?>";
    //          let o_text = o_message == "JWT - Ex 1:Expired token" ? "Your token has expired. Please login in again." : "Your token is expired or unrecognized. Please login in again."
    //          let o_title = o_message == "JWT - Ex 1:Expired token" ? 'Expired Token!' : 'Session Expired!'
    //          Swal.fire({
    //             title: "Sesion Expired!",
    //             text: o_text,
    //             icon: 'info',
    //             }).then((result) => {
    //                 $.ajax({
    //                 type : 'GET',
    //                 url : '../../auth/signout_action.php',
    //                 success : function(response) {
    //                     var res = JSON.parse(response);
    //                     if(res["status"] == 200){
    //                         window.location = getDocumentLevel()+'/pages/support/';
    //                     }
    //                 }
    //                 });
    //             })
    //      </script>
    //     <?php
    //  }
?>
</body>
</html>