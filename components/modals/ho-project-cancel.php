<?php
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $can_projectID = null;
    $can_job_post_name = null;
    $can_project_type_name = null;
    $can_home_address_label = null;
    $can_assigned_to = null;
    $user_type = null;
    if($data != null){
        $can_projectID = $_POST['data']['projectID'];
        $can_job_post_name = $_POST['data']['job_post_name'];
        $can_project_type_name = $_POST['data']['project_type_name'];
        $can_home_address_label = $_POST['data']['home_address_label'];
        $can_assigned_to = $_POST['data']['assigned_to'] ?? null;
        $user_type = $_POST['data']['user_type'] ?? null;
    }
?>
<div class="modal-content">
    <?php 
        if($can_projectID == null){
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
        } else {
    ?>
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">CANCEL JOB ORDER</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="cancel-Project-form" type="POST"  name="cancelProjectForm">
    <div class="modal-body">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $can_job_post_name  ?? ( $can_project_type_name ?? 'Your project'); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">for the address at</h6>
                <p class="card-text"><?php echo $can_home_address_label;?></p>
                <?php if($user_type != 'worker') { ?>
                    <h6 class="card-subtitle mb-2 text-muted">assigned to homehero </h6>
                    <p class="card-text"><?php echo $can_assigned_to;?></p>
                <?php } else { ?>
                    <h6 class="card-subtitle mb-2 text-muted">posted by homeowner </h6>
                    <p class="card-text"><?php echo $can_assigned_to;?></p>
                <?php } ?>
            </div>
        </div>
        <input type="hidden" value="<?php echo $can_projectID;?>" name="id">
        <?php if($user_type == 'worker') { ?> <input type="hidden" value="<?php echo $_SESSION['id'];?>" name="user_id"> <?php } ?>
        
        <div class="form-group mt-3">
            <label for="cancellation_reason">Please provide your cancellation reason below:</label>
            <textarea class="form-control mb-0" id="cancellation_reason" rows="3" name="cancellation_reason"></textarea>
        </div>
        <h5 class="text-center mb-2 mt-0">Are you sure your want to cancel this job order?</h5>
        <h6 class="text-center">The homehero will be notified of this cancellation</h6>
    </div>
        
        <div class="modal-footer d-flex flex-row justify-content-between">
            <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-danger text-white font-weight-bold mb-3 mt-3" style="width: 47%">
                    <span id="RU-submit-btn-txt">YES, CANCEL</span>
                    <div id="RU-submit-btn-load" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
            </button>
            <button type="button" class="btn btn-secondary" style="width: 47%" data-dismiss="modal">NO, KEEP POST</button>
        </div>
        </div>
    </form>
    <?php 
        }
    ?>
</div>

<?php if($user_type == 'worker') { ?>
    <script src="../../js/components/modal-validation/modal-ho-cancel-order2.js"></script>
<?php } else { ?>
    <script src="../../js/components/modal-validation/modal-ho-cancel-order.js"></script>
<?php } ?>
