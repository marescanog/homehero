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
$history = 0;
$nbi_info = 0;

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
        if($output === FALSE){
            $curl_error_message = curl_error($ch);
        }
    
        curl_close($ch);
    
        // $output =  json_decode(json_encode($output), true);
        $output =  json_decode($output);
    
        // Set the declare variables (refer at the top)
        if(is_object($output) && $output->success == true){
            $base_info = $output->response->base_info;
            $history = $output->response->history;
            $nbi_info = $output->response->nbi_info;
        }
}

// HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/pages/support/support-ticket.css">
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
    
<!-- WHERE THE MAIN INFORMATION STARTS -->
<div class="row" style="min-width:100%">
    <div class="col-12 col-lg-6" >
        <div class="card" style="width: 100%;">
            <div class="card-header">
                <h5 class="card-title mb-0">Ticket Info</h5>
            </div>
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
                        <div class="col-8 col-lg-9"> <?php echo htmlentities($base_info->agent_name)?> </div>
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

        <div class="card mt-4" style="width: 100%;">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo htmlentities($base_info->category_text)?> Info</h5>
            </div>
            <!-- WORKER REGISTRATION DISPLAY -->
            <?php 
                if($base_info->issue_id == 1){
                    if(count($nbi_info) > 0){
            ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> Worker Name</div>
                        <div class="col-8 col-lg-9"> <?php echo htmlentities($nbi_info[0]->worker_name);?> </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> NBI ID</div>
                        <div class="col-8 col-lg-9"> <?php echo htmlentities($nbi_info[0]->clearance_no);?> </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> Expiration Date</div>
                        <div class="col-8 col-lg-9"> <?php $date = new DateTime($nbi_info[0]->expiration_date);
                                echo $date->format('M j, Y');?> </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4 col-lg-3 border-right ticket-title"> Status</div>
                        <div class="col-8 col-lg-9"> <?php echo htmlentities($nbi_info[0]->is_verified);?> </div>
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
        </div>

        <?php 
            if($base_info->has_images > 0 && count($nbi_info) > 0){
        ?>
            <div class="card mt-4" style="width: 100%;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attached Image(s)</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <img src="<?php echo $nbi_info[0]->file_path.$nbi_info[0]->file_name;?>" class="img-fluid" alt="Responsive image">
                    </li>
                </ul>
            </div>
            <div>

            </div>
        <?php 
            }
        ?>

        <div class= "mb-5"></div>
    </div>



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
                        <!-- <?php 
                            for($chis = 0; $chis < 3; $chis++){
                        ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-4 col-lg-3 border-right"> adsadsad</div>
                                    <div class="col-8 col-lg-9"> adsadsd </div>
                                </div>
                            </li>
                        <?php } ?> -->
                    </ul>
                </div>  
            </div>
            <div class="tab-pane fade" id="assignment" role="tabpanel" aria-labelledby="assignment-tab">
                <div class="card" style="width: 100%;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 col-lg-3 border-right"> adsadsad</div>
                                <div class="col-8 col-lg-9"> adsadsd </div>
                            </div>
                        </li>
                    </ul>
                </div>  
            </div>
        </div>
    </div>
</div>
</main>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
</body>
</html>