<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}
$level ="../../";

// // CURL STARTS HERE
// // NEWLINKDEV
// // Curl request to get data to fill projects page

// $url = "http://localhost/slim3homeheroapi/public/ticket/all"; // DEV
// // $url = ""; // NO PROD LINK

// $headers = array(
//     "Authorization: Bearer ".$_SESSION["token_support"],
//     'Content-Type: application/json',
// );

$send_limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? $_GET['limit']: 10 ;
$send_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// 0-ongoing, 1-completed, 2-escalations, 3-transferred
$get_tab = isset($_GET['tab']) && $_GET['tab'] ?  $_GET['tab'] : null;
$current_tab = isset($_GET['tab']) && is_numeric($get_tab) ? ($get_tab > 0 && $get_tab <5 ? $get_tab : 0) : 0;

// $post_data = array(
//     'email' => $_SESSION["email"],
//     'page' => $send_page,
//     'limit' => $send_limit
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
//     if($output === FALSE){
//         $curl_error_message = curl_error($ch);
//     }

//     curl_close($ch);

//     // $output =  json_decode(json_encode($output), true);
//     $output =  json_decode($output);
    
//     // Declare variables to be used in this page
//     $new_total = 0;
//     $ongoing_total = 0;
//     $completed_total = 0;
//     $escalated_total = 0;
//     $transferred_total = 0;
//     $new_tickets = [];
//     $ongoing_tickets = [];
//     $completed_tickets = [];
//     $escalated_tickets = [];
//     $transferred_tickets = [];


//     if(is_object($output) && $output->success == true){
//         $new_tickets = $output->response->new;
//         $ongoing_tickets = $output->response->ongoing;
//         $completed_tickets = $output->response->completed;
//         // $escalated_tickets = $output->response->escalated_tickets;
//         // $transferred_tickets = $output->response->transferredTickets;
//         $new_total = $output->response->new_total;
//         $ongoing_total = $output->response->ongoing_total;
//         $completed_total = $output->response->completed_total;
//         // $escalated_total = $output->response->escalated_total;
//         // $transferred_total = $output->response->transferred_total;
//     }

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
            <a class="nav-item nav-link active" id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab" aria-controls="nav-new" aria-selected="true">New</a>

            <a class="nav-item nav-link" id="nav-read-tab" data-toggle="tab" href="#nav-read" role="tab" aria-controls="nav-read" aria-selected="false">Read</a>
            
            <a class="nav-item nav-link" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="false">All</a>

            <!-- <a class="nav-item nav-link" id="nav-deleted-tab" data-toggle="tab" href="#nav-deleted" role="tab" aria-controls="nav-deleted" aria-selected="false">Deleted</a> -->

        </div>
    </nav>


    <!-- Tab Header -->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
            <?php
                $buttonClass = array("","","","btn-success","btn-primary","btn-danger","btn-secondary");
                $buttonName = array("","","","accept","read","decline","delete");
                $searchCaption = "Search Agent";
                $ID_row = 10;
                $statusButton = [4,5,6,7];
                $modalButtons  = [4,6];
                $tableHeaderLabels = ["Sp.ID", "From", "Type", "Accept","Read","Decline","Delete", "Notification Message", "Date Sent", "Time Sent"];
                $tableName = "new";
                $basicSearchId = "allSearch";
                $tableRows = [
                    ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #53. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #53. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","1"],
                    ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #55. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #55. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","2"],
                    ["192","Sharma M.", "Override Notif","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #143 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #54. (NOTES: cX badasdsasdadsO)", "May 8, 2022","9:00 AM","3"],
                    ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye-slash'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #56. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #56. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","4"]
                ];

                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
                $tableRows = [];
            ?>
        </div>

        <div class="tab-pane fade" id="nav-read" role="tabpanel" aria-labelledby="nav-read-tab">
            <?php
                $tableName = "read";
                $basicSearchId = "activeSearch";
                $tableRows = [
                    ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #53. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #53. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","1"],
                    ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #55. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #55. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","2"],
                    ["192","Sharma M.", "Override Notif","<i class='fas fa-check'></i>","<i class='far fa-eye'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #143 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #54. (NOTES: cX badasdsasdadsO)", "May 8, 2022","9:00 AM","3"],
                    ["168","Ashley C.", "Transfer Req","<i class='fas fa-check'></i>","<i class='far fa-eye'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  "AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #56. (NOTES: cX bUGO). AGENT #163 TRANSFER REQUEST REASON-R5 OTHER ON TICKET #56. (NOTES: cX bUGO)", "May 8, 2022","9:00 AM","4"]
                ];
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

        <div class="tab-pane fade" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
            <?php
                $tableName = "all";
                $basicSearchId = "bookmarkedSearch";
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

        <!-- <div class="tab-pane fade" id="nav-deleted" role="tabpanel" aria-labelledby="nav-deleted-tab">
            <?php
                // $tableName = "deleted";
                // $basicSearchId = "archivedSearch";
                // include "$level/components/UX/support-table.php";

                // // Reset Values after to prepare for the next iteration
                // $tableName = null;
                // $basicSearchId = null;
            ?>
        </div> -->

    </div>

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
            $ultimatotal = [];
            $paginationBaseTotal = 10;
            $numberOfPages = 1;
            // // 0-new, 1-ongoing, 2-completed, 3-escalations, 4-transferred
            // $ultimatotal = [$new_total,$ongoing_total,$completed_total,$escalated_total,$transferred_total];
            // $paginationBaseTotal = $ultimatotal[$current_tab];
            // $numberOfPages =  ceil($paginationBaseTotal/$send_limit);
            // // echo var_dump($numberOfPages);
            // // echo var_dump($send_page);
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
                    $starting_send_page = 0; //$send_page   
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
    <!-- <input id="total-new" type="hidden" value=<?php //echo $new_total;?>>
    <input id="total-ongoing" type="hidden" value=<?php //echo $ongoing_total;?>>
    <input id="total-completed" type="hidden" value=<?php //echo $completed_total;?>>
    <input id="total-escalated" type="hidden" value=<?php //echo $escalated_total;?>>
    <input id="total-transferred" type="hidden" value=<?php //echo $transferred_total;?>>
    <input id="limit" type="hidden" value=<?php //echo $send_limit;?>>
    <input id="page" type="hidden" value=<?php //echo $send_page;?>> -->
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