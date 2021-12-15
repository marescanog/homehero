<?php
session_start();
$output = null;
$worker_id = isset($_POST['data']['worker_id']) ? $_POST['data']['worker_id'] : null;
// curl to get the needed modal information

// NODEPLOYEDPRODLINK
// Make curl for the personal inforation pagge information vv
$url = "http://localhost/slim3homeheroapi/public/homeowner/get-my-projects"; // DEV - NEW ROUTE specifically for populating form including single address data
// $url = ""; // NO PROD ROUTE

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

    // Type of request = POST
    // curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);

    // Set headers for auth
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    

    // Execute the request and fetch the response. Check for errors
    $output = curl_exec($ch);

    // Moved inside Modal Body for better display of error messages
    $mode = "PROD"; // DEV to see verbose error messsages, PROD for production build
    $curl_error_message = null;

// VAR ----------------------------------------------
    // Declare variables to be used in this modal
    $jobPosts = [];


?><!-------------------------------------------------->
    <!-- HTML ZONE -->

    <!-- This HTML displays the head of the modal with title and close X button -->
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">NOTIFY WORKER</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>


    <?php //--------------- PHP ZONE ------------------------
    // ERROR HANDLING 
    if($output === FALSE){
        $curl_error_message = curl_error($ch);
    }

    curl_close($ch);

    // $output =  json_decode(json_encode($output), true);
     $output =  json_decode($output);


    // ERROR HANDLING - Curl Error
    if($curl_error_message){
    ?><!-------------------------------------------------->
    <!-- HTML ZONE : CURL ERROR HANDLING & MESSAGE DISPLAY -->
        
        <!-- Displays an Alert containing CURL issues -->
        <div class="modal-body">
            <div class="title-2-container alert alert-danger alert-dismissible fade show" role="alert">
                <!-- TITLE -->
                <div>
                    <h5>CURL ERROR</h5>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- BODY -->
                <div>
                    <?php echo $curl_error_message ; ?>
                </div>
            </div>
        </div>

    <?php //--------------- PHP ZONE ------------------------
    }

    // ERROR HANDLING - Server Error
    if(!$curl_error_message &&  is_object($output) && ($output->success == false)){
    ?><!-------------------------------------------------->
    <!-- HTML ZONE : CURL ERROR HANDLING & MESSAGE DISPLAY -->

        <!-- Displays an Alert containing Server issues -->
            <div class="modal-body">
            <div class="title-2-container alert alert-danger alert-dismissible fade show" role="alert">
                <!-- TITLE -->
                <div>
                    <h5>
                        <?php
                         if ($output->response && $output->response->status == 500 ){
                                echo "SERVER ERROR 500";
                            } else if ($output->response && $output->response->status == 401 ){
                                echo "SERVER ERROR 401: NOT FOUND";
                            } else {
                                echo "SERVER ERROR";
                            }
                        ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- BODY -->
                <div>
                    <?php 
                        if($output->response->status){
                            echo $output->response->message;
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php //--------------- PHP ZONE ------------------------
    }

    // ERROR HANDLING - Server Error
    if($output == null || $output == "" || empty($output)){
        ?><!-------------------------------------------------->
        <!-- HTML ZONE : CURL ERROR HANDLING & MESSAGE DISPLAY -->
    
            <!-- Displays an Alert containing Server issues -->
                <div class="modal-body">
                <div class="title-2-container alert alert-danger alert-dismissible fade show" role="alert">
                    <!-- TITLE -->
                    <div>
                        <h5>
                            <?php
                                if($output == null){
                                    echo "OUTPUT FROM CURL IS NULL";
                                } else if ($output == "" || empty($output)){
                                    echo "OUTPUT FROM CURL IS EMPTY";
                                } else {
                                    echo "OUTPUT IS UNKNOWN";
                                }
                            ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- BODY -->
                    <div>
                        <?php 
                            if($curl_error_message){
                                echo $curl_error_message;
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php //--------------- PHP ZONE ------------------------
        }
// VAR ----------------------------------------------
// Format our data variables for modal use
// ----------------------------------------------
    if($curl_error_message == null && $output !== null && is_object($output) && $output->response !== null && $output->success){
        $jobPosts = $output->response->ongoingJobPosts;
    }

?>
    <!-- MODAL BODY DEV -->
    <?php //--------------- PHP ZONE ------------------------
        if($mode !== null && $mode == "DEV"){
    ?> <!-------------------------------------------------->
    <!-- HTML ZONE -->
        <div class="modal-body">
            <h6>Your current session variables</h6>
            <p>
                <?php
                    echo var_dump($_SESSION);
                ?>
            </p>
            <h6>Your curl output</h6>
            <p>
                <?php
                    echo var_dump($output);
                ?>
            </p>
        </div>
    <?php //--------------- PHP ZONE ------------------------
    } else {
    ?><!-------------------------------------------------->
<!-- TESTING AREA -->
<?php
    // echo var_dump($worker_id);
    // echo var_dump($jobPosts[0]);
?>
<!-- TESTING AREA -->
    <!-- HTML ZONE -->
    <!-- ======================================================= -->
    <!-- YOUR MAIN CONTENT -->
    <!-- ======================================================= -->

    <form id="notifyWorkerModal" type="POST" name="modalForm">
        <div class="modal-body">
        <!-- Modal body Start -->
            
        <div class="container">
            <p>Please select a project that you'd like to notify the worker about:</p>
            <select id="project_select" class="custom-select c mb-3" name="project_id">
                <option selected disabled value="">Select your posted project</option>
                <?php 
                    for($jpIndx = 0; $jpIndx < count($jobPosts); $jpIndx++){
                ?>
                    <option value="<?php echo $jobPosts[$jpIndx]->id;?>">
                        <?php 
                            $jp_name = $jobPosts[$jpIndx]->job_post_name == "" ? htmlentities($jobPosts[$jpIndx]->project_type) : htmlentities($jobPosts[$jpIndx]->job_post_name);
                            $jp_date=date_create($jobPosts[$jpIndx]->preferred_date_time);
                            $jp_formatted_date =  date_format($jp_date,"M d, h:i A");
                            echo $jp_name." - ". $jp_formatted_date;
                        ?>
                    </option>
                <?php 
                    }
                ?>
            </select>
            <input type="hidden" value="<?php echo $worker_id;?>" name="worker_id">
            <?php 
                for($jpIndx = 0; $jpIndx < count($jobPosts); $jpIndx++){
            ?>
            <div id="post-details-<?php echo $jobPosts[$jpIndx]->id;?>" class="card d-none cardTastic" >
                <div class="card-header">
                    <span id="jp-lbl-name">
                        <?php echo $jobPosts[$jpIndx]->job_post_name == "" ? htmlentities($jobPosts[$jpIndx]->project_type) : htmlentities($jobPosts[$jpIndx]->job_post_name);?>
                    </span>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Your Scheduled Date: <?php 
                        echo date_format(date_create($jobPosts[$jpIndx]->preferred_date_time),"D, M d Y, h:i A");?>
                    </li>
                    <li class="list-group-item">Address: <?php echo $jobPosts[$jpIndx]->complete_address;?></li>
                    <li class="list-group-item">Your Offer: <?php echo $jobPosts[$jpIndx]->rate_offer.'/'.$jobPosts[$jpIndx]->rate_type;?></li>
                    <li class="list-group-item">Description: <?php echo $jobPosts[$jpIndx]->job_description;?></li>
                </ul>
            </div>
            <?php 
               }
            ?>
        </div>
                
        <!-- Modal body End -->
        </div>
        <div class="modal-footer">
            <button id="PI-submit-btn" type="submit" value="Submit" class="w-100 btn btn-warning text-white qbtn-text-2 justify-content-center align-items-center">
                    <span id="PI-submit-btn-txt">Send Project to Worker</span>
                    <div id="PI-submit-btn-load" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                </div>
            </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </form>


<?php //----------------------------------------------
}
?><!-------------------------------------------------->

</div>

<script>
    $("#notifyWorkerModal").validate({
    rules: {
        project_id:"required",
    },
    messages: {
        project_id: {
            required: "Please select a project to notify the worker about"
        },
    },
    submitHandler: function(form, event) { 
        event.preventDefault();
        console.log("NOTIFY WORKER");
        const addressData = getFormDataAsObj(form);

        const button = document.getElementById("PI-submit-btn");
        const buttonTxt = document.getElementById("PI-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("PI-submit-btn-load");
        const formData = getFormDataAsObj(form);
        // console.log(formData);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);


        $.ajaxSetup({cache: false})
        $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
                    // console.log(data)
                    const parsedSession = JSON.parse(data);
                    const token = parsedSession['token'];
                    console.log(token);
                    console.log(formData);

                                
                    // Create new form 
                    const samoka = new FormData();

                    // Append 
                    samoka.append('project_id', formData["project_id"]);
                    // samoka.append('barangay_id', formData["worker_id"]);
                   

                    // console.log("your token is")
                    // console.log(token);
                    // console.log(form);
                    // console.log(samoka);


                $.ajax({
                    type : 'POST',
                     url : "http://localhost/slim3homeheroapi/public/homeowner/send-project/"+formData["worker_id"], // Dev
                    // url : "", // No Prod Deployed Route
                    data : samoka,
                        contentType: false,
                        processData: false,
                        headers: {
                            "Authorization": `Bearer ${token}`
                        },
                    success : function(response) {
                        console.log(response.response);
                        let hasalreadyNotifiedWorker = response.response.hasbeen_Notified_before;
                        let status = response.response.notification_status;
                        if(hasalreadyNotifiedWorker){
                            Swal.fire({
                            title: 'You have already notified this worker!',
                            text: 'Status: '+status,
                            icon: 'info'
                            });
                        } else {
                            Swal.fire({
                            title: 'Worker successfully notified!',
                            text: 'Status: '+status,
                            icon: 'success'
                            });
                        }

                        $("#modal").modal('hide');
                    },
                    error: function (response) {
                            console.log(response);
                            Swal.fire({
                                title: 'An error occurred',
                                text: 'Please try again',
                                icon: 'error'
                            });
                    }
                });

        });

        


    }



    });

    

</script>