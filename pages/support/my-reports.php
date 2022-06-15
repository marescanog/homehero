<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}
// ONLY MANAGERS HAVE ACCESS TO THIS PAGE
if(!isset($_SESSION["role"]) || ($_SESSION["role"]!=7)){
    header("Location: ../support/home.php");
    exit();
}

$level ="../../";
// CURL STARTS HERE


// NEWLINKDEV
// Declare variables to be used in this page
// $codesRes = [];
$agentsList = [];

$url = "http://localhost/slim3homeheroapi/public/ticket/get-teams-agents"; // DEV
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
        // $codesRes = $output->response->codesRes;
            $agentsList = $output->response->agentsList;
    } else {
        $err_stat = $output->response->status;
        $message= $output->response->message;
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
        $current_side_tab = "My Reports";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">My Reports</h1>
    </div>
    <div style="width: 30rem;">
        <p><i>This page allows you to view the metrics for support and for the app in general.</i></p>
    </div>
    <?php 
        // var_dump($codesRes->DEFAULT_3);
        // var_dump($output);
    ?>
<!-- =========================== -->
<!-- TAB TABBIES -->
<!-- =========================== -->
<!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="support-tab" data-toggle="tab" href="#support" role="tab" aria-controls="support" aria-selected="true">Support Metrics</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="app-tab" data-toggle="tab" href="#app" role="tab" aria-controls="app" aria-selected="false">App Metrics</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent"> -->

<!-- =========================== -->
<!-- TAB CONTENTS -->
<!-- =========================== -->
    <!-- SUPPORT -->
  <!-- <div class="tab-pane fade show active" id="support" role="tabpanel" aria-labelledby="support-tab"> -->

    <div class="mt-4 ml-2" id="report-settings">
            <h4>Generate Report</h4>
            <div class="card mt-3" style="width: 30rem;">
              <div class="card-body">
                <div class="contaienr">
                    <div class="row mb-2">
                      <div class="col p-0">
                          <h6 class="font-italic m-0 p-0 mb-1 pb-1 ml-2">Select a report type</h6>
                          <div class="d-flex flex-column">
                              <div class="ml-3 form-check">
                                  <input class="form-check-input" type="radio" name="report_type" id="report_type_1" value="1" checked>
                                  <label class="form-check-label" for="report_type_1">
                                      Support Report
                                  </label>
                              </div>
                              <div class="ml-3 form-check">
                                  <input class="form-check-input" type="radio" name="report_type" id="report_type_2" value="2">
                                  <label class="form-check-label" for="report_type_2">
                                      App Report
                                  </label>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Support Reporting Settings -->
            <div class="card mt-3" style="width: 30rem;" id="trans_UI_1" >
              <form id="form-sup" class="card-body" method="POST">
                  <h6>
                    <i>Support Report Settings</i>
                  </h6>
                  <!-- Settings -->
                  <div class="mt-3">

                    <select class="custom-select" name="ticket_type" id="ticket_type">
                      <option selected disabled value="">Select Ticket Type</option>
                      <option value="1">All</option>
                      <option value="2">Verification Tickets</option>
                      <option value="3">Customer Support Tickets</option>
                    </select>

                    <select class="custom-select mt-3" name="ticket_status">
                      <option selected disabled value="">Select Ticket Status</option>
                      <option value="1">All</option>
                      <option value="2">New</option>
                      <option value="3">Ongoing</option>
                      <option value="4">Closed/Resolved</option>
                    </select>

                    <select class="custom-select mt-3" name="ticket_filter" id="ticket_filter" disabled>
                      <option selected disabled value="">Select Filter</option>
                      <option value="1">All</option>
                      <option value="2">By Team</option>
                      <option value="3">By Agent</option>
                    </select>

                    <div class="card d-none" id="ticket_filter_agent">
                      <div class="card-body">
                        <div class="form-group m-0 p-0">
                            <select class="custom-select" name="agent_id" id="ticket_select_agent">
                              <option selected  value="" disabled>Select Agent</option>
                              <?php 
                                for($wq = 0; $wq  < count($agentsList);  $wq++){
                                  $agent  = $agentsList[$wq];
                                  if($agent->role_type != 4){
                              ?>
                                  <option value="<?php echo $agent->id; ?>" class="<?php echo $agent->role_type == 1 ? 'ver' : 'cx';?>">
                                    ID#<?php echo $agent->id; ?> - <?php echo $agent->full_name; ?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                              <!-- <option value="1" class="cx">a</option> -->
                              <!-- <option value="3" class="ver">f</option> -->
                            </select>
                        </div>
                      </div>
                    </div>

                    <div class="card d-none"  id="ticket_filter_team">
                      <div class="card-body">
                        <div class="form-group m-0 p-0">
                            <select class="custom-select" name="agent_id" id="ticket_select_team">
                              <option selected disable value="">Select Team</option>
                              <?php 
                                for($wq = 0; $wq  < count($agentsList);  $wq++){
                                  $agent  = $agentsList[$wq];
                                  if($agent->role_type == 4){
                              ?>
                                  <option value="<?php echo $agent->id; ?>">
                                    ID#<?php echo $agent->id; ?> - <?php echo $agent->full_name; ?>'s Team
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                              <!-- <option value="1">'s team</option> -->
                            </select>
                        </div>
                      </div>
                    </div>

                    <select class="custom-select mt-3" name="ticket_time_period">
                      <option selected disable value="">Select Time Period</option>
                      <option value="1">Daily</option>
                      <!-- <option value="2">Weekly</option> -->
                      <option value="3">Monthly</option>
                    </select>

                    <div class="card mt-3">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="date_start">Start Date</label>
                          <input type="date" class="form-control mb-2"  id="date_start" name="date_start">
                        </div>
                        <div class="form-group">
                          <label for="date_end">End Date</label>
                          <input type="date" class="form-control mb-2"  id="date_end" name="date_end">
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Submission -->
                  <div class="flex-row justify-content-center">
                      <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-primary text-white font-weight-bold mb-3 mt-3">
                              <span id="RU-submit-btn-txt">GENERATE REPORT</span>
                              <div id="RU-submit-btn-load" class="d-none">
                                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                  <span class="sr-only">Loading...</span>
                              </div>
                      </button>
                  </div>
              </form>
            </div>


            <!-- App Reporting Settings -->
            <div class="card mt-3 d-none" style="width: 30rem;" id="trans_UI_2">
              <form id="form-app" class="card-body" method="POST">
                  <h6>
                    <i>App Report Settings</i>
                  </h6>
                  <!-- Settings -->
                  <div>
                  <select class="custom-select" name="app_type">
                    <option selected disable value="">Select Type</option>
                      <option value="1">All</option>
                      <option value="2">Job Posts</option>
                      <option value="3">Job Orders</option>
                    </select>

                    <select class="custom-select mt-3" name="app_filter">
                      <option selected disable value="">Select Filter</option>
                      <option value="1">All</option>
                      <option value="2">Completed</option>
                      <option value="3">Cancelled</option>
                      <option value="4">Ongoing</option>
                      <option value="5">Expired</option>
                    </select>

                    <select class="custom-select mt-3" name="app_time_period">
                      <option selected disable value="">Select Time Period</option>
                      <option value="1">Daily</option>
                      <option value="2">Weekly</option>
                      <option value="3">Monthly</option>
                    </select>

                    <div class="card mt-3">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="date_start">Start Date</label>
                          <input type="date" class="form-control mb-2"  id="date_start" name="date_start">
                        </div>
                        <div class="form-group">
                          <label for="date_end">End Date</label>
                          <input type="date" class="form-control mb-2"  id="date_end" name="date_end">
                        </div>
                      </div>
                    </div>
                    <!-- 
                        type: All, job Orders, Job posts
                        filter: all, completed, cancelled, ongoing, expired
                        By month, by day, by year
                        For Period Start - End
                    -->
                  </div>
                  <!-- Submission -->
                  <div class="flex-row justify-content-center">
                      <button id="AR-submit-btn"  type="submit" value="Submit"  class="btn btn-primary text-white font-weight-bold mb-3 mt-3">
                              <span id="AR-submit-btn-txt">GENERATE REPORT</span>
                              <div id="AR-submit-btn-load" class="d-none">
                                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                  <span class="sr-only">Loading...</span>
                              </div>
                      </button>
                  </div>
                </div>
              </form>
            </div>
    </div>

<div class="container">
  <div id="graph-UI" role="main" class="container col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 d-none">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
              <h1 id="chart-title" class="h2">Dashboard</h1>
              <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                  <button id="new-report" class="btn btn-sm btn-outline-secondary">New</button>
                  <!-- <button  id="print-report" class="btn btn-sm btn-outline-secondary">Print</button> -->
                </div>
                <!-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                  <span data-feather="calendar"></span>
                  This week
                </button> -->
              </div>
            </div>

            <canvas class="my-4" id="myChart" width="900" height="380"></canvas>

            <div class="div" id="table-load">
            </div>

    </div>
</div>

  <!-- APP -->
  <!-- <div class="tab-pane fade" id="app" role="tabpanel" aria-labelledby="app-tab">
  </div> -->
    
</main>
    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
<!-- <script src="../../js/pages/my-reports.js"></script> -->
<!-- Icons -->
  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

    <script src="../../js/pages/man-sup-reports.js"></script>

</body>
</html>