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


<div class="jumbotron container">
    <div class="pt-5 pb-5">
    <h1 >About Us</h1>
    <div style="max-width:650px;">
        <p style="text-indent: 50px;">HomeHero is an information management system project developed by a group of students from the University of San Carlos as a final project requirement for their CIS 2014 Informtion Management II class.</p>
        <p style="text-indent: 50px;">The main purpose of this web application is to connect homeowners and workers through job posts based on their needs and location. Furthermote, this system will be able to facilitate the booking of a project through a simple booking and notification system. Aditionally, a simple support system is added for the homeowners and workers where they will be able to submit suport tickets to the homehero support agents.</p>
    </div>
    <h2 >The Developers</h2>
        <ul style="list-style-type: none;">

            <li class="mt-4" style="max-width: 900px;">
                <div class="card" >
                    <div class="card-body">
                        <div style="width:200px; height:200px">
                            <img src="../../images/pages/about/marvie.jpg" alt="..." class="img-thumbnail">
                        </div>
                   
                        <h5 class="card-title">Marvie Gasataya</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Project Leader || Homeowner Module || UX Design || Lead Front End Developer</h6>
                        <p style="text-indent: 25px;">Marvie Gasataya graduated at the University of the Philippines with a bachelor of fine Arts Under Studio Arts. She participated in the DTI digital artisans Manila World Trace Center M.A.D.E. event and represented Cebu through her designs and show peice. This experience and knowledge of 3D-Printing, CNC Machine Milling, 3D modelling, UX/UI Design and Video Game Development lead her to an interest in the Computer Science & Programming path.</p>
                        <p style="text-indent: 25px;">She is currently a second year student at the University of San Carlos majoring in Computer Science and works as a React Native software developer at AppUp Tech Solutions Philippines.</p>
                        <a href="https://github.com/marescanog" class="card-link">Github Page</a>
                        <a href="https://marescanog.github.io/portfolio-gasataya/" class="card-link">Portfolio Page</a>
                        <a href="https://www.linkedin.com/in/marvie-maria-gasataya-268807165/" class="card-link">Linked In</a>
                    </div>
                </div>
            </li>

           
            <li class="mt-4" style="max-width: 900px;">
                <div class="card" >
                    <div class="card-body">
                        <div style="width:200px; height:200px">
                            <img src="..." alt="..." class="img-thumbnail">
                        </div>
                   
                        <h5 class="card-title">Wayne Dayata</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Group Member || Worker Module</h6>
                        <p style="text-indent: 25px;">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="card-link">Github Page</a>
                        <a href="#" class="card-link">Portfolio Page</a>
                    </div>
                </div>
            </li>


            <li class="mt-4" style="max-width: 900px;">
                <div class="card" >
                    <div class="card-body">
                        <div style="width:200px; height:200px">
                            <img src="..." alt="..." class="img-thumbnail">
                        </div>
                   
                        <h5 class="card-title">Ivana Leonado</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Group Member || Support Module</h6>
                        <p style="text-indent: 25px;">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="card-link">Github Page</a>
                        <a href="#" class="card-link">Portfolio Page</a>
                    </div>
                </div>
            </li>

            <li class="mt-4" style="max-width: 900px;">
                <div class="card" >
                    <div class="card-body">
                        <div style="width:200px; height:200px">
                            <img src="..." alt="..." class="img-thumbnail">
                        </div>
                   
                        <h5 class="card-title">Ixia Tan</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Group Member || Support Module</h6>
                        <p style="text-indent: 25px;">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="card-link">Github Page</a>
                        <a href="#" class="card-link">Portfolio Page</a>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>


      

<?php 
    require_once dirname(__FILE__)."/$level/components/footer.php"; 
?>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <!-- <script src="./js/pages/landing.js"></script> -->
</body>
</html>