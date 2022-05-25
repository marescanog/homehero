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


$myrole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$level ="../..";

$showDashCards = ($myrole == null || $myrole < 5);

// No need to get tickets for dash since 
//  Managers & admin will not be handling tickets
// if($showDashCards == true){
    // CURL STARTS HERE
// NEWLINKDEV
// Curl request to get data to fill projects page

$url = "http://localhost/slim3homeheroapi/public/support/ticket-dashboard"; // DEV
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
    
    // Declare variables to be used in this page
    $new_tickets = 0;
    $ongoing_tickets = 0;
    $closed_tickets = 0;
    $anouncements = [];
    $userID = null;
    if(is_object($output) && $output->success == true){
        $new_tickets = $output->response->new_total;
        $ongoing_tickets = $output->response->ongoing_total;
        $closed_tickets = $output->response->resolved_total;
        $anouncements =  $output->response->anouncements;
        $userID = $output->response->dede;
    }

// }



// HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/pages/support/sup-home.css">
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
        $current_side_tab = "Dashboard";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <?php
    //    var_dump($_SESSION);
    //    var_dump($output);
    //    var_dump($new_tickets);
    //    var_dump($ongoing_tickets);
    //    var_dump($closed_tickets);
        //  var_dump($anouncements);
    ?>
    
    <!-- Only Show cards for supervisors and agents -->
    <?php 
        if($showDashCards == true){
    ?>
        <!-- Cards -->
        <div class="row mb-4">
            <div class="col-12 col-lg-3">
                <a href="../support/all-Tickets.php?tab=0" class="button-hover">
                    <div class="card">
                    <div class="card-body purple">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="p-0 cont-value">
                                <?php
                                    // echo isset($_POST['new_ticket_total']) ? $_POST['new_ticket_total'] : '0';
                                    echo $new_tickets;
                                ?>
                            </div>
                            <div class="p-0 cont-icon">
                                <i class="fas fa-ticket-alt card-icon purple-icon"></i>
                            </div>
                        </div>
                        <h5 class="card-title card-header-format">New Tickets</h5>
                    </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-lg-3">
                <a href="../support/my-tickets.php?tab=0" class="button-hover">
                    <div class="card ">
                    <div class="card-body blue">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="p-0 cont-value">
                                <?php
                                    // echo isset($_POST['ongoing_ticket_total']) ? $_POST['ongoing_ticket_total'] : '0';
                                    echo $ongoing_tickets;
                                ?>
                            </div>
                            <div class="p-0 cont-icon">
                                <i class="fas fa-list-alt card-icon blue-icon"></i>
                            </div>
                        </div>
                        <h5  class="card-title card-header-format">Ongoing Tickets</h5>
                    </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-lg-3">
                <a href="../support/my-tickets.php?tab=1" class="button-hover">
                    <div class="card">
                    <div class="card-body green">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="p-0 cont-value">
                                <?php
                                    // echo isset($_POST['resolved_ticket_total']) ? $_POST['resolved_ticket_total'] : '0';
                                    echo $closed_tickets;
                                ?>
                            </div>
                            <div class="p-0 cont-icon">
                                <i class="fas fa-calendar-check card-icon green-icon"></i>
                            </div>
                        </div>
                        <h5 class="card-title card-header-format">Resolved Tickets</h5>
                    </div>
                    </div>
                </a>
            </div>
        </div>
    <?php 
        }
    ?>

    <?php 
        if($showDashCards == false || $myrole == 4){
    ?>
        <div class="mb-3">
            <!-- Add mofal -->
            <button class="btn btn-primary" id="add-anouncement" data-toggle="modal" data-target="#modal">
                Add <?php echo  $myrole == 4 ? "Team " : "";?>Anouncement
            </button>
        </div>
    <?php 
        }
    ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Anouncements</h1> 
    </div>
    <?php 
        // var_dump($myrole);
        // var_dump($_SESSION);
    ?>
    <div>
    <div class="container-fluid pt-4">
        <div class="row">
            <?php 
                if(count($anouncements) != 0) {
                    for($x = 0; $x < count($anouncements); $x++){
            ?>
            <ul>
                <div class="col col-md-10 col-lg-7">
                    <div class="alert alert-secondary" role="alert">
                        <div class="d-flex flex-row justify-content-between">
                            <div style="max-width:90%">
                                <h4 class="alert-heading m-0 p-0">
                                    <?php echo htmlentities($anouncements[$x]->title);?>
                                </h4>
                                <p class="m-0 p-0 font-weight-light font-italic mt-1" style="font-size:'xs' !important">
                                    By: <?php echo htmlentities($anouncements[$x]->author_full_name);?>
                                </p>
                            </div>
                            <?php if($myrole==7||($myrole==4 && $anouncements[$x]->author_id==$userID)){ ?>
                            <button id="btn-view-<?php echo htmlentities($anouncements[$x]->id);?>" type="button" class="btn btn-danger btn-view-post" style="max-width:2.5em;max-height:2.5em">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php } ?>
                        </div>
                        <hr class="m-0 p-0 mt-2 mb-2">
                        <div>
                            <p class="mb-0" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                <?php echo mb_strimwidth(htmlentities($anouncements[$x]->details), 0, 35, "..."); ?>
                            </p>
                            <button type="button" id="btn-delete-<?php echo htmlentities($anouncements[$x]->id);?>" class="mt-2 btn btn-sm btn-primary btn-delete-post">
                                View
                            </button>
                        </div>

                    </div>
                    <p class="unselectable text-white p-0 m-0" style="max-height:1px; overflow: hidden; ">
                        <?php 
                            echo var_dump($anouncements[$x]);
                        ?>
                    </p>
                </div>
            </ul>
            <?php
                    }
                } else {
                    echo 'No Anouncements';
                }
            ?>
        </div>
    </div>
    </div>
    </main>





    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<?php 
    if($showDashCards == true && is_object($output) && $output->success == false){
        $output_status = $output->response->status;
        $output_message = $output->response->message; // "JWT - Ex 1:Expired token"
        ?>
        <!-- <input type="hidden" id="output_status" value="<?php echo $output_status;?>">
        <input type="hideen" id="output_message" value="<?php echo $output_message ?>"> -->
         <script>
             let o_status  = "<?php echo $output_status;?>";
             let o_message = "<?php echo $output_message ?>";
             let o_text = o_message == "JWT - Ex 1:Expired token" ? "Your token has expired. Please login in again." : "Your token is expired or unrecognized. Please login in again."
             let o_title = o_message == "JWT - Ex 1:Expired token" ? 'Expired Token!' : 'Session Expired!'
             Swal.fire({
                title: "Sesion Expired!",
                text: o_text,
                icon: 'info',
                }).then((result) => {
                    $.ajax({
                    type : 'GET',
                    url : '../../auth/signout_action.php',
                    success : function(response) {
                        var res = JSON.parse(response);
                        if(res["status"] == 200){
                            window.location = getDocumentLevel()+'/pages/support/';
                        }
                    }
                    });
                })
         </script>
        <?php
     }
?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
        <script src="../../js/pages/sup-home.js"></script>
</body>
</html>