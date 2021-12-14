
<?php
    $hs = $headerLink_Selected ?? 0;
    $tabs=[
      ['Opportunities', 'Interested Clients', 'Ongoing Projects', 'Past Projects'],
      //['Messages', 'Unread', 'Archived', 'Sent Quotes', 'Support'],
      ['Services Offered', 'Service Area', 'Weekly Activity', 'Insights','Invoices Sent'],
      //['Overview', 'Edit Day'],
      ['Your Profile', 'Reviews', 'Settings']
    ];
    $hasExtraFilters = isset($hasExtraFilters) ? $hasExtraFilters : false;
    $extraFilters = isset($extraFilters) ? $extraFilters : [];
    $current_nav_side_tab = isset($current_nav_side_tab) ? $current_nav_side_tab : $tabs[$hs][0];

    // for $iconsSideNav variable
    require_once dirname(__FILE__)."/worker-side-nav-icons-list.php";
?>
<div class="row">
  <!-- Desktop -->
    <nav class="col-md-2 d-none d-lg-block bg-white sidebar">
      <div class="sidebar-sticky d-flex flex-column">
        <ul class="nav flex-column">
          <?php 
            for($x=0; $x< count($tabs[$hs]); $x++){
          ?>
            <li class="nav-item">
              <a id="<?php echo "side-nav-".$x;?>"
                class="nav-link <?php echo $current_nav_side_tab == $tabs[$hs][$x] ? 'active' : '';?>" href="./home.php">
                <?php
                  echo $iconsSideNav[$hs][$x];
                  echo " ".$tabs[$hs][$x];
                  echo $current_nav_side_tab == $tabs[$hs][0] ? "<span class='sr-only'>(current)</span>" : '';
                ?>
              </a>
            </li>
          <?php
            }
          ?>
        </ul>
      </div>
    </nav>
    <!-- MOBILE -->
    <!-- <div class="container-full">
      <nav id="top-nav" class="d-lg-none topbar m-0 p-0 pl-1 pl-md-4">
        <ul class="d-flex m-0 p-0">
          <li class="ml-1 <?php echo $current_side_tab == $tabs[0] ? ' selected' : '';?>">Opportunities</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[1] ? ' selected' : '';?>">Interested Clients</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[2] ? ' selected' : '';?>">Ongoing Projects</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[3] ? ' selected' : '';?>">Past Projects</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[3] ? ' selected' : '';?>">Past Projects</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[3] ? ' selected' : '';?>">Past Projects</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[3] ? ' selected' : '';?>">Past Projects</li>
          <li class="ml-1 <?php echo $current_side_tab == $tabs[3] ? ' selected' : '';?>">Past Projects</li>
        </ul>
      </nav>
    </div> -->
    <!-- Mobile -->

</div>
