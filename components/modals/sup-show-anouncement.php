

<?php 
    session_start();
    date_default_timezone_set('Asia/Manila');
    $level = isset($_POST['level']) ? $_POST['level'] : '.';

    $supportToken = isset($_SESSION['token_support']) ? $_SESSION['token_support'] : null;
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
        //$_POST['data']['id']
    $a_id = isset($_POST['data']) && isset($_POST['data']['id']) ? $_POST['data']['id'] : null;
    $output = null;
    $isFound = false;
    $title = "";
    $content = "";

if(   $a_id != null){
// Initialize and set necessary variables
// Do a cURL request to get the necessary info
// NOLINKDEVPROD
$url = "http://localhost/slim3homeheroapi/public/support/get-single-anouncement/".$a_id; // DEV
    
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
        // $sup_List = $output->response->data->agentsList;
        $isFound = true;
        $title = $output->response->data->anouncement->title;
        $content = $output->response->data->anouncement->details;
    }
    curl_close($ch);


}
?>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content px-3">
      <div class="modal-header" style="border-bottom: 0;">
          <div class="mx-auto" style="width: auto;">
              <img src='<?php echo $level;?>/images/logo/HH_Logo_Light.svg'>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem 0;">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
              <?php 
                if($output == null ||  $isFound == false){
              ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">404: Anouncement  not found!</h4>
                    <p>The anouncement could not be found. Please try refreshing your page.</p>
                </div>
              <?php 
                } else {
              ?>
                    <div id="modal-login-form" type="POST"  name="hoLoginForm">
                        <h4 style="font-weight: bold; font-size: 26px; color: #707070"><?php echo htmlentities($title)?></h4>
                        <h5 class="mt-3" style="font-size: 16px; color: #707070"><?php echo htmlentities($content)?></h5>
                        <?php 
                            // var_dump($output->response->data->anouncement->details);
                        ?>
                    </div>
                <?php 
                }
              ?>

          </div>

    </div>
</div>
<!-- <script src="<?php //echo $level?>/js/components/modal-validation/modal-homeowner-login.js"></script> -->
                   