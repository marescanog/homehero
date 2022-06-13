
<?php
    $current_side_tab = isset($current_side_tab) ? $current_side_tab : 'Dashboard';
    $tabs=['Dashboard', 'My Tickets', 'All Tickets', 'Messages', 'Account Settings','My Team', 'Permissions', 'Team Tickets', 'My Reports', 'Escalations & Issues', 'Modify User Priveledges'];
    $side_nav_role =  isset($_SESSION["role"]) ? $_SESSION["role"]  : null; 
?>
<div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky d-flex flex-column">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[0] ? 'active' : '';?>" href="./home.php">
                  <i class="fas fa-external-link-square-alt icons"></i>
                  Dashboard  <?php echo $current_side_tab == $tabs[0] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>

              <?php 
                if($side_nav_role!= null && ($side_nav_role == 4 || $side_nav_role == 7)){
              ?>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[3] ? 'active' : '';?>" href="./messages.php">
                    <i class="fas fa-envelope-open-text icons"></i>
                  Notifications <?php echo $current_side_tab == $tabs[3] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <?php 
                }
              ?>



<!-- Managers & admin do not need this thus remove from their dash -->
<?php 
    if($side_nav_role < 5){
?>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[1] ? 'active' : '';?>" href="./my-tickets.php">
                  <i class="fas fa-clipboard-check icons"></i>
                  My Tickets <?php echo $current_side_tab == $tabs[1] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[2] ? 'active' : '';?>" href="./all-Tickets.php">
                    <i class="fas fa-clipboard-list icons"></i>
                  All Tickets <?php echo $current_side_tab == $tabs[2] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <?php 
                // if($side_nav_role!= null && ($side_nav_role == 4 || $side_nav_role == 7)){
              ?>
                <!-- <li class="nav-item">
                  <a class="nav-link <?php echo $current_side_tab == $tabs[7] ? 'active' : '';?>" href="./team-Tickets.php">
                    <i class="fas fa-sitemap icons" style="transform: translateX(-3px)"></i>
                    Team Tickets <?php echo $current_side_tab == $tabs[7] ? "<span class='sr-only'>(current)</span>" : '';?>
                  </a>
                </li> -->
              <?php 
                // }
              ?>

              <?php 
                // if($side_nav_role!= null && ($side_nav_role != 4 && $side_nav_role != 7)){
              ?>
              <!-- <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[3] ? 'active' : '';?>" href="./messages.php">
                    <i class="fas fa-envelope-open-text icons"></i>
                  Notifications <?php echo $current_side_tab == $tabs[3] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li> -->
              <?php 
                // }
              ?>

              <?php 
                if($side_nav_role!= null && ($side_nav_role == 4 || $side_nav_role == 7)){
              ?>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[5] ? 'active' : '';?>" href="./my-team.php">
                  <i class="fas fa-users icons" style="transform: translateX(-2px)"></i>
                  My Team <?php echo $current_side_tab == $tabs[5] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <?php 
                }
              ?>

<?php 
    }
?>




<!-- Add reporting tab for manager -->
<?php 
  if($side_nav_role == 7){
?>
  <li class="nav-item">
    <a class="nav-link <?php echo $current_side_tab == $tabs[8] ? 'active' : '';?>" href="./my-reports.php">
      <i class="fas fa-chart-area icons"></i>
      My Reports <?php echo $current_side_tab == $tabs[8] ? "<span class='sr-only'>(current)</span>" : '';?>
    </a>
  </li>
<?php 
  }
?>

<?php 
  if($side_nav_role == 5 || $side_nav_role == 6){
?>
  <li class="nav-item">
    <a class="nav-link <?php echo $current_side_tab == $tabs[9] ? 'active' : '';?>" href="./escalations.php">
      <i class="fas fa-bug icons"></i>
      Escalations & Issues <?php echo $current_side_tab == $tabs[9] ? "<span class='sr-only'>(current)</span>" : '';?>
    </a>
  </li>
<?php 
  }
?>

<?php 
  if($side_nav_role == 5 || $side_nav_role == 6){
?>
  <li class="nav-item">
    <a class="nav-link <?php echo $current_side_tab == $tabs[10] ? 'active' : '';?>" href="./priveledges.php">
      <i class="fas fa-user-circle icons"></i>
      Modify User Priveledges <?php echo $current_side_tab == $tabs[10] ? "<span class='sr-only'>(current)</span>" : '';?>
    </a>
  </li>
<?php 
  }
?>


              <?php 
                if($side_nav_role!= null && ($side_nav_role == 4 || $side_nav_role == 5 ||$side_nav_role == 6 || $side_nav_role == 7)){
              ?>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[6] ? 'active' : '';?>" href="./permissions.php">
                <i class="fas fa-key icons"></i>
                  Permissions <?php echo $current_side_tab == $tabs[6] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <?php 
                }
              ?>

              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[4] ? 'active' : '';?>" href="./account-settings.php">
                    <i class="fas fa-cog icons"></i>
                  Account Settings <?php echo $current_side_tab == $tabs[4] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>

            </ul>
            <div class="mt-auto  d-flex flex-column mx-3 mb-4">
              <!-- <div class="mb-2">
                Total log-in time: <span>03:25:00</span>
              </div> -->
              <button id="logout-btn-desktop" class="btn btn-danger">
                LOG OUT
              </button>
            </div>
          </div>
        </nav>
        <script src="<?php echo $level;?>/js/components/headers/support-signedin.js"></script>