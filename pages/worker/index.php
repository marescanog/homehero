<?php 

session_start();
if(isset($_SESSION["registration_token"]) && isset($_SESSION["hasRegistered"])){
    session_destroy(); //destroy entire session 
    session_start(); // start new session
} else{
    if(isset($_SESSION["token"])){
        if($_SESSION['userType'] == 2) {
            header("Location: ../../pages/worker/home.php");
            exit();
        } else {
            header("Location: ../../pages/homeowner/home.php");
            exit();
        }
    }
}
// else if(isset($_SESSION["token"]) && isset($_SESSION["user_type"])){
//     switch($_SESSION["user_type"]){
//         case 1:
//             header("Location: ../homeowner/home.php"); // homeowner dashboard
//             break;
//         case 2:
//             header("Location: ./home.php"); // worker dashboard
//             break;
//         case 3:
//         case 4:
//             header("Location: ../support/"); // support log-in portal
//             break;
//         default:
//             header("Location: ../../"); // default landing page
//     }
//     exit();
// }

$level ="../..";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 
?>
<!-- Add your custom CSS below -->
<link rel="stylesheet" href="../../css/headers/user.css">
<link rel="stylesheet" href="../../css/landing.css">
<link rel="stylesheet" href="../../css/footer.css">
<!-- Add your custom CSS above -->
</head>
 <body class="container-fluid m-0 p-0 main-container">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/worker.php"; 
    ?>
    <div class="<?php echo $hasHeader ?? ''; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <!-- =============================================== -->
    <!-- JUMBOTRON -->
    <!-- =============================================== -->
    <div class="jumbotron py-0 mb-auto d-flex justify-content-center flex-lg-end">
        <div class="d-lg-flex flex-lg-row justify-content-center jumbotron-container row">
            <div class="d-flex jumbotron-img-container col-12 col-lg-6 mt-auto">
                <div class="mx-auto m-lg-0 d-flex justify-content-center">
                    <!-- <img src="./images/pages/landing/Jumbotron_Image.jpg" 
                         srcset="./images/pages/landing/Jumbotron_Image.jpg 594w, 
                                 ./images/pages/landing/jumbo-320.jpg 320w,
                                 ./images/pages/landing/jumbo-280.jpg 280w"
                         sizes="(max-width: 280px) 280w, 
                                (max-width: 320px) 320w
                                594w"
                         alt="A homehero worker holding a drill" 
                         class="img-fluid jumbotron-img" > -->
                </div>
            </div>

            <div class="jumbotron-card mt-5 pt-5 col-12 col-lg-6" > 
                <div class="card w-lg-75 ml-lg-auto mr-lg-3 mb-lg-5 jumbotron-card-body">
                    <div class="card-body">
                            <h3>Find new work in Mandaue</h3>
             
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- =============================================== -->
    <!-- HOW IT WORKS -->
    <!-- =============================================== -->
    <div class="feature-point-container">
        <div class="container featured-point_content-wrapper">
            <div class="container pt-5">
                <h2 class="gray-font feature-point-headline text-center">How it works</h2>

                <div class="row d-flex justify-content-around mt-5 mb-5 mb-lg-0"> 


                    <!-- Feature 1 -->
                    <div class="col col-lg-3 d-flex flex-column align-items-center pb-5">
                        <div>
                            <?php
                                // include dirname(__FILE__).'/images/pages/landing/post_tasks.svg'; 
                            ?>
                        </div>
                        <div>
                            <h4 class="gray-font text-center">Post Tasks</h4>
                            <p>Delegate projects that need to be done by posting your needs</p>
                        </div>
                    </div> 

                    
                    <!-- Feature 2 -->
                     <div class="col col-lg-3 d-flex flex-column align-items-center">
                        <div>
                            <?php
                                // include dirname(__FILE__).'/images/pages/landing/review_offers.svg'; 
                            ?>
                        </div>
                        <div>
                            <h4 class="gray-font text-center">Review Offers</h4>
                            <p>Check offers and hire the right hero for your tasks</p>
                        </div>
                    </div> 

                    
                    <!-- Feature 1 -->
                    <div class="col col-lg-3 d-flex flex-column align-items-center">
                        <div >
                            <!-- <img src="./images/pages/landing/taxi-surfing.png" alt="" class="img-fluid feature-max-img"> -->
                        </div>
                        <div>
                            <h4 class="gray-font text-center">Task Done!</h4>
                            <p>Sit back and relax as your hirehero gets the task done for you!</p>
                        </div>
                    </div> 


                </div>
            </div>
        </div>
    </div>

  
 
    <!-- =============================================== -->
    <!-- CTA BOTTOM  -->
    <!-- =============================================== -->
    <div class="CTA-container pb-5 pb-lg-0">
        <div class="container CTA-wrapper pt-5 ">
             <h2 class="text-center gray-font my-4">Ready to get your needs solved?</h2>
             <div class="container CTA-box-container">
                 <div class="row">
                     <div class="col-12 col-lg-6 CTA-box CTA-box-white ">
                         <div class="CTA-box-content-container mb-lg-3 mt-lg-3">
                            <div>
                                <!-- <img class="img-fluid" src="./images/pages/landing/CTABottomHomeowner.jpg" alt="Homeowner using the Homehero app"> -->
                            </div>
                            <div class="CTA-button-container">
                                <button class="btn btn-warning btn-lg text-white font-weight-bold w-100 mb-2">
                                    Sign Up
                                </button>
                                <p>Get your FREE online account and start looking for a Hero to help with your home.</p>
                            </div>
                         </div>
                     </div>
                     <div class="col-12 col-lg-6 CTA-box CTA-box-offwhite">
                        <div class="CTA-box-content-container mb-lg-3 mt-lg-3">
                            <div>
                                <!-- <img class="img-fluid" src="./images/pages/landing/CTABottomWorker.jpg" alt="Worker using the Homehero app"> -->
                            </div>
                            <div class="CTA-button-container">
                                <button class="btn btn-warning btn-lg text-white font-weight-bold w-100 mb-2"">
                                    Become a HomeHero
                                </button>
                                <p>Register as a HomeHero and easily be able to find and connect to clients.</p>
                            </div>
                        </div>
                    </div>
                 </div>
             </div>
        </div>
    </div>
      

<?php 
    require_once dirname(__FILE__)."/$level/./components/footer.php"; 
?>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <!-- <script src="./js/pages/landing.js"></script> -->
</body>
</html>