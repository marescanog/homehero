<?php 
session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}
if(!isset($_SESSION["role"]) || ($_SESSION["role"]!=4 && $_SESSION["role"]!=7 && $_SESSION["role"]!=6 && $_SESSION["role"]!=8)){
    header("Location: ../support/home.php");
    exit();
}

$level ="../../";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<script src="<?php echo $level;?>/js/components/loadModal.js"></script>
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body class="container-fluid m-0 p-0">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/support.php"; 
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <?php
        $current_side_tab = "Permissions";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Permissions</h1>
    </div>
    <div style="width: 30rem;">
        <p><i>Manage your request codes and reset permissions. You can generate a new permission code or request a code from your manager.</i></p>
    </div>

    <div class="card mb-4 ml-2 mt-3" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>My Permission Codes</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Transfer</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input readonly type="text" class="form-control" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <!-- <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Transfer</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input readonly type="text" class="form-control" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button id="btn_gen_transfer" data-toggle="modal" data-target="#modal" class="btn btn-sm btn-outline-secondary" type="button">Generate New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul> -->
    </div>

    <div class="card mb-4 ml-2" style="width: 30rem;">
        <div class="card-header text-muted">
            <b>Manager Permission Codes</b>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-4 border-right ticket-title">Transfer</div>
                    <div class="col-8 col-lg-8 align-items-center"> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-key"></i></span>
                            </div>
                            <input readonly type="text" class="form-control" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-secondary" type="button">Request New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

</main>
    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
<script src="../../js/pages/sup-permissions.js"></script>

</body>
</html>