<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}
$level ="../../";

// CURL STARTS HERE
// NEWLINKDEV
// Curl request to get data to fill projects page

$url = "http://localhost/slim3homeheroapi/public/ticket/get-notifications"; // DEV
// $url = ""; // NO PROD LINK

$headers = array(
    "Authorization: Bearer ".$_SESSION["token_support"],
    'Content-Type: application/json',
);

$role = isset($_SESSION['role']) && is_numeric($_SESSION['role']) ? $_SESSION['role'] : null;
$send_limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? $_GET['limit']: 10 ;
$send_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// 0-ongoing, 1-completed, 2-escalations, 3-transferred
$get_tab = isset($_GET['tab']) && $_GET['tab'] ?  $_GET['tab'] : null;
$current_tab = isset($_GET['tab']) && is_numeric($get_tab) ? ($get_tab > 0 && $get_tab <5 ? $get_tab : 0) : 0;

$viewArr = array("new","read","all");
$post_data = array(
    'email' => $_SESSION["email"],
    'page' => $send_page,
    'limit' => $send_limit,
    // 'view' => $viewArr[$current_tab]
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
        $new_total = 0;
        $read_total = 0;
        $all_total = 0;
        $done_total = 0;
        $new_notifs = [];
        $read_notifs = [];
        $all_notifs = [];
        $done_notifs = [];

    if(is_object($output) && $output->success == true){
        $new_notifs = $output->response->data->new;
        $read_notifs = $output->response->data->read;
        $all_notifs = $output->response->data->all;
        $done_notifs = $output->response->data->done;
        $new_total = $output->response->data->new_total;
        $read_total = $output->response->data->read_total;
        $all_total = $output->response->data->all_total;
        $done_total = $output->response->data->done_total;

    }

// HTML starts here
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/support-table.css">
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
        $current_side_tab = "Messages";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Notifications</h1>
    </div>

    <!-- Tab Header -->
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a onClick="addURLParameter(['tab','page'],['0','1'])" class="nav-item nav-link <?php echo $current_tab==0?"active":"";?>" id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab" aria-controls="nav-new" aria-selected="<?php echo $current_tab==0?"true":"false";?>">New</a>

            <a onClick="addURLParameter(['tab','page'],['1','1'])" class="nav-item nav-link <?php echo $current_tab==1?"active":"";?>" id="nav-read-tab" data-toggle="tab" href="#nav-read" role="tab" aria-controls="nav-read" aria-selected="<?php echo $current_tab==1?"true":"false";?>">Read</a>
            
            <a onClick="addURLParameter(['tab','page'],['2','1'])" class="nav-item nav-link <?php echo $current_tab==2?"active":"";?>" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="<?php echo $current_tab==2?"true":"false";?>">All</a>

            <a onClick="addURLParameter(['tab','page'],['3','1'])" class="nav-item nav-link <?php echo $current_tab==3?"active":"";?>" id="nav-done-tab" data-toggle="tab" href="#nav-done" role="tab" aria-controls="nav-done" aria-selected="<?php echo $current_tab==3?"true":"false";?>">Done</a>

            <input type="hidden" id="r" value="<?php echo $role;?>">
        </div>
    </nav>

    <?php 
        // var_dump($output);
        // var_dump($new_notifs);
        // var_dump($all_notifs);
        // var_dump($output->response->data->all);
    ?>

<!-- ======================================== -->
<!--            SUPERVISORS  VIEW             -->
<!-- ======================================== -->
    <?php 
        if($role!= null &&($role==4||$role==7||$role==6||$role==6)){
            /*
                YO WE NEED A COLUMN HAS TAKEN ACTION AND IT IS INVISIBLE,
                THIS DEACTIVATES THE BUTTON
            */
    ?>
    
        <!-- Tab Header -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade <?php echo $current_tab==0?"show active":"";?>" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
                <?php
                include "$level/components/UX/ticketTableConversion.php";
                    $tableName = "new";
                    $basicSearchId = "allSearch";
                    $tableHeaderLabels = ["EmpID", "From", "Type", "Accept","Read","Decline","Delete", "Notification Message", "Date Sent", "Time Sent"];
                    $buttonClass = array("","","","btn-success","btn-primary","btn-danger","btn-secondary");
                    $buttonName = array("","","","accept","read","decline","delete");
                    $searchCaption = "Search Agent";
                    $ID_row = 11;
                    $statusButton = [4,5,6,7];
                    $modalButtons  = [4,6];
                    $hiddenRows = array(10,11);
                    // $tableRows = [
                    //     ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #53. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #53. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","1"],
                    //     ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #55. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #55. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","2"],
                    //     ["192","Sharma M.", "Override Notif","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #143 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #54. (NOTES: cX badasdsasdadsO)", "May 8, 2022","9:00 AM","3"],
                    //     ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #56. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #56. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","4"]
                    // ];
                    if( count($new_notifs) != 0 ){
                        $tableRows = array_map('convertNotification_PlainDataToTableRow_accept_read_decline_delete', $new_notifs);
                    }
                    include "$level/components/UX/support-table.php";
                    // Reset Values after to prepare for the next iteration
                    $tableName = null;
                    $basicSearchId = null;
                    $tableRows = [];
                ?>
            </div>

            <div class="tab-pane fade <?php echo $current_tab==1?"show active":"";?>" id="nav-read" role="tabpanel" aria-labelledby="nav-read-tab">
                <?php
                    $tableName = "read";
                    $basicSearchId = "readSearch";
                    if(count($read_notifs) != 0){
                        $tableRows = array_map('convertNotification_PlainDataToTableRow_accept_read_decline_delete', $read_notifs, [true]);
                    }
                    include "$level/components/UX/support-table.php";
                    // Reset Values after to prepare for the next iteration
                    $tableName = null;
                    $basicSearchId = null;
                    $tableRows = [];
                ?>
            </div>

            <div class="tab-pane fade <?php echo $current_tab==2?"show active":"";?>" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                <?php
                    $tableName = "all";
                    $basicSearchId = "allSearch";
                    $tableHeaderLabels = ["EmpID", "From", "Type", "Accept","Decline","Delete", "Notification Message", "Date Sent", "Time Sent"];
                    $buttonClass =  array("","","","btn-success","btn-danger","btn-secondary");
                    $buttonName = array("","","","accept","decline","delete");
                    $searchCaption = "Search Agent";
                    $ID_row = 10;
                    $statusButton = [4,5,6];
                    $modalButtons  = [4,5];
                    $hiddenRows = array(9,10);
                    if(count($all_notifs) != 0){
                        $tableRows = array_map('convertNotification_PlainDataToTableRow_accept_decline_delete', $all_notifs);
                    }
                    include "$level/components/UX/support-table.php";

                    // Reset Values after to prepare for the next iteration
                    $tableName = null;
                    $basicSearchId = null;
                    $tableRows = [];
                ?>
            </div>
                   
            <div class="tab-pane fade <?php echo $current_tab==3?"show active":"";?>" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab"> 
                <?php
                    $tableName = "done";
                    $basicSearchId = "doneSearch";
                    $tableHeaderLabels = ["EmpID", "From", "Type", "Delete", "Notification Message", "Date Sent", "Time Sent"];
                    $buttonClass =  array("","","","btn-secondary");
                    $buttonName = array("","","","delete");
                    $searchCaption = "Search Agent";
                    $ID_row = 8;
                    $statusButton = [4];
                    $hasClass = true;
                    $modalButtons  = [];
                    $hiddenRows = array(7,8);            
                    if(count($done_notifs) != 0){
                        $tableRows = array_map('convertNotification_PlainDataToTableRow_delete', $done_notifs);
                    }
                    include "$level/components/UX/support-table.php";
                    // Reset Values after to prepare for the next iteration
                    $tableName = null;
                    $basicSearchId = null;
                    $tableRows = [];
                ?>
            </div> 

        </div>
    <?php 
        }else{
    ?>
    
<!-- ======================================== -->
<!--               AGENTS VIEW                -->
<!-- ======================================== -->
     <!-- Tab Header -->
     <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade <?php echo $current_tab==0?"show active":"";?>" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
            <?php
                // $buttonClass = array("","","","btn-success","btn-primary","btn-danger","btn-secondary");
                // $buttonName = array("","","","accept","read","decline","delete");
                // $searchCaption = "Search Agent";
                // $ID_row = 10;
                // $statusButton = [4,5,6,7];
                // $modalButtons  = [4,6];
                $tableHeaderLabels = ["Sp.ID", "From", "Sent To", "Type", "Read", "Notification Message", "Date Sent", "Time Sent"];
                $tableName = "new";
                $basicSearchId = "allSearch";
                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
                $tableRows = [];
            ?>
        </div>

        <div class="tab-pane fade <?php echo $current_tab==1?"show active":"";?>" id="nav-read" role="tabpanel" aria-labelledby="nav-read-tab">
            <?php
                $tableName = "read";
                $basicSearchId = "activeSearch";
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

         <div class="tab-pane fade <?php echo $current_tab==2?"show active":"";?>" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
            <?php
                // $tableName = "all";
                // $basicSearchId = "bookmarkedSearch";
                // include "$level/components/UX/support-table.php";

                // // Reset Values after to prepare for the next iteration
                // $tableName = null;
                // $basicSearchId = null;
            ?>
        </div> 

         <div class="tab-pane fade <?php echo $current_tab==3?"show active":"";?>" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab"> 
            <?php
                $tableName = "deleted";
                $basicSearchId = "archivedSearch";
                $tableHeaderLabels = ["EmpID", "From", "Type", "Delete", "Notification Message", "Date Sent", "Time Sent"];
                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div> 

    </div>
    <?php 
        }
    ?>

   

<!-- Footer (Pagination & Number of Entries) -->
<div class="mt-auto d-flex flex-column flex-lg-row justify-content-between align-items-center">
        <!-- Number of Entries -->
        <div class="btn-group show-entries-height">
            <button id="btn-entry-select" class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Show <?php echo $send_limit; ?> Entries
            </button>
            <div class="dropdown-menu">
                <button id="btn-select-10" class="dropdown-item" type="button">Show 10 Entries</button>
                <button id="btn-select-20" class="dropdown-item" type="button">Show 20 Entries</button>
                <button id="btn-select-30" class="dropdown-item" type="button">Show 30 Entries</button>
            </div>
        </div>
        <!-- Pagination -->
        <?php 
            // 0-new, 1-read, 2-all, 3-done
            $ultimatotal = [$new_total,$read_total,$all_total,$done_total];
            $paginationBaseTotal = $ultimatotal[$current_tab];
            $numberOfPages =  ceil($paginationBaseTotal/$send_limit);
            // echo var_dump($numberOfPages);
            // echo var_dump($send_page);
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li id="pag-prev"  class="page-item <?php echo $send_page==1?"disabled":"";?>">
                    <a class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php 
                    // $starting_send_page = 0; //$send_page   
                    $over_lim = $numberOfPages-5;
                    $ciel_page =  $over_lim <= 0 ? ($numberOfPages <= 5 ? 1 : $send_page ) : $over_lim+1; //$send_page
                    for($titi = $send_page > $ciel_page ? $ciel_page : $send_page, $pagLimit = 0; $pagLimit < 5 && $titi <= $numberOfPages; $titi++, $pagLimit++){
                ?>
                    <li id="pag-<?php echo $titi;?>" class="page-item <?php echo $send_page==$titi?"active":"";?>"><a class="page-link"><?php echo $titi;?></a></li>
                <?php 
                    }
                ?>
                <li id="pag-next" class="page-item <?php echo $send_page==$numberOfPages?"disabled":"";?>">
                    <a class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Input for Javascript pagination manipulation -->
    <input id="total-new" type="hidden" value=<?php echo $new_total;?>>
    <input id="total-read" type="hidden" value=<?php echo $read_total;?>>
    <input id="total-all" type="hidden" value=<?php echo $all_total;?>>
    <input id="total-done" type="hidden" value=<?php echo $done_total;?>>
    <input id="limit" type="hidden" value=<?php echo $send_limit;?>>
    <input id="page" type="hidden" value=<?php echo $send_page;?>> 
    <input id="tab" type="hidden" value=<?php echo $current_tab;?>>

</main>






    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
<script src="../../js/pages/sup-notifs.js"></script> 
</body>
</html>