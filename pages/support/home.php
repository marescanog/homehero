<?php 

session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}

$level ="../..";

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

    if(is_object($output) && $output->success == true){
        $new_tickets = $output->response->new_total;
        $ongoing_tickets = $output->response->ongoing_total;
        $closed_tickets = $output->response->resolved_total;
    }




// HTML STARTS HERE
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/pages/support/sup-home.css">
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
    ?>
        <!-- Cards -->
        <div class="row mb-4">
            <div class="col-12 col-lg-3">
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
            </div>

            <div class="col-12 col-lg-3">
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
                    <h5 class="card-title card-header-format">Ongoing Tickets</h5>
                </div>
                </div>
            </div>

            <div class="col-12 col-lg-3">
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
            </div>
        </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Anouncements</h1> 
    </div>
    <div>
        <?php 
            if(isset($_POST['anouncements'])) {
                for($x = 0; $x < count($_POST['anouncements']); $x++){
        ?>
        <ul>
            <?php 
                echo "<li>".$_POST['anouncements'][$x]."</li>";
            ?>
        </ul>
        <?php
                }
            } else {
                echo 'No Anouncements';
            }
        ?>
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