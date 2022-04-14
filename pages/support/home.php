<?php 

session_start();
if(!isset($_SESSION["token_support"])){
    header("Location: ../../");
    exit();
}

$level ="../..";

require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/pages/support/sup-home.css">
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
        $current_side_tab = "Dashboard";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <?php
       // var_dump($_SESSION);
    ?>
        <!-- Cards -->
        <div class="row mb-4">
            <div class="col-12 col-lg-3">
                <div class="card">
                <div class="card-body purple">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="p-0 cont-value">
                            <?php
                                echo isset($_POST['new_ticket_total']) ? $_POST['new_ticket_total'] : '0';
                            ?>
                        </div>
                        <div class="p-0 cont-icon">
                            <i class="fas fa-ticket-alt card-icon purple-icon"></i>
                        </div>
                    </div>
                    <h5 class="card-title card-header-format">New Tickets</h5>
                </div>
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <div class="card ">
                <div class="card-body blue">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="p-0 cont-value">
                            <?php
                                echo isset($_POST['ongoing_ticket_total']) ? $_POST['ongoing_ticket_total'] : '0';
                            ?>
                        </div>
                        <div class="p-0 cont-icon">
                            <i class="fas fa-list-alt card-icon blue-icon"></i>
                        </div>
                    </div>
                    <h5 class="card-title card-header-format">Ongoing Tickets</h5>
                </div>
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <div class="card">
                <div class="card-body green">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="p-0 cont-value">
                            <?php
                                echo isset($_POST['resolved_ticket_total']) ? $_POST['resolved_ticket_total'] : '0';
                            ?>
                        </div>
                        <div class="p-0 cont-icon">
                            <i class="fas fa-calendar-check card-icon green-icon"></i>
                        </div>
                    </div>
                    <h5 class="card-title card-header-format">Resolved Tickets</h5>
                </div>
                </div>
            </div>
        </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Anouncements</h1> 
    </div>
    <div>
        <?php 
            if(isset($_POST['anouncements'])) {
                for($x = 0; $x < count($_POST['anouncements']); $x++){
        ?>
        <ul>
            <?php 
                echo "<li>".$_POST['anouncements'][$x]."</li>";
            ?>
        </ul>
        <?php
                }
            } else {
                echo 'No Anouncements';
            }
        ?>
    </div>
    </main>





    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
</body>
</html>