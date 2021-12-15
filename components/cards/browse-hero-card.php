
<div class="card shadow rounded mt-4">
  <div class="card-body">
    <div class="row">
        <div class="p-0 m-0 col-3 col-lg-2">
        <?php
            if($profile_pic == null || $profile_pic == false || $profile_pic == "false"){
        ?>
            <div class="avatar d-flex justify-content-center align-items-center">
                <h2><?php echo $winitials;?></h2>
            </div>
        <?php
            } else {
        ?>
            <div class="avatar d-flex justify-content-center align-items-center">
                <img src="<?php echo $profile_pic;?>" alt="worker's profile pic" class="img-fluid">
            </div>
        <?php
            }
        ?>          

        </div>
        <div class="p-0 m-0 col-6 col-lg-5">
            <div class="d-flex flex-column pt-lg-3">
                <h2>
                    <?php echo $name == null ? "" : htmlentities($name);?>
                </h2>
                <div class="d-flex align-items-center">
                    <?php 
                        // $hasRatings = $rating_average == null ? false : true;
                        $hasRatings = $rating_average == null ? false : true;
                        $rating = $rating_average == null ? 0 : $rating_average;
                        include "$level/components/UX/ratingsDisplay.php";
                    ?>
                    <?php
                        if($rating_average != null){
                    ?>
                    <h6 class="pt-1 ml-2">(<?php echo $total_ratings;?> ratings)</h6>
                    <?php
                        }
                    ?>
                </div>
                <div class="d-flex align-items-center pt-1">
                    <?php
                        if($completed_jobs != 0){
                    ?>
                        <i class="far fa-check-circle mr-1"></i>
                    <?php
                        }
                    ?>
                    <h6 class="p-0 m-0"><?php echo $completed_jobs;?> Projects Completed</h6>
                </div>
            </div>
        </div>
        <div class="p-0 m-0 col-2 col-lg-5 d-flex justify-content-end align-items-center">
           <button id="notif-<?php echo $worker_id?>"class="btn btn-primary mr-lg-5" onclick="openModal(<?php echo $worker_id?>)">
               Notify
           </button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-2 justify-content-center align-items-center">
            <!-- EMPTY FOR SPACE -->
        </div>
        <div class="col-3 justify-content-center align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <p class="p-0 m-0"><?php echo $city_info;?></p>
            </div>
        </div>
        <div class="col-3 justify-content-center align-items-center">
            <?php if($pricing !== null && $pricing !== "" && $pricing !== 0 && $pricing !== "0"){ ?>
                <div class="d-flex align-items-center">
                    <i class="fas fa-tag mr-2"></i>
                    <p class="p-0 m-0"><?php echo $pricing;?></p>
                </div>
            <?php }?>
        </div>
        <div class="col-3 justify-content-center align-items-center">
        <?php if ($skill_list != null){ ?>
            <div class="d-flex align-items-center">
                <i class="fas fa-newspaper mr-2"></i>
                <p class="p-0 m-0">
                    <?php echo $skill_list;?>
                </p>
            </div>
        <?php } ?>
        </div>
    </div>
  </div>
</div>