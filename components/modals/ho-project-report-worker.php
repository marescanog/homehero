<?php
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $projectID = null;
    if($data != null){
        $projectID = $_POST['data']['projectID'];
    }
?>
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">REPORT WORKER</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-login-form" type="POST"  name="hoLoginForm">
        <div class="modal-body">

        <p>
            <?php echo var_dump( $projectID);?>
        </p>


        </div>
        <div class="modal-footer d-flex flex-row">
            <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 w-100 btn-lg">
                    <span id="RU-submit-btn-txt">SUBMIT</span>
                    <div id="RU-submit-btn-load" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
            </button>
        </div>
        </div>
    </form>
</div>