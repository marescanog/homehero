<?php $hasHeader = "header";
    $headerLink_Selected = $headerLink_Selected ?? 0;
?>
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top w-100 p-0 pt-2">
    <div class="flex flex-column w-100 ">
        <div class="d-flex justify-content-between px-3 nav-container"  id="header-mobile-container">
            <a id="header-logo-mobile" class="navbar-brand custom-a" href="<?php echo $level;?>/index.php">
                    <img src="<?php echo $level;?>/images/logo/worker-header-sm.jpg" class="header-img" alt="Home Hero Logo" >
            </a>
            <div class="d-flex">
                <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="header-btn-mobile" class="nav-item dropdown">
                    <div class="nav-link dropdown-toggle d-flex justify-content-center align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="circle mr-3 p-2 color-link"><?php echo $initials; ?></div>
                                <?php echo $fistName;?>
            </div>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo $level;?>/pages/worker/profile.php">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a id="logout-link-mobile" class="dropdown-item" >Log out</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex px-3 py-0 nav-container align-items-center" id="header-desktop-content">
            <a      class="navbar-brand custom-a" href="<?php echo $level;?>/index.php">
                    <img src="<?php echo $level;?>/images/logo/worker-header-sm.jpg" class="header-img" alt="Home Hero Logo" id="header-logo-desktop">
            </a>
            <div id="header-menu-links">
                <div class="collapse navbar-collapse pr-3 " id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto align-items-center">

                        <li class="nav-item mr-lg-3 cust-width mt-3 mt-lg-0">
                            <a class="nav-link p-0 custom-a 
                                <?php echo $headerLink_Selected == 0 ? " selected" : "";
                                ?>
                                " href="<?php echo $level;?>/pages/worker/home.php"
                                >Postings</a>
                            <div class="custom-underline                                 
                                <?php echo $headerLink_Selected == 0 ? " show" : "";?>">
                            </div>
                        </li>

                        <!--<li class="nav-item mr-lg-2 cust-width mt-3 mt-lg-0">
                            <a class="nav-link p-0 custom-a 
                                <?php echo $headerLink_Selected == 1 ? " selected" : "";
                                    ?>
                            " href="<?php echo $level;?>/pages/worker/messages.php"
                                >Messages</a>
                            <div class="custom-underline                                 
                                <?php echo $headerLink_Selected == 1 ? " show" : "";?>">
                            </div>
                        </li>-->

                        <!--<li class="nav-item mr-lg-3 cust-width mt-3 mt-lg-0">
                            <a class="nav-link p-0 custom-a
                                <?php echo $headerLink_Selected == 2 ? " selected" : "";
                                ?>
                            " href="<?php echo $level;?>/pages/worker/services.php">Services</a>
                            <div class="custom-underline                                 
                                <?php echo $headerLink_Selected == 2 ? " show" : "";?>">
                            </div>
                        </li>-->

                        <!--<li class="nav-item mr-lg-3 cust-width mt-3 mt-lg-0">
                            <a class="nav-link p-0 custom-a 
                                <?php echo $headerLink_Selected == 3 ? " selected" : "";
                                ?>
                            " href="<?php echo $level;?>/pages/worker/calendar.php">Calendar</a>
                            <div class="custom-underline                                 
                                <?php echo $headerLink_Selected == 3 ? " show" : "";?>">
                            </div>
                        </li>-->

                        <li class="nav-item mr-lg-3 cust-width mt-3 mb-3 mt-lg-0 mb-lg-0">
                            <a class="nav-link p-0 custom-a 
                                <?php echo $headerLink_Selected == 4 ? " selected" : "";
                                ?>
                            " href="<?php echo $level;?>/pages/worker/profile.php">Profile</a>
                            <div class="custom-underline                                 
                                <?php echo $headerLink_Selected == 4 ? " show" : "";?>">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="header-btn-desktop" class="nav-item dropdown tray-margin">
                <a class="nav-link dropdown-toggle d-flex justify-content-center align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="circle mr-3 p-2"><?php echo $initials; ?></div>
                            <?php echo $fistName;?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo $level;?>/pages/worker/profile.php">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a id="logout-link-desktop" class="dropdown-item" >Log out</a>
                </div>
            </div>
        </div>

        <div class="w-100 m-0 ">
            <hr class="w-100 m-0 mt-lg-2 hr-yellow" />
        </div>
    </div>
</nav>
<script src="<?php echo $level;?>/js/components/loadModal.js"></script>
<script src="<?php echo $level;?>/js/components/headers/worker-signedin.js"></script>

