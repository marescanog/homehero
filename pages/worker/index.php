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
                    <img src="../../images/pages/landing/worker-jumbotron.jpg" 
                         alt="Workers" 
                         class="img-fluid jumbotron-img" >
                </div>
            </div>

            <div class="jumbotron-card mt-5 pt-5 col-12 col-lg-6" > 
                <div class="card w-lg-75 ml-lg-auto mr-lg-3 mb-lg-5 jumbotron-card-body">
                    <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h2 class="h1">Find new work in your city</h2>
                                <h6 class="h5 pt-2">Complete the 3-step registration process and get access to project offers. It's that simple!</h6>
                                <p class="mb-0 pb-0">You will need:</p>
                                <ul class="mt-0 pt-0">
                                    <li>A valid PH phone number</li>
                                    <li>Your NBI Clearance</li>
                                </ul>
                            </div>
                           
                            <div>
                                <button data-toggle="modal" data-target="#modal" id="button_worker_jombotron_register" class="btn-lg btn-warning text-white">
                                    <b>REGISTER</b>
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Replacement for the  popular posting and feature heroes-->
    <div style="background-color:#EDEDED; height: 50vh;" class="d-flex justify-content-center align-items-center">
        <div style="max-width:750px;">
            <h4 class="text-center"><i>Homehero is a web application that allows workers to find job posts from homeowners about different projects for their home. Workers will be able to select job posts based on their skillset such as Plumbing, Carpentry, Gardening and Home Improvement Services.</i></h4>
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
                                include dirname(__FILE__).'/../../images/pages/landing/post_tasks.svg'; 
                            ?>
                        </div>
                        <div>
                            <h4 class="gray-font text-center">Find Tasks</h4>
                            <p>Select projects based on your skill level and preferences.</p>
                        </div>
                    </div> 

                    
                    <!-- Feature 2 -->
                     <div class="col col-lg-3 d-flex flex-column align-items-center">
                        <div>
                            <?php
                                include dirname(__FILE__).'/../../images/pages/landing/review_offers.svg'; 
                            ?>
                        </div>
                        <div>
                            <h4 class="gray-font text-center">Accept Offers</h4>
                            <p>Check offers and accept the right one for your price</p>
                        </div>
                    </div> 

                    
                    <!-- Feature 1 -->
                    <div class="col col-lg-3 d-flex flex-column align-items-center">
                        <div >
                            <img src="../../images/pages/landing/taxi-884.png" alt="" class="img-fluid feature-max-img">
                        </div>
                        <div>
                            <h4 class="gray-font text-center">Work and Get Paid!</h4>
                            <p>Complete the task and get paid!</p>
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
                     <div class="col-12 col-lg-6 CTA-box CTA-box-offwhite">
                        <div class="CTA-box-content-container mb-lg-3 mt-lg-3">
                            <div>
                                <img class="img-fluid" src="../../images/pages/landing/CTABottomWorker.jpg" alt="Worker using the Homehero app">
                            </div>
                            <div class="CTA-button-container">
                                <button  data-toggle="modal" data-target="#modal" id="button_worker_cta_register" class="btn btn-warning btn-lg text-white font-weight-bold w-100 mb-2"">
                                    Become a HomeHero
                                </button>
                                <p>Register as a HomeHero and easily be able to find and connect to clients.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 CTA-box CTA-box-white ">
                         <div class="CTA-box-content-container mb-lg-3 mt-lg-3">
                            <div>
                                <img class="img-fluid" src="../../images/pages/landing/CTABottomHomeowner.jpg" alt="Homeowner using the Homehero app">
                            </div>
                            <div class="CTA-button-container">
                                <button  data-toggle="modal" data-target="#modal" id="button_homeowner_cta_register"class="btn btn-warning btn-lg text-white font-weight-bold w-100 mb-2">
                                    Sign Up
                                </button>
                                <p>Get your FREE online account and start looking for a Hero to help with your home.</p>
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
<script>

    const button_worker_jombotron_register = document.getElementById("button_worker_jombotron_register");
    const button_homeowner_cta_register = document.getElementById("button_homeowner_cta_register");
    const button_worker_cta_register = document.getElementById("button_worker_cta_register");
    // Assign the load modals for each appropriate button
    button_worker_jombotron_register.addEventListener("click", ()=>{
        loadModal("worker-signup",modalTypes,()=>{},getDocumentLevel());
    });
    button_homeowner_cta_register.addEventListener("click", ()=>{
        loadModal("signup",modalTypes,()=>{},getDocumentLevel());
    });

    button_worker_cta_register.addEventListener("click", ()=>{
        loadModal("worker-signup",modalTypes,()=>{},getDocumentLevel());
    });



</script>