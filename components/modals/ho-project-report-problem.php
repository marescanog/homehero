<?php
session_start();
$output = null;
// curl to get the needed modal information

    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $job_order_id = null; 
    $assigned_to = null;
    $rep_job_post_name = null;
    $rep_project_type_name = null;
    $rep_home_address_label = null;
    $rep_assigned_to = null;
    $user_type = null;

    if($data != null){
        $job_order_id = isset($_POST['data']['job_order_id']) ? $_POST['data']['job_order_id'] : null;
        $assigned_to = isset($_POST['data']['assigned_to']) ? $_POST['data']['assigned_to'] : null;
        $rep_job_post_name = isset($_POST['data']['job_post_name']) ? $_POST['data']['job_post_name'] : null;
        $rep_project_type_name = isset($_POST['data']['project_type_name']) ? $_POST['data']['project_type_name'] : null;
        $rep_home_address_label = isset($_POST['data']['home_address_label']) ? $_POST['data']['home_address_label'] : null;
        $rep_assigned_to = isset($_POST['data']['assigned_to'] )? $_POST['data']['assigned_to'] : null;
        $user_type = isset($_POST['data']['user_type'] )? $_POST['data']['user_type'] : null;
    }

    $curl_error = null;
    // Check if a report has already been filed
    if($job_order_id !== null){
        // NODEPLOYEDPRODLINK
        // DO A CURL REQUEST TO check if A Report has already been filed
        // $url = ""; // PROD (No current deployed prod route)
        if($user_type == 'worker') {
            $url = "http://localhost/slim3homeheroapi/public/homeowner/has-job-issue/".$job_order_id.'/'.$_SESSION['id']; // DEV
        } else {
            $url = "http://localhost/slim3homeheroapi/public/homeowner/has-job-issue/".$job_order_id;
        }
            
        $headers = array(
            "Authorization: Bearer ".$_SESSION["token"],
            'Content-Type: application/json',
        );

        // 1. Initialize
        $ch = curl_init();

        // 2. set options
            // URL to submit to
            curl_setopt($ch, CURLOPT_URL, $url);

            // Return output instead of outputting it
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Type of request = GET
            curl_setopt($ch, CURLOPT_HTTPGET, 1);

            // Set headers for auth
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



            // Execute the request and fetch the response. Check for errors
            $output = curl_exec($ch);

            if($output === FALSE){
                $curl_err = curl_error($ch);
            }

            curl_close($ch);

            // $output =  json_decode(json_encode($output), true);
            $output =  json_decode($output);

            $success = "";
            $support_ticket_info = null;
            $lastSupportTicketAction = null; //
            if($output !== null && $output->response != null){
                $support_ticket_info = isset($output->response->support_ticket_info ) ? $output->response->support_ticket_info  : null;
                $lastSupportTicketAction = isset($output->response->lastSupportTicketAction) ? $output->response->lastSupportTicketAction : null;
            }
    }
