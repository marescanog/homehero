<?php
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $projectID = null;
    $oldSched = null;
    $reformattedDate = null;
    $reformattedTime = null;
    if($data != null){
        $projectID = $_POST['data']['projectID'];
        $oldSched = $_POST['data']['old_date_time'];
    }
?>
<div class="modal-content">
    <?php 
        // if($projectID == null || $oldSched == null){
        if(false){
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
        }else {
    ?>
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">CHANGE START DATE & TIME</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-reschedule-project" type="POST" >
        <div class="modal-body">
        <?php 
            if( $oldSched != null){
                $oldSched = explode(" ",$oldSched);
                $time = explode(":", $oldSched[1]);
                $date = explode("-", $oldSched[0]);
                $reformattedDate =  $date[2].'/'.$date[1].'/'.$date[0];

                $period =  $time[0] > 12 ? "PM" : "AM";
                $hours = $time[0] > 12 ?  $time[0]-12 :  $time[0]+0;
                $reformattedTime = $hours.':'.$time[1].' '.$period ;
            }
        ?>

        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">Previous Schedule</h5>
                <div class="d-flex flex-row justify-content-between">
                    <div style="width:49%">
                        <h6 class="card-subtitle mb-2 text-muted">Date</h6>
                        <p class="card-text"><?php //echo $reformattedDate;?></p>
                    </div>
                    <div style="width:49%">
                        <h6 class="card-subtitle mb-2 text-muted">Time</h6>
                        <p class="card-text"><?php //echo $reformattedTime;?></p>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php //echo $projectID;?>">
        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">New Schedule</h5>
                <div class="d-flex flex-row justify-content-between">
                    <div class="form-group" style="width:49%">
                        <label for="date" class="h6 card-subtitle mb-2 text-muted">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php //echo $date_time_arr[0];?>">
                    </div>
                    <div class="form-group" style="width:49%">
                        <label for="time" class="h6 card-subtitle mb-2 text-muted">Time</label>
                        <input type="time" class="form-control" id="time" name="time" value="<?php //echo $date_time_arr[1];?>">
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mb-0" style="font-size:0.8rem;">** Rescheduling will refresh this project's start date and make this post visible to workers in the area. Click on the reschedule button below to continue.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 47%">
                        <span id="RU-submit-btn-txt">RESCHEDULE </span>
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
        }
    ?>
</div>
<!-- <script src="../../js/components/modal-validation/modal-ho-project-reschedule.js"></script> -->
