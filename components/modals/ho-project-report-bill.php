<?php
session_start();
$output = null;
// curl to get the needed modal information


    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $job_order_id = null; 
    $assigned_to = null;
    $address = null;
    $user_type=null;
    if($data != null){
        $job_order_id = isset($_POST['data']['job_order_id']) ? $_POST['data']['job_order_id'] : null;
        $assigned_to = isset($_POST['data']['assigned_to']) ? $_POST['data']['assigned_to'] : null;
        $address = isset($_POST['data']['address']) ? $_POST['data']['address'] : null;
        $user_type = isset($_POST['data']['user_type']) ? $_POST['data']['user_type'] : null;
    }

    $curl_error = null;
    // Check if a report has already been filed
    if($job_order_id !== null){
        // NODEPLOYEDPRODLINK
        // DO A CURL REQUEST TO check if A Report has already been filed
        // $url = ""; // PROD (NO LIVE DEPLOYED ROUTE LINK)
        if($user_type == 'worker') {
            $url = "http://localhost/slim3homeheroapi/public/homeowner/has-billing-issue/".$job_order_id.'/'.$_SESSION['id']; // DEV
        } else {
            $url = "http://localhost/slim3homeheroapi/public/homeowner/has-billing-issue/".$job_order_id;
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

           $bill_data = [];
            if($output !== null && $output->response != null && $output->response->bill_data != null){
               $bill_data = $output->response->bill_data;
            }
    }
?>
<div class="modal-content">
<!-- Curl Error handling and checking if JOB ID is found (Validation) -->
    <?php 
        if(   $job_order_id == null || $output == null) {
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
                    <b>500 Server Error!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
                <!-- <<p><?php //echo $job_order_id;?></p> -->
                <p><?php echo isset($output->response) && isset($output->response->message) ? var_dump($output->response->message) : var_dump($output);?></p>
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
        <h5 class="modal-title" id="signUpModalLabel">REPORT BILL ISSUE</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-report-bill" type="POST"  name="hoLoginForm">
        <div class="modal-body">

<!-- TEST AREA -->
<p>
        <?php 
             // echo var_dump($output->response->bill_data);
            // echo var_dump($output);
        ?>
    </p>

<!-- TEST AREA -->
<?php 
     if($output->response->support_ticket_info !== false){
   //     if(true){
?>
        <div class="card mb-4">
            <div class="card-body">
                <?php
                    $modal_support_ticket_ID = null;
                    $modal_ticket_status = null;
                    $modal_hasAuthor = 1;
                    if($output != null && $output->response != null && $output->response->support_ticket_info != null
                    ){
                        $modal_support_ticket_ID = $output->response->support_ticket_info->support_ticket_id;
                    }
                    // pending addition of variable in server has_author_taken_action
                    $modal_hasAuthor = 1;
                    $modal_ticket_status = $output->response->support_ticket_info->status_id;
                    $modal_ticket_status = $modal_ticket_status == null ? 4 : $modal_ticket_status-1;
                    /*
                        has author taken action = 0, No further actions needed
                        has author taken action = 1, Ticket Just Submitted
                        has author taken action = 2, Agent Notified Author
                        has author taken action = 3, Author Notified Agent
                    */
                    $stat_mod_ongoing = array("Closed","Under Investigation","Agent Requests Additional Action","You Notified Agent");
                    // New, Ongoing, Resolved, Closed
                    $stat_mod_bill = array("Pending Agent Assignment", $stat_mod_ongoing[ $modal_hasAuthor ],"Resolved","Closed","Unavailble");
                    // echo var_dump();
                ?>
                <p class="text-danger small-warn">*You have already filed for a support ticket for this billing issue.</p>

                <h5 class="card-title mb-2">
                    Support Ticket <?php echo $modal_support_ticket_ID==null?"":"ID: DIS-".str_pad($modal_support_ticket_ID, 5, "0", STR_PAD_LEFT);?>
                </h5>
                <h6 class="card-subtitle mt-3 mb-2 text-muted h5"><?php //echo $rep_job_post_name  ?? ( $rep_project_type_name ?? 'Your project'); ?></h6>
                <h6 class="card-subtitle mt-2 mb-2 text-muted">for the address at</h6>
                <p class="card-text"><?php echo $address;?></p>
                <?php if($user_type != 'worker') { ?>
                <h6 class="card-subtitle mb-2 text-muted">assigned to homehero </h6>
                <p class="card-text"><?php echo $assigned_to;?></p>
                <?php } else { ?>
                <h6 class="card-subtitle mb-2 text-muted">posted by homeowner </h6>
                <p class="card-text"><?php echo $assigned_to;?></p>
                <?php } ?>
                <hr>
                <h6 class="card-subtitle mb-2 text-muted">STATUS </h6>
                <p class="card-text"><i><?php echo  $stat_mod_bill[$modal_ticket_status];?></i></p>
            </div>
        </div>
<?php 
    } 
?>
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Bill Number</h6>
                <p class="card-text ml-3">#<?php  echo $bill_data->id == null ? "" : sprintf("%08d", $bill_data->id); ?></p>
                <h6 class="card-subtitle mb-2 text-muted">Bill Status</h6>
                <p class="card-text ml-3"><?php echo $bill_data->status == "Pending" ? "Pending Payment" : "Paid";?></p>
                <h6 class="card-subtitle mb-2 text-muted">Billed On</h6>
                <p class="card-text ml-3"><?php 
                    $billedOndate_formatted=date_create($bill_data->billed_on);
                    echo date_format($billedOndate_formatted,"D, M d Y, h:i A");
                ?></p>
                <?php 
                    if($bill_data->status != "Pending"){
                        $PaidOndate_formatted=date_create($bill_data->date_time_completion_paid);
                ?>
                    <h6 class="card-subtitle mb-2 text-muted">Paid On</h6>
                    <p class="card-text ml-3"><?php 
                        echo date_format($PaidOndate_formatted,"D, M d Y, h:i A");
                    ?>
                    </p>
                <?php 
                  }
                ?>
                <h6 class="card-subtitle mb-2 text-muted">Total Price</h6>
                <p class="card-text ml-3">P<?php echo $bill_data->total_price_billed;?></p>
            </div>
        </div>

        <div class="mb-4 ml-3">
            <a href="./help-center.php">View more info or follow-up here</a>
        </div>
<?php 
    if($output->response->support_ticket_info !== false){
?>
<button type="button" class="mt-2 btn btn-danger text-white btn-lg w-100" data-dismiss="modal">CLOSE</button>

<?php 
    } else {
?>

        <div class="form-group mt-3">
            <label for="comments">Your Concerns:</label>
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
<?php 
    }
?>

    </form>
    <?php
        }
    ?>
</div>
<?php if($user_type == 'worker') { ?>
<script src="../../js/components/modal-validation/modal-ho-report-bill2.js"></script>
<?php } else { ?>
<script src="../../js/components/modal-validation/modal-ho-report-bill.js"></script>
<?php } ?>