?>
<div class="modal-content">
<!-- Curl Error handling and checking if JOB ID is found (Validation) -->
    <?php 
        if(   $job_order_id == null) {
    ?>

        <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>404 Project Not Found!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div>

    <?php 
        } else if ($output !== null && $output->success == false) {
    ?>

        <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>500 Server Srror!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div>


    <?php 
        } else if ($curl_error != null) {
    ?>

        <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>500 Error!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div>
        
    <?php 
        } else {
    ?>
<!-- Main content -->
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">REPORT JOB ORDER PROBLEM</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-report-problem" type="POST"  name="hoLoginForm">
        <div class="modal-body">

<!-- TEST AREA -->

    <p>
        <?php 
            // echo var_dump($output);
            // echo var_dump($support_ticket_info->job_order_id);
        ?>
    </p>

<!-- TEST AREA -->
    <?php 
        if ($support_ticket_info == false){
     ?>
<!-- If homeowner has not submitted form -start  -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $rep_job_post_name  ?? ( $rep_project_type_name ?? 'Your project'); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">for the address at</h6>
                <p class="card-text"><?php echo $rep_home_address_label;?></p>
                <?php if($user_type != 'worker') { ?>
                    <h6 class="card-subtitle mb-2 text-muted">assigned to homehero </h6>
                    <p class="card-text"><?php echo $rep_assigned_to;?></p>
                <?php } else { ?>
                    <h6 class="card-subtitle mb-2 text-muted">posted by homeowner </h6>
                    <p class="card-text"><?php echo $rep_assigned_to;?></p>
                <?php } ?>
            </div>
        </div>
        

        <div class="form-group mt-3">
            <label for="comments">What is your concern?</label>
            <textarea class="form-control" id="comments" name="author_description" rows="3"></textarea>
        </div>

        <input type="hidden" name="id" value="<?php echo $job_order_id;?>">
        <?php if($user_type == 'worker') { ?> <input type="hidden" value="<?php echo $_SESSION['id'];?>" name="user_id"> <?php } ?>
        <!-- Issue number 7 - Job Order Issue -->

        </div>
        <div class="modal-footer d-flex flex-row">
            <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-danger text-white font-weight-bold mb-3 mt-3 w-100 btn-lg">
                <span id="RU-submit-btn-txt">SUBMIT TICKET</span>
                <div id="RU-submit-btn-load" class="d-none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        </div>
        </div>
    </form>
<!-- If homeowner has not submitted form -end  -->
<?php 
        } else {
     ?>
<!-- A SUPPORT TICKET IS ALREADY MADE -start  -->
        <div class="card">
            <div class="card-body">
                <p class="text-danger small-warn">*You have already filed for a support ticket for this job order.</p>
                <h5 class="card-title mb-2">Support Ticket #<?php echo $support_ticket_info->support_ticket_id == null ? "" : sprintf("%08d", $support_ticket_info->support_ticket_id);?></h5>
                <h6 class="card-subtitle mt-3 mb-2 text-muted h5"><?php echo $rep_job_post_name  ?? ( $rep_project_type_name ?? 'Your project'); ?></h6>
                <h6 class="card-subtitle mt-2 mb-2 text-muted">for the address at</h6>
                <p class="card-text"><?php echo $rep_home_address_label;?></p>
                <?php if($user_type != 'worker') { ?>
                    <h6 class="card-subtitle mb-2 text-muted">assigned to homehero </h6>
                    <p class="card-text"><?php echo $rep_assigned_to;?></p>
                <?php } else { ?>
                    <h6 class="card-subtitle mb-2 text-muted">posted by homeowner </h6>
                    <p class="card-text"><?php echo $rep_assigned_to;?></p>
                <?php } ?>
            </div>
        </div>

        <div class="card my-4">
            <div class="card-header">
                Support Ticket Details
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Status: <?php echo $support_ticket_info->status_id == 1 ? "Pending investigation" : $support_ticket_info->status;?></li>
                <li class="list-group-item">Last updated on: 
                    <?php 
                        $action_date=date_create($lastSupportTicketAction->action_date);
                        echo date_format($action_date,"D, M d Y, h:i A");
                    ?></li>
                <li class="list-group-item">Submitted on: 
                    <?php 
                        $created_on=date_create($support_ticket_info->created_on);
                        echo date_format($created_on,"D, M d Y, h:i A");
                    ?></li>
            </ul>
        </div>

        <button type="button" class="mt-2 btn btn-danger text-white btn-lg w-100" data-dismiss="modal">CLOSE</button>

    <?php
        } // for check if support ticket exists
    } // for error handling
    ?>
<!-- A SUPPORT TICKET IS ALREADY MADE -end  -->
</div>

<?php if($user_type == 'worker') { ?>
    <script src="../../js/components/modal-validation/modal-ho-report-problem2.js"></script>
<?php } else  { ?>
    <script src="../../js/components/modal-validation/modal-ho-report-problem.js"></script>
<?php } ?>