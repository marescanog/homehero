<?php 
session_start();
$output = null;

// // curl to get the needed modal information

// // CHANGELINKDEVPROD
// // Make curl for the personal inforation pagge information vv
//   $url = "http://localhost/slim3homeheroapi/public/homeowner/get-all-addresses"; // DEV
// // $url = ""; // PROD (No Prod link)

// $headers = array(
//     "Authorization: Bearer ".$_SESSION["token"],
//     'Content-Type: application/json',
// );

// // 1. Initialize
// $ch = curl_init();

// // 2. set options
//     // URL to submit to
//     curl_setopt($ch, CURLOPT_URL, $url);

//     // Return output instead of outputting it
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//     // Type of request = POST
//     // curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_HTTPGET, 1);

//     // Set headers for auth
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    

//     // Execute the request and fetch the response. Check for errors
//     $output = curl_exec($ch);

//     // Moved inside Modal Body for better display of error messages
//     $mode = "PROD"; // DEV to see verbose error messsages, PROD for production build
//     $curl_error_message = null;

//     // Declare variables to be used in this modal
    $defaultHome = null;
    $Addressess = [];
    $cities = [];
    $homeTypes = [];
    $barangays = [];

//     // ERROR HANDLING 
//     if($output === FALSE){
//         $curl_error_message = curl_error($ch);
//     }

//     curl_close($ch);

//     // $output =  json_decode(json_encode($output), true);
//     $output =  json_decode($output);

//     // Declare variables to be used in this modal
//     $defaultHome_id = null;
//     $Addressess = [];

//     // Format our data variables for modal use
//     if($curl_error_message == null && $output !== null && is_object($output) && $output->response !== null && $output->success){
//          $Addressess = $output->response->allAddress;
//         $defaultHome_id = $output->response->defaultHome_id;
//         // $Addressess = array_slice($output->response->allAddress,0,1);
//     }

    $current_selected_home_id = isset($_POST["data"]["home_id"]) ? $_POST["data"]["home_id"] : null;

?>
<div class="modal-content" style="width: auto !important;">
    <?php
        // if( $output != null && $output->success == false){
            if( false){
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
        } else if ( false) {
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
        <h5 class="modal-title" id="signUpModalLabel">SELECT YOUR ADDRESS</h5>
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
           // echo var_dump($_POST);
        ?>
    </p>
<!-- TEST AREA -->

<!-- MAIN CONTENT -->
    <?php 
        if($Addressess != null && count($Addressess) !== 0 && count($Addressess) > 1){
    ?>
<!-- USER HAS MULTIPLE ADDRESSES -->
        <div class="card">
            <div class="card-body pb-3">
                <label for="change_address">Your addresses</label>
                <select class="custom-select c" style="width:100%;" id="change_address" name="home_id">
                    <?php 
                        for($adnx = 0; $adnx < count($Addressess); $adnx++){
                    ?>
                        <option 
                            value="<?php echo $Addressess[$adnx]->home_id;?>"
                            <?php
                                if( (( $current_selected_home_id == null && $current_selected_home_id == "") && $adnx == 0) ||
                                    $Addressess[$adnx]->home_id ==  $current_selected_home_id
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
                    <button id="add_address" type="button" class="mt-2 btn btn-secondary text-white"  style="width:49%">
                        ADD ANOTHER ADDRESS
                    </button>
                    <button id="select" type="button" class="mt-2 btn btn-warning text-white"  style="width:49%" >
                        <b>CHOOSE THIS ADDRESS</b>
                    </button>
                </div>
            </div>
        </div>
        
    <?php 
        } else if ($Addressess != null && count($Addressess) == 1) {
    ?>
<!-- USER HAS ONE ADDRESS -->
        <input id="home_address_hidden" type="hidden" value="<?php echo $Addressess[0]->street_no.' '.$Addressess[0]->street_name;?>" name="home_address_text">
        <input id="home_number_hidden" type="hidden" value="<?php echo $Addressess[0]->home_id;?>" name="home_address_text">


        <!-- <div class="d-flex justify-content-between align-items-center"> -->
            <h6 class="card-subtitle mb-2 text-muted text-center h4">Your Address: </h6>
        <!-- </div> -->
            <h6 class="card-title text-center h4">
                <?php echo $Addressess[0]->complete_address;?>
            </h6>

            <p class="small-warn" style="font-size:0.85rem">
            * You currently have only one address in your list. Click "Add another address" to change to a new address.
        </p>

        <div class="d-flex justify-content-between">
            <button id="add_address" type="button" class="mt-2 btn btn-secondary text-white"  style="width:49%">
                ADD ANOTHER ADDRESS
            </button>
            <button id="choose" type="button" class="mt-2 btn btn-warning text-white"  style="width:49%" >
                <b>CHOOSE THIS ADDRESS</b>
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
        <button id="add_address" type="button" class="mt-4 btn btn-warning text-white btn-lg w-100"  >
            ADD AN ADDRESS
        </button>
    <?php 
        }
    ?>

    </form>
<?php 
    }
?>
</div>