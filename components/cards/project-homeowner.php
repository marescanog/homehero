<?php
    $address = isset($address) ? $address : null;
    $d_formatted = isset($d_formatted) ? $d_formatted : null;
    $job_order_size = isset( $job_order_size) ?  $job_order_size : null;
    $pref_sched = isset($pref_sched) ? $pref_sched : null;
    $job_desc = isset($job_desc) ? $job_desc : null;
    $job_title = isset($job_title) ? $job_title: null;
    $project_type = isset($project_type) ? $project_type : null;

    // For DB meta values
    $job_status =  isset($job_status) ? $job_status: null;
    $job_id =  isset($job_id) ? $job_id: null;
    $isRated = isset( $isRated) ?  $isRated: null;
    $job_order_id = isset($job_order_id) ? $job_order_id: null;
    $cancellation_reason = isset(  $cancellation_reason) ?   $cancellation_reason: null;
    $job_order_status_id = isset($job_order_status_id) ? $job_order_status_id: null;
    $assigned_to = isset($assigned_to) ?   $assigned_to: null;
    $bill_status_id = isset($bill_status_id) ?   $bill_status_id: null;

    $tab_link = isset($tab_link) ?   $tab_link: "";
    $tomorrow = new DateTime('tomorrow'); 
    $today =  isset( $today) ?  $today: null;
    $jo_start_time = isset( $jo_start_time) ?  $jo_start_time : null;

    $date_paid = isset($date_paid) ? $date_paid : null;
    $rate_offer = isset($rate_offer ) ? $rate_offer  : null;
    $rate_type_id = isset(  $rate_type_id) ?  $rate_type_id  : null;
    $rt_array = ['/hr', '/day','/week','/project'];

    // order cancellation varilables
     $cancelled_by = isset($cancelled_by ) ? $cancelled_by  : null;
     $homeowner_id = isset($homeowner_id) ? $homeowner_id : null;
     $order_cancellation_reason = isset($order_cancellation_reason) ? $order_cancellation_reason : null;

     // must parse because the 's create an error if unescaped
     $job_desc = $job_desc != null ? addslashes($job_desc) : null;
     $job_title = $job_title != null ? addslashes($job_title) : null;


    // For billing 
    $total_price_billed  = $total_price_billed != null ? htmlentities($total_price_billed) : null ;
    $date_time_completion_paid = $date_time_completion_paid != null ? htmlentities($date_time_completion_paid) : null ;
    $computedRating = $computedRating != null ? $computedRating : 0;

    // For phone number display
    $phone_no =  $phone_no == null ?  null :  $phone_no;
?>
<div class="card mt-3 mb-4 shadow ">
    <div class="card-header" style="background-color:#FCEBBF;">
        <h5 class="card-title titulo-proj"><?php echo $job_title  ?? ( $project_type?? 'Project Name'); ?></h5>
        <h6 class="mb-0 mt-0">Status: 
            <span class="
                <?php
                    if ($job_order_status_id == 1 && $today!= null && $d != null && $today>$d && $jo_start_time == null) {
                            echo "text-danger";
                    } else 
                    if($job_status == 2 && $job_order_status_id != 3){
                        echo "text-success";
                    } else if ($job_status == 4){ // cancelled post
                        echo "text-danger";
                    } else if ($job_order_status_id == 3){ // cancelled order
                        // echo nothing
                    }else if ($job_status == 3){ // expired
                        if($job_order_status_id == null || $job_order_status_id  != 1){ // not assigned
                            echo "text-danger";
                        } 
                    }
                ?>
            ">
                <?php 
                if ($job_status == 1){
                    echo 'Not Assigned';
                } else if  ($job_status == 2){
                    if($job_order_status_id == 1){
                        if($today!= null && $d != null && $today>$d && $jo_start_time == null){
                            echo 'Assigned To '.$assigned_to;
                            echo '</br>';
                            echo "<span class='small-warn'>** This worker has not started the job and the scheduled time has already passed. Please cancel and repost in the event the worker does not show.</span>";
                        } else {
                            echo 'Assigned To '.$assigned_to;
                        }
                    } else if ($job_order_status_id == 3){
                        $nameWhoCancelled = "";
                        if ($cancelled_by != null && $homeowner_id != null){
                            $nameWhoCancelled = $cancelled_by  == $homeowner_id ? " You" : $assigned_to;
                        }
                        echo 'Assigned To '.$assigned_to;
                        echo '</br>';
                        echo "<span class='text-danger mt-1'>Cancelled by: ".$nameWhoCancelled."</span>";
                    } else {
                        echo 'Completed by '.$assigned_to;
                    }
                } else if  ($job_status == 3){
                    if($job_order_status_id == null || $job_order_status_id  != 1){ // not assigned
                        echo "Expired";
                    } 
                } else if  ($job_status == 4 || $job_order_status_id  == 1){
                    echo 'Cancelled';
                }
                ?>
            </span>
        </h6>
    </div>
