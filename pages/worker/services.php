<?php 

// session_start();
// if(!isset($_SESSION["token"])){
//     header("Location: ../../");
//     exit();
// }

$level ="../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";


require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/header-worker.css">
<link rel="stylesheet" href="../../css/headers/worker-side-nav.css">
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<!-- <link rel="stylesheet" href="../../css/pages/homeowner/homeowner-create-project.css"> -->
<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body class="container-fluid m-0 p-0  w-100 bg-light">  
    <!-- Add your Header NavBar here-->
    <?php 
        $headerLink_Selected = 2;
        require_once dirname(__FILE__)."/$level/components/headers/worker-signed-in.php"; 
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

<?php
    $current_nav_side_tab = "Services Offered";
    require_once dirname(__FILE__)."/$level/components/headers/worker-side-nav.php"; 
?>
<div class="container container-full  w-100 m-lg-0 p-0 min-height">
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 ">

<h1>Services</h1>













    </main>
</div>


    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <!-- <script src="../../js/pages/user-home.js"></script> -->
    <script>

   

    </script>
</body>
</html>