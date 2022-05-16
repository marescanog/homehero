<?php
    date_default_timezone_set('Asia/Manila');
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    // $projectID = null;
    $oldSched = null;
    $changedVal = null;
    $today = isset($_POST['data']) && isset($_POST['data']['today']) ? $_POST['data']['today'] : date("YYYY-MM-DD H:i:s");
    $reformattedTime = null;
    $changedDate = "1212-12-12";
    $changedTime = "00:00:00";
    if($data != null){
        // $projectID = isset($_POST['data']['projectID']) ? $_POST['data']['projectID'] : null;
        $oldSched = isset($_POST['data']['old_date_time']) ? $_POST['data']['old_date_time'] : null;
        $changedVal = isset($_POST['data']['changedValue']) ? ($_POST['data']['changedValue'] == "" ? null : $_POST['data']['changedValue']) : null;

        if($changedVal != null){
            $explodedChangedDate = explode(" ",$changedVal);
            $changedTime = $explodedChangedDate[1];
            $changedDate = $explodedChangedDate[0];
        }
    }
?>
<div class="modal-content">
    <?php 
        // var_dump($_POST);
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
    ?>
    <div class="modal-header">
        <h5 class="modal-title" id="signUpModalLabel">CHANGE JOB START DATE & TIME</h5>
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size:1.5em">&times;</span>
        </button>
    </div>
    <div name="modalForm">
    <form id="modal-edit-jo-start-date"  method="POST">
        <div class="modal-body">
        <?php 
            // Get the reformatted time for the input variable & display
            $monthArr = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
             $setDate = $oldSched != null ? $oldSched : $today;
                $explodedDate = explode(" ",$setDate);
                $time = explode(":", $explodedDate[1]);
                $date = explode("-", $explodedDate[0]);
                $reformattedDate =  $monthArr[$date[1]-1].' '.$date[2].', '.$date[0];

                $period =  $time[0] > 12 ? "PM" : "AM";
                $hours = $time[0] > 12 ?  $time[0]-12 :  $time[0]+0;
                $reformattedTime = $hours.':'.$time[1].' '.$period ;
        ?>

        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">Previous Schedule</h5>
                <div class="d-flex flex-row justify-content-between">
                    <div style="width:49%">
                        <h6 class="card-subtitle mb-2 text-muted">Date</h6>
                        <p class="card-text"><?php echo $reformattedDate;?></p>
                    </div>
                    <div style="width:49%">
                        <h6 class="card-subtitle mb-2 text-muted">Time</h6>
                        <p class="card-text"><?php echo $reformattedTime;?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card  mb-3">
            <div class="card-body">
                <h5 class="card-title">New Schedule</h5>
                <div class="d-flex flex-row justify-content-between">
                    <div class="form-group" style="width:49%">
                        <label for="date" class="h6 card-subtitle mb-2 text-muted">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $changedVal != null ?  $changedDate : "";?>">
                    </div>
                    <div class="form-group" style="width:49%">
                        <label for="time" class="h6 card-subtitle mb-2 text-muted">Time</label>
                        <input type="time" class="form-control" id="time" name="time" value="<?php echo $changedVal != null ?  $changedTime : "";?>">
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mb-0" style="font-size:0.8rem;">** Review your changes after selecting the date and clicking on "Change Date". Save your changes by submitting the form on the main page.</p>
        </div>
            <div class="modal-footer d-flex flex-row justify-content-center">
                <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold mb-3 mt-3 btn-lg" style="width: 47%">
                        <span id="RU-submit-btn-txt">NEXT</span>
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
<script src="../../js/components/modal-validation/modal-sup-edit-jo-start-date.js"></script>
