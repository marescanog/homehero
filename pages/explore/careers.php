<?php 

session_start();
// if(isset($_SESSION["token"])){
//     header("Location: ./pages/homeowner/home.php");
//     exit();
// }

$level ="../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- Add your custom CSS below -->
<link rel="stylesheet" href="../../css/headers/user.css">
<link rel="stylesheet" href="../../css/landing.css">
<link rel="stylesheet" href="../../css/footer.css">
<!-- Add your custom CSS above -->
<style>
    .jumbotron {
    background-image: url("../../images/pages/careers/jumbotron.png");
    background-size: cover;
    background-position: center top;
    background-repeat: no-repeat;
    }
</style>
</head>
 <body class="container-fluid m-0 p-0 main-container">  
    <!-- Add your Header NavBar here-->
    <?php 
        // require_once dirname(__FILE__)."/$level/components/headers/user.php";
        if(isset($_SESSION["token"])){
            require_once dirname(__FILE__)."/$level/components/headers/ho-signed-in.php";
        } else {
            require_once dirname(__FILE__)."/$level/components/headers/user.php";
        }
    ?>
    <div class="<?php echo $hasHeader ?? ''; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <!-- =============================================== -->
    <!-- JUMBOTRON -->
    <!-- =============================================== -->
    <div class="container">
    <div class="pt-5 pb-5">
        <h1>Careers</h1>
        <h3 class="mt-3 pb-3">Want to be part of the HomeHero Support team?</h3>
        <div class="jumbotron jumbotron-fluid">
        <div class="container">
        </div>
        </div>
        <h3>Find the right career option for you.</h3>
        <div style="max-width:650px;">
            <p class="pt-3">Homehero has a dedicated support center that is able to answer the tickets of customers. We operate by communicating with our customers through phone and e-mail. We are able to provide a variety of support such as billing issues, disputes and registration requests.</p>
            <p class="pt-3">To Apply as a HomeHero support agent, send your resume over to homehero@support.com.</p>
        </div>
      
        </div>
    </div>

      

<?php 
    require_once dirname(__FILE__)."/$level/components/footer.php"; 
?>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script src="./js/pages/landing.js"></script>
</body>
</html>