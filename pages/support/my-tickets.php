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

$url = "http://localhost/slim3homeheroapi/public/support/my-tickets"; // DEV
// $url = ""; // NO PROD LINK

$headers = array(
    "Authorization: Bearer ".$_SESSION["token_support"],
    'Content-Type: application/json',
);

$send_limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? $_GET['limit']: 10 ;
$send_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// 0-ongoing, 1-completed, 2-escalations, 3-transferred
$get_tab = isset($_GET['tab']) && $_GET['tab'] ?  $_GET['tab'] : null;
$current_tab = isset($_GET['tab']) && is_numeric($get_tab) ? ($get_tab > 0 && $get_tab <4 ? $get_tab : 0) : 0;

$post_data = array(
    'email' => $_SESSION["email"],
    'page' => $send_page,
    'limit' => $send_limit
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
    $ongoing_total = 0;
    $completed_total = 0;
    $escalated_total = 0;
    $transferred_total = 0;
    $ongoing_tickets = [];
    $completed_tickets = [];
    $escalated_tickets = [];
    $transferred_tickets = [];


    if(is_object($output) && $output->success == true){
        $ongoing_total = $output->response->ongoing_total;
        $completed_total = $output->response->completed_total;
        $escalated_total = $output->response->escalated_total;
        $transferred_total = $output->response->transferred_total;
        $ongoing_tickets = $output->response->ongoing_tickets;
        $completed_tickets = $output->response->completed_tickets;
        $escalated_tickets = $output->response->escalated_tickets;
        $transferred_tickets = $output->response->transferredTickets;
    }


// HTML starts here
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/support-table.css">
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
        <h1 class="h2">My Tickets</h1>
    </div>
    <?php
    // var_dump($_GET['limit']);
    // var_dump($send_page);
    // var_dump($send_limit);
    //    var_dump($_SESSION);
    //    var_dump($output);
    // var_dump($output->response->ongoing_total);
    // var_dump($ongoing_total);
    // var_dump($ongoing_tickets);
    ?>

    <!-- Tab Header -->
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a onClick="addURLParameter(['tab','page'],['0','1'])" class="nav-item nav-link <?php echo $current_tab==0?"active":"";?>" id="nav-ongoing-tab" data-toggle="tab" href="#nav-ongoing" role="tab" aria-controls="nav-ongoing" aria-selected="<?php echo $current_tab==0?"true":"false";?>">Ongoing</a>

            <a onClick="addURLParameter(['tab','page'],['1','1'])" class="nav-item nav-link <?php echo $current_tab==1?"active":"";?>" id="nav-completed-tab" data-toggle="tab" href="#nav-completed" role="tab" aria-controls="nav-completed" aria-selected="<?php echo $current_tab==1?"true":"false";?>">Completed</a>
            
            <a onClick="addURLParameter(['tab','page'],['2','1'])" class="nav-item nav-link <?php echo $current_tab==2?"active":"";?>" id="nav-escalations-tab" data-toggle="tab" href="#nav-escalations" role="tab" aria-controls="nav-escalations" aria-selected="<?php echo $current_tab==2?"true":"false";?>">Escalations</a>

            <a onClick="addURLParameter(['tab','page'],['3','1'])" class="nav-item nav-link <?php echo $current_tab==3?"active":"";?>" id="nav-transferred-tab" data-toggle="tab" href="#nav-transferred" role="tab" aria-controls="nav-transferred" aria-selected="<?php echo $current_tab==3?"true":"false";?>">Transferred</a>

            <!-- <a class="nav-item nav-link" id="nav-stale-tab" data-toggle="tab" href="#nav-stale" role="tab" aria-controls="nav-stale" aria-selected="false">Stale</a> -->
        </div>
    </nav>


    <!-- Tab Header -->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade  <?php echo $current_tab==0?"show active":"";?>" id="nav-ongoing" role="tabpanel" aria-labelledby="nav-ongoing-tab">
            <?php 
include "$level/components/UX/ticketTableConversion.php";
/* Note:
 Required Variables to Declare: 
    $tableName -> The name of your table
    $basicSearchId -> The button ID for your search bar element
    $totalRecords -> total number of records returned by the query
    $EntriesDisplayed -> total number of entries displayed
    $tableHeaderLabels = the table headers you want to display, default is ["Ticket No.", "Type", "Status", "Assigned Agent", "Last Updated", "Date Assigned", "Date Created"]
    $tableRows = the table data you want to display
    Example data format for header above is:

    $tableRows = [
        ["REG-001", "Registration", "Ongoing", "Ashley Miles", "09/12/2021", "09/12/2021", "09/12/2021", "1"],
        ["DIS-002", "Dispute", "New", "Jim Day", "09/12/2021", "09/12/2021", "09/12/2021", "2"],
        ["REG-003", "Registration", "Resolved", "Ashley Miles", "09/12/2021", "09/12/2021", "09/12/2021", "3"],
    ];
    
    $statusButton = the number of the column where a button will be; default is 3
*/
                // $roleSubTypes = ["1,2,3","4,5,6,7,8,9","10,11","12,13,14,15,16"];
                // // Registration/Verification, Customer Support,GEN, Technical Support

                $tableName = "Ongoing";
                $basicSearchId = "ongoingSearch";

                $totalRecords = $ongoing_total;
                $tableRows = array_map('convertPlainDataToTableRow', $ongoing_tickets);

                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
                $totalRecords = 0;
                $tableRows = [];
            ?>
        </div>



        <div class="tab-pane fade <?php echo $current_tab==1?"show active":"";?>" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
            <?php
                $tableName = "completed";
                $basicSearchId = "completedSearch";

                $totalRecords = $completed_total;
                $tableRows = array_map('convertPlainDataToTableRow', $completed_tickets);

                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
                $totalRecords = 0;
                $tableRows = [];
                
            ?>
        </div>



        <div class="tab-pane fade <?php echo $current_tab==2?"show active":"";?>" id="nav-escalations" role="tabpanel" aria-labelledby="nav-escalations-tab">
            <?php
                $tableName = "escalations";
                $basicSearchId = "escalationsSearch";

                $totalRecords = $escalated_total;
                $tableRows = array_map('convertPlainDataToTableRow', $escalated_tickets);

                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
                $totalRecords = 0;
                $tableRows = [];
            ?>
        </div>



        <div class="tab-pane fade <?php echo $current_tab==3?"show active":"";?>" id="nav-transferred" role="tabpanel" aria-labelledby="nav-transferred-tab">
            <?php
                $tableName = "transferred";
                $basicSearchId = "transferredSearch";

                $totalRecords = $transferred_total;
                $tableRows = array_map('convertPlainDataToTableRow', $transferred_tickets);

                include "$level/components/UX/support-table.php";
                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
                $totalRecords = 0;
                $tableRows = [];
            ?>
        </div>



        <!-- <div class="tab-pane fade" id="nav-stale" role="tabpanel" aria-labelledby="nav-stale-tab">
            <?php
                // $tableName = "stale";
                // $basicSearchId = "staleSearch";
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
                Show 10 Entries
            </button>
            <div class="dropdown-menu">
                <button id="btn-select-10" class="dropdown-item" type="button">Show 10 Entries</button>
                <button id="btn-select-20" class="dropdown-item" type="button">Show 20 Entries</button>
                <button id="btn-select-30" class="dropdown-item" type="button">Show 30 Entries</button>
            </div>
        </div>
        <!-- Pagination -->
        <?php 
            // 0-ongoing, 1-completed, 2-escalations, 3-transferred
            $ultimatotal = [$ongoing_total,$completed_total,$escalated_total,$transferred_total];
            $paginationBaseTotal = $ultimatotal[$current_tab];
            $numberOfPages =  ceil($paginationBaseTotal/$send_limit);
            echo var_dump($numberOfPages);
            echo var_dump($send_page);
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
                    $ciel_page =  $over_lim <= 0 ? $send_page : $over_lim+1;
                    for($titi = $send_page > $ciel_page ? $ciel_page : $send_page, $pagLimit = 0; $pagLimit < 5 && $titi <= $numberOfPages; $titi++, $pagLimit++){
                ?>
                    <li class="page-item <?php echo $send_page==$titi?"active":"";?>"><a id="pag-<?php echo $titi;?>" class="page-link"><?php echo $titi;?></a></li>
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

    <!-- For Javascript pagination manipulation -->
    <input id="total-ongoing" type="hidden" value=<?php echo $ongoing_total;?>>
    <input id="total-completed" type="hidden" value=<?php echo $completed_total;?>>
    <input id="total-escalated" type="hidden" value=<?php echo $escalated_total;?>>
    <input id="total-transferred" type="hidden" value=<?php echo $transferred_total;?>>
    <input id="limit" type="hidden" value=<?php echo $send_limit;?>>
    <input id="page" type="hidden" value=<?php echo $send_page;?>>
    <input id="tab" type="hidden" value=<?php echo $current_tab;?>>
</main>




    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script>
        // ==================
        // Show Entries Limit
        let show_entries = document.getElementById("btn-entry-select");
        let show_10 = document.getElementById("btn-select-10");
        let show_20 = document.getElementById("btn-select-20");
        let show_30 = document.getElementById("btn-select-30");

        show_10.addEventListener("click", ()=>{
            show_entries.innerText = "Show 10 Entries";
            addURLParameter("limit",10);
        });

        show_20.addEventListener("click", ()=>{
            show_entries.innerText = "Show 20 Entries";
            addURLParameter("limit",20);
        });

        show_30.addEventListener("click", ()=>{
            show_entries.innerText = "Show 30 Entries";
            addURLParameter("limit",30);
        });

        // ==================
        // Pagination
        // Get Values
            let my_limit = document.getElementById("limit").value;
            let my_page = document.getElementById("page").value;
            let my_tab = document.getElementById("tab").value;
            let my_ongoing = document.getElementById("total-ongoing").value;
            let my_completed = document.getElementById("total-completed").value;
            let my_escalated = document.getElementById("total-escalated").value;
            let my_transferred = document.getElementById("total-transferred").value;
            let ultimatotal = [my_ongoing,my_completed,my_escalated,my_transferred];
        // Get Hooks
            let prev_page = document.getElementById("pag-prev");
            let next_page = document.getElementById("pag-next");

        prev_page.addEventListener("click", ()=>{
            if(my_page != 1){
                addURLParameter("page",parseInt(my_page)-1);   
            }
        });

        next_page.addEventListener("click", ()=>{
            if(my_page != Math.ceil(ultimatotal[my_tab]/my_limit)){
                addURLParameter("page",parseInt(my_page)+1);
            }
        });

        // TODO, CLICKABLE BUTTONS
    </script>
    
</body>
</html>