<!-- ====================================== -->
<!-- POST INFORMATION - CARD BODY  -->
<!-- ====================================== -->
    <div class="card-body">
        <div class="d-flex flex-row align-items-center">
            <div class="gray-icon">
                <?php
                    include dirname(__FILE__)."/".$level.'/images/svg/local_offer_black_24dp.svg';  
                ?>
            </div>
            <h6 class="card-subtitle mb-2 text-muted mt-1"><b>Your offer:
                <?php 
                    if(  $rate_offer != null &&  $rate_type_id != null){
                        echo $rate_offer.$rt_array[$rate_type_id-1];
                    }
                ?>
            </b></h6>
        </div>
        
        <div class="d-flex flex-row">
            <div class="gray-icon">
                <?php
                    include dirname(__FILE__)."/".$level.'/images/svg/today_black_24dp.svg'; 
                ?>
            </div>
            <p id="dateLabel" class=""><b>Schedule:</b> <?php echo $d_formatted ?? 'Day, Month X at 0:00 PM'; ?></p>
        </div>

        <div class="d-flex flex-row">
            <div class="gray-icon">
                <?php
                    include dirname(__FILE__)."/".$level.'/images/svg/location_on_black_24dp.svg'; 
                ?>
            </div>
            <p id="addressLabel"> <b>Home address:</b> <?php echo $address ?? 'Address information'; ?></p>
        </div>

        <div class="d-flex flex-row">
            <div class="gray-icon">
                <?php
                    include dirname(__FILE__)."/".$level.'/images/svg/straighten_black_24dp.svg'; 
                ?>
            </div>
            <p id="jobSizeLabel"><b>Job size:</b> <?php echo $job_order_size ?? 'Job size information'; ?></p>
        </div>
        <div class="d-flex flex-row">
            <div class="gray-icon">
                <?php
                    include dirname(__FILE__)."/".$level.'/images/svg/description_black_24dp.svg'; 
                ?>
            </div>
            <p id="descLabel"><b>Description:</b> <?php echo $job_desc ?? 'Job description'; ?></p>
        </div>

        <?php if($job_status == 4 || $job_order_status_id == 3){?>
            <div class="d-flex flex-row">
                <div class="gray-icon">
                    <?php
                        include dirname(__FILE__)."/".$level.'/images/svg/close_black_24dp.svg'; 
                    ?>
                </div>
                <p id="descLabel"><b>Cancellation Reason:</b> 
                    <?php 
                        if($job_status == 4){
                            echo $cancellation_reason ?? ''; 
                        } else if ($job_order_status_id == 3){
                            echo $order_cancellation_reason ?? ''; 
                        }
                    ?></p>
            </div>
        <?php }?>
<!-- ====================================== -->
<!-- JOB ORDER STATUSES  -->
<!-- ====================================== -->
        <?php if($job_status == 2 && $job_order_status_id  == 1 ){?>
            <div class="d-flex flex-row">
                <div class="gray-icon">
                    <div class="status-circle 
                    <?php
                        if($today!= null && $d != null && $today>$d && $jo_start_time == null){
                            echo 'bg-danger ';
                        } else if ($jo_start_time != null) {
                            echo 'bg-success ';
                        }
                    ?>"></div>
                </div>
                <p id="descLabel"><b>Job Order Status:</b> 
                    <span class="
                    <?php
                        if($today!= null && $d != null && $today>$d && $jo_start_time == null){
                            echo 'text-danger font-weight-bold font-italic';
                        } else if ($jo_start_time != null) {
                            echo 'text-success font-weight-bold';
                        }
                    ?>
                    ">
                        <?php
                            if($today!= null && $d != null && $today>$d && $jo_start_time == null){
                                echo 'Not Started By worker - Past Schedule';
                            } else if ($jo_start_time != null) {
                                echo 'In Progress';
                            } else {
                                echo 'Pending';
                            }
                        ?>
                    </span>
                </p>
            </div>
        <?php }?>

