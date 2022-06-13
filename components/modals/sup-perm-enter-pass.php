<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $permission_code = null; // 1-transfer, 2-request override, 3-escalte to supervisor
    $supID = null;
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    if($data != null){
        $permission_code = isset($_POST['data']['permission_code']) ? $_POST['data']['permission_code'] : null;
        $supID = isset($_POST['data']['supervisor_id']) ? $_POST['data']['supervisor_id'] : null;
    }
?>
<div class="modal-content">
    <?php 
        // if($projectID == null || $oldSched == null){
    ?> 
        <!-- Error handler (Not needed for this modal) -->
        <!-- <div class="modal-header">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div>
                    <b>404 Project Not Found!</b>
                </div>
                <p>Please close the modal & Refresh the browser.</p>
            </div>   
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:1.5em">&times;</span>
            </button>
        </div> -->
    <?php 
        // }else {
            // var_dump($role);
    ?>
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">
            ENTER YOUR PASSWORD
        </h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-perm-password"  method="POST">
        <div class="modal-body">
        <?php 

        ?>
        <input type="hidden" name="permission_id" value="<?php echo htmlentities($permission_code);?>">
        <div class="form-group">
            <input type="password" name="password" class="form-control" id="sup-perm-pass" placeholder="">
        </div>


        <?php 
            if($role == 7){
        ?>
            <!-- <?php var_dump($supID); ?> -->
            <input type="hidden" value="<?php echo $supID; ?>" name="supervisor_id">
        <?php 
            }
        ?>  


        <p class="text-center mb-0" style="font-size:0.8rem;">** Confirm this permission reset by entering your password to generate a new code.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 100%">
                        <span id="RU-submit-btn-txt">GENERATE NEW CODE</span>
                        <div id="RU-submit-btn-load" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                        </div>
                </button>
                <!-- <button type="button" class="btn btn-secondary" style="width: 47%" data-dismiss="modal">CLOSE</button> -->
            </div>
        </div>
    </form>
    <?php 
        // }
    ?>
</div>
<script src="../../js/components/modal-validation/modal-sup-permission-reset.js"></script>
