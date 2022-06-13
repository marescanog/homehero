<?php 
session_start();

// Initialize and set necessary variables
$output = null; 
$homeownerID = isset($_POST["data"]["homeownerID"]) ?  $_POST["data"]["homeownerID"] : null;
$homeID_submit = isset($_POST["data"]["homeIDSubmit"]) ? $_POST["data"]["homeIDSubmit"] : null;
$current_selected_home_id = isset($_POST["data"]["home_id"]) ? $_POST["data"]["home_id"] : null;

// Declare variables to be used in this modal
$Addressess = [];

if($homeownerID != null){
// curl to get the needed modal information
// CHANGELINKDEVPROD
// Make curl for the personal inforation pagge information vv
  $url = "http://localhost/slim3homeheroapi/public/ticket/get-homeowner-address/".$homeownerID; // DEV
// $url = ""; // PROD (No Prod link)

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
        $data = $output->response->data;
        $Addressess = $data->address_list;
    } else {
        $err_stat = $output->response->status;
        $message= $output->response->message;
    }
}
    
?>
<div class="modal-content" style="width: auto !important;">
    <?php
        if( $output != null && $output->success == false){
            // if( false){
    ?>
        <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>500 Error</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div>
    <?php
        // } else if ( $curl_error_message != null || $output == null) {
        } else if ( $homeownerID == null || $homeownerID == "" ) {
    ?>    
    <div class="modal-header">
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <div>
                <b>Error loading Edit Modal!</b>
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
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">SELECT AN ADDRESS</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-enter-address" type="POST"  name="hoLoginForm">
        <div class="modal-body">
<!-- TEST AREA -->
    <p class="p-0 m-0"> 
        <?php 
            // var_dump($data);
            // var_dump($Addressess)
           //  echo var_dump($output);
            // echo var_dump( $Addressess );
           // echo "</br>";
           // echo var_dump($output->response);
            // echo "</br>";
             // echo var_dump($output->response->allAddress);
            // echo "</br>";
            // echo var_dump($output->response->cities);
            // echo "</br>";
           //echo var_dump($output->response->barangays);
        //    echo var_dump($_POST);
        //    echo var_dump($current_selected_home_id);
        //    echo var_dump($homeownerID);
        //    echo var_dump($homeownerID == null || $homeownerID == "");
        //    echo var_dump( $homeID_submit);
        ?>
    </p>
<!-- TEST AREA -->

<!-- MAIN CONTENT -->
    <?php 
        if($Addressess != null && count($Addressess) !== 0 && count($Addressess) > 1){
    ?>
<!-- USER HAS MULTIPLE ADDRESSES -->
        <form class="card" method="POST">
            <div class="card-body pb-3">
                <label for="change_address">Account holder's list of addresses</label>
                <select id="jo_change_address_select" class="custom-select c" style="width:100%;"  name="home_id">
                    <?php 
                        for($adnx = 0; $adnx < count($Addressess); $adnx++){
                    ?>
                        <option 
                            value="<?php echo $Addressess[$adnx]->home_id;?>"
                            <?php
                                if( (( $homeID_submit == null && $homeID_submit == "") && $adnx == 0) ||
                                    $Addressess[$adnx]->home_id ==  $homeID_submit
                                ){
                                    echo 'selected';
                                }
                            ?>
                        >
                            <?php 
                                echo htmlentities($Addressess[$adnx]->complete_address);
                            ?>
                        </option>
                    <?php 
                        }
                    ?>
                </select>
                <div class="d-flex justify-content-between">
                    <button id="jo_select_address" type="submit" class="mt-2 btn btn-warning text-white"  style="width:100%" >
                        <b>NEXT</b>
                    </button>
                </div>
            </div>
        </form>
        
    <?php 
        } else if ($Addressess != null && count($Addressess) == 1) {
    ?>
<!-- USER HAS ONE ADDRESS -->
        <input id="home_address_hidden" type="hidden" value="<?php echo $Addressess[0]->street_no.' '.$Addressess[0]->street_name;?>" name="home_address_text">
        <input id="home_number_hidden" type="hidden" value="<?php echo $Addressess[0]->home_id;?>" name="home_address_text">

        <!-- <div class="d-flex justify-content-between align-items-center"> -->
            <h6 class="card-subtitle mb-2 text-muted text-center h4">The Current Job Order Address: </h6>
        <!-- </div> -->
            <h6 class="card-title text-center h4">
                <?php echo $Addressess[0]->complete_address;?>
            </h6>

            <p class="small-warn" style="font-size:0.85rem">
            * The account holder currently has only one address in their list. Only the account holder is authorized to add a different address.
        </p>

        <div class="d-flex justify-content-between">
            <button id="close_one_address" type="button" class="mt-2 btn btn-warning text-white"  style="width:100%" >
                <b>CLOSE</b>
            </button>
        </div>

    <?php 
        } else {
    ?>
<!-- USER HAS NO ADDRESS -->
        <div class="card">
            <div class="card-body d-flex justify-content-center align-items-center">
                <p class="card-text text-center h6">You currently do not have an address in your list. Please add an address.</p>
            </div>
        </div>
        <button id="close_no_address" type="button" class="mt-4 btn btn-warning text-white btn-lg w-100"  >
            CLOSE
        </button>
    <?php 
        }
    ?>

    </form>
<?php 
    }
?>
</div>
<script src="../../js/components/modal-validation/modal-sup-edit-jo-address.js"></script>