<!-- ====================================== -->
<!-- PAYMENT DISPLAY  -->
<!-- ====================================== -->
        <?php if($job_order_status_id == 2 && $total_price_billed != null){?>
            <div class="d-flex flex-row">
                <div class="gray-icon">
                    <?php
                        include dirname(__FILE__)."/".$level.'/images/svg/payments_black_24dp.svg'; 
                    ?>
                </div>
                <p id="descLabel"><b>Total payment:</b> <?php echo $total_price_billed ?? ''; ?></p>
            </div>
            <div class="d-flex flex-row">
                <div class="gray-icon">
                    <?php
                        if($date_time_completion_paid != null){
                            include dirname(__FILE__)."/".$level.'/images/svg/verified.svg'; 
                        } else {
                            include dirname(__FILE__)."/".$level.'/images/svg/pending_black_24dp.svg'; 
                        }
                    ?>
                </div>
                <p id="descLabel"><b>Status: </b>
                <?php
                        $formatted_datepaid=date_create($date_time_completion_paid);
                        if($date_time_completion_paid != null){
                           echo 'Paid on '.date_format($formatted_datepaid,"D, M d Y, h:i A"); 
                        } else {
                           echo 'Pending payment';
                        }
                ?>
            </p>
            </div>
        <?php }?>
        

<!-- ====================================== -->
<!-- RATINGS DISPLAY -->
<!-- ====================================== -->
        <?php if($job_order_status_id == 2){?>
            <div class="d-flex flex-row">
                <div class="gray-icon">
                    <?php
                        include dirname(__FILE__)."/".$level.'/images/svg/star4.svg'; 
                    ?>
                </div>
                <p id="descLabel"><b>Ratings:</b> 
                    <?php 
                     
                        if($isRated != null && $isRated == 1){
                            // compute rating
                            echo 'Your rated '.$computedRating.' stars';
                        } else {
                            echo 'You did not rate this job order.'; 
                        }
                    ?></p>
            </div>
        <?php }?>
    </div>

<!-- ====================================== -->
<!-- Phone Number DISPLAY -->
<!-- ====================================== -->
<?php
    if($job_order_status_id == 1){
?>
<div class="container mb-4">
    <div class="card">
    <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">Contact Details:</h6>
        <p class="ml-2 mb-0 pb-0"><i class="fas fa-phone"></i> <?php echo htmlentities( $phone_no);?></p>
    </div>
    </div>
</div>
<?php
    }
?>

<!-- ====================================== -->
<!-- BUTTONS DISPLAY - LEFT SIDE -->
<!-- ====================================== -->

    <div class="card-footer text-muted">
        <div class="d-flex justify-content-between">
           <div class="d-flex">
                <a href="<?php echo $level;?>/pages/homeowner/project-info.php?id=<?php echo $job_id.$tab_link ;?>">
                    <button class="btn btn-warning text-white">
                        <b>VIEW</b>
                    </button>
                </a>
                    <?php
                        // You can only edit a project when it is not filled
                        if($job_status == 1){
                    ?> 
                        <button class="btn btn-outline-warning ml-2" style="border: 2px solid #f0ad4e" data-toggle="modal" data-target="#modal" onclick="editProject(<?php echo addslashes(htmlentities($job_id)).',\''.addslashes(htmlentities($job_title)).'\',\''.addslashes($pref_sched).'\',\''.addslashes($job_size_id).'\',\''.addslashes($rate_offer).'\',\''.addslashes($rate_type_id).'\',\''.addslashes(htmlentities($job_desc)).'\',\''.addslashes($home_id).'\',\''.addslashes(htmlentities($address)).'\''; ?>)">
                            <b>EDIT</b>
                        </button>
                    <?php 
                        // user can reshedule but not edit only if the post expired
                        } else if ($job_status == 3) {
                    ?>
                        <button class="btn btn-secondary text-white ml-2" data-toggle="modal" data-target="#modal" onclick="reschedule(<?php echo $job_id.',\''.$pref_sched.'\'';?>)">
                            <b>RESCHEDULE</b>
                        </button>
                    <?php 
                        } else {                            
                            // Edit button also appears when project is filled
                            // but it is not one day before the scheduled date
                            // decided to change that cannot edit when post is filled, just comment out
                            // Rebook instead
                    ?>
                        <?php if($job_order_status_id != 2 && $job_order_status_id != 3 && $job_status != 4){?>
                            <!-- <button class="btn text-white ml-1
                                <?php 
                                    // if($today!= null && $d != null && $tomorrow>$d){
                                    //     echo "disabled btn-secondary";
                                    // } else {
                                    //     echo "btn-warning";
                                    // }
                                    // ?>"
                                    // <?php 
                                    // if($today!= null && $d != null && $tomorrow>$d){
                                    // ?>
                                    //     data-toggle="tooltip" data-placement="top" title="Editing disabled 1 day before scheduled date"
                                    // <?php
                                    // } 
                                ?> 
                            >
                                <b>EDIT</b>
                            </button> -->

                            <?php
                                if($job_order_status_id == 1 && $today!= null && $d != null && $today>$d && $jo_start_time == null){
                            ?>
                                <button class="btn btn-danger text-white ml-2" data-toggle="modal" data-target="#modal" onclick="cancelandRepost(<?php echo $job_order_id.',\''.$pref_sched.'\',\''. addslashes($job_title).'\',\''. addslashes($project_type).'\',\''.addslashes($address).'\'';?>)">
                                    <b>CANCEL & REPOST</b>
                                </button>
                            <?php
                                }
                            ?>


                        <?php } 
                        
                        
                        
                        // You can only complete payment & rate if the job order is complete
                            if($job_order_status_id == 2){
                        ?>
                            <?php
                                if($date_paid == null){
                            ?>
                                <button class="btn btn-success text-white ml-2" data-toggle="modal" data-target="#modal" onclick="completePayment(<?php echo $job_order_id;?>)">
                                    <b>COMPLETE PAYMENT</b>
                                </button>
                            <?php 
                                }
                            ?>

                            <?php
                                if($isRated != null && $isRated == 0){
                            ?>
                                <button class="btn btn-outline-success ml-2" style="border: 3px solid #5cb85c" data-toggle="modal" data-target="#modal" onclick="rateProject(<?php echo $job_order_id;?>)">
                                    <b>RATE</b>
                                </button>
                            <?php 
                                }
                            ?>
                        <?php 
                            }
                        ?>


                    <?php 
                        }
                    ?>
           </div>


