<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}

// OLD CODE
// if(!isset($_SESSION["role"]) || ($_SESSION["role"]!=4 && $_SESSION["role"]!=7 && $_SESSION["role"]!=6 && $_SESSION["role"]!=8)){
//     header("Location: ../support/home.php");
//     exit();
// }

// MANAGERS DO NOT HAVE ACCESS TO THIS PAGE
if(!isset($_SESSION["role"]) || ($_SESSION["role"]==7)){
    header("Location: ../support/home.php");
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
$url = "http://localhost/slim3homeheroapi/public/support/get-team-details"; // DEV
    
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
    // $output->response->data->agentsList
    $data = null;
    $agentsList = [];
    // $acc = null;
    if($output != null && isset($output->success) && $output->success == true){
        $data = $output->response->data;
        $agentsList = $data->agentsList;
        // $acc = $data->acc;
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
        $current_side_tab = "My Team";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">My Team</h1>
    </div>


    <?php 
        // var_dump($output->response->data->agentsList);
        // var_dump(count($agentsList)==0);
    ?>
        <?php 
            if(count($agentsList)==0){
        ?>
            <div class="card mb-4 ml-2" style="width: 30rem;">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="ticket-title">You have no active agents currently listed. Contact your manager to have agents assigned to your team.</div>
                </li>
                </ul>
            </div>
        <?php 
            } else {
        ?>
     
                <?php 
                    $rolesArr = array("Verification","Customer Support","Customer Support", "Supervisor", "Admin", "Super Admin", "Manager");
                    for($x=0; $x<count($agentsList); $x++){
                ?>
                    <div class="card mb-4 ml-2" style="width: 30rem;">
                    <div class="card-header text-muted">
                        <b> E.ID  <?php echo str_pad($agentsList[$x]->id, 3, "0", STR_PAD_LEFT);?>

                        </b>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item mb-3">
                            <!-- <div class="row align-items-center">
                                <div class="col-4 col-lg-4 border-right ticket-title supList">
                                    Employee ID
                                </div>
                                <div class="col-8 col-lg-8 align-items-center"> 
                                    <?php //echo str_pad($agentsList[$x]->id, 3, "0", STR_PAD_LEFT);?>
                                </div>    
                          
                            </div>    
                            <hr> -->
                            <div class="row align-items-center">
                                <div class="col-4 col-lg-4 border-right ticket-title supList">
                                    Full Name
                                </div>
                                <div class="col-8 col-lg-8 align-items-center"> 
                                    <?php echo htmlentities($agentsList[$x]->full_name);?>
                                </div>    
                            </div> 
                            <hr> 
                            <div class="row align-items-center">
                                <div class="col-4 col-lg-4 border-right ticket-title supList">
                                    Email
                                </div>
                                <div class="col-8 col-lg-8 align-items-center"> 
                                    <?php echo htmlentities($agentsList[$x]->email);?>
                                </div>    
                            </div>   
                            <hr>
                            <div class="row align-items-center">
                                <div class="col-4 col-lg-4 border-right ticket-title supList">
                                    Phone Number
                                </div>
                                <div class="col-8 col-lg-8 align-items-center"> 
                                    <?php echo htmlentities($agentsList[$x]->phone_no);?>
                                </div>    
                            </div>   
                            <hr>
                            <div class="row align-items-center">
                                <div class="col-4 col-lg-4 border-right ticket-title supList">
                                    Role
                                </div>
                                <div class="col-8 col-lg-8 align-items-center"> 
                                    <?php echo htmlentities($rolesArr[$agentsList[$x]->role_type-1]);?>
                                </div>    
                            </div>   
                        </li>
                    </ul>
                    </div>
                <?php
                    }
                ?>
      
        <?php 
            }
        ?>


    
























</main>






    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
</body>
</html>