<?php $hasHeader = "header";?>
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top w-100 p-0">
        <d class="flex flex-column w-100 pt-3 pt-lg-0">
        <div class="d-flex justify-content-between px-3 nav-container" id="header-mobile-container">
            <a class="navbar-brand custom-a" href="<?php echo $level;?>/index.php">
                    <img src="<?php echo $level;?>/images/logo/HH_Logo_Mobile.svg" class="rounded mr-2" alt="Home Hero Logo" id="header-logo-mobile">
            </a>
            <div>
                <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <button class="btn btn-warning text-light" id="header-btn-mobile"><b>LOG-IN</b></button>
            </div>
        </div>

        <div class="d-flex justify-content-between px-3 py-0 nav-container" id="header-desktop-content">
            <a class="navbar-brand" href="<?php echo $level;?>/">
                <img src="<?php echo $level;?>/images/logo/HH_Logo_Light.svg" class="rounded mr-2" alt="Home Hero Logo" id="header-logo-desktop">
            </a>
            <div id="header-menu-links">
                <div class="collapse navbar-collapse pr-3" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item pr-lg-5">
                            <a class="nav-link custom-a" href="<?php echo $level;?>/pages/Registration.php">FIND WORK</a>
                        </li>
                        <li class="nav-item pr-lg-5">
                            <a class="nav-link custom-a" href="<?php echo $level;?>/pages/Help.php">HELP</a>
                        </li>
                        <li class="nav-item pr-lg-5">
                            <a id="RU-signup" class="nav-link custom-a" type="button" data-toggle="modal" data-target="#modal">SIGN-UP</a>
                        </li>
                    </ul>
                </div>
                <button class="btn btn-warning text-light" id="header-btn-desktop"><b>LOG-IN</b></button>
            </div>
        </div>

        <div class="w-100 m-0 ">
            <hr class="w-100 m-0 mt-lg-2 hr-yellow" />
        </div>
        </d>
    </nav>