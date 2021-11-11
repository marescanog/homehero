
<?php
    $current_side_tab = isset($current_side_tab) ? $current_side_tab : 'Dashboard';
    $tabs=['Dashboard', 'My Tickets', 'All Tickets', 'Messages', 'Account Settings'];
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
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[1] ? 'active' : '';?>" href="./my-tickets.php">
                  <i class="fas fa-clipboard-check icons"></i>
                  My Tickets <?php echo $current_side_tab == $tabs[1] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[2] ? 'active' : '';?>" href="./all-tickets.php">
                    <i class="fas fa-clipboard-list icons"></i>
                  All Tickets <?php echo $current_side_tab == $tabs[2] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[3] ? 'active' : '';?>" href="./messages.php">
                    <i class="fas fa-envelope-open-text icons"></i>
                  Messages <?php echo $current_side_tab == $tabs[3] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_side_tab == $tabs[4] ? 'active' : '';?>" href="./account-settings.php">
                    <i class="fas fa-cog icons"></i>
                  Account Settings <?php echo $current_side_tab == $tabs[4] ? "<span class='sr-only'>(current)</span>" : '';?>
                </a>
              </li>
            </ul>
            <div class="mt-auto  d-flex flex-column mx-3 mb-4">
              <div class="mb-2">
                Total log-in time: <span>03:25:00</span>
              </div>
              <button class="btn btn-danger">
                LOG OUT
              </button>
            </div>
          </div>
        </nav>