<!-- ====================================== -->
<!-- BUTTONS DISPLAY - RIGHT SIDE -->
<!-- ====================================== -->
           <?php
                // Case when worker does not show, user can report the worker
                if($job_order_status_id == 1 && $today!= null && $d != null && $today>$d && $jo_start_time == null){
           ?>
            <button class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="reportNoShow(<?php echo $job_order_id.',\''.addslashes($assigned_to).'\'';?>)">
                    REPORT WORKER
                </button>
           <?php
                } else {
           ?>
                <?php 
                    // Case when it is still a post
                    if($job_status == 1 && $job_order_status_id == null){
                ?>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="cancelJobPost(<?php echo $job_id.',\''.addslashes($job_title).'\',\''.$project_type.'\',\''.addslashes($address).'\'';?>)">
                        CANCEL POST
                    </button>
                <?php 
                    // Case when it is a job order
                    } else if ($job_status == 2 && $job_order_status_id == 1){
                        // Cannnot cancel a job order when it has started but can report to admin to close job order
                        // In event homehero doesn't stop job
                ?>
                    <?php 
                        if( $jo_start_time == null){
                    ?>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="cancelProject(<?php echo $job_order_id.',\''.addslashes(htmlentities($job_title)).'\',\''.addslashes(htmlentities($project_type)).'\',\''.addslashes(htmlentities($address)).'\',\''.addslashes(htmlentities($assigned_to)).'\'';?>)">
                            CANCEL JOB ORDER
                        </button>
                    <?php 
                        } else {
                    ?>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="reportProblem(<?php echo $job_order_id.',\''.addslashes(htmlentities($job_title)).'\',\''.addslashes(htmlentities($project_type)).'\',\''.addslashes(htmlentities($address)).'\',\''.addslashes(htmlentities($assigned_to)).'\'';?>)">
                            REPORT PROBLEM
                        </button>
                    <?php 
                        }
                    ?>
                <?php 
                    } else {
                    // All other cases
                ?>
                    <!-- For now just billing issues instead of all other cases -->
                    <?php
                        if($job_order_status_id == 2 && $date_paid == null){
                    ?>
                         <button class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="reportBill(<?php echo $job_order_id.',\''.addslashes(htmlentities($address)).'\',\''.addslashes($assigned_to).'\'';?>)">
                            DISPUTE BILL
                        </button>
                    <?php 
                        }
                    ?>
                <?php
                    }
                ?>
           <?php 
                }
           ?>

            
        </div>
    </div>
</div>