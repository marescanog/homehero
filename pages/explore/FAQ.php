<?php 

session_start();
// if(isset($_SESSION["token"])){
//     header("Location: ./pages/homeowner/home.php");
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
<link rel="stylesheet" href="../../css/pages/FAQ.css">
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



    <div style="min-height:70vh">
    <div class="w-100 h-100 d-flex">
    <div class="container">
  <ul class="acc">
        <h1 class="text-center pb-5">F.A.Q.s</h1>
    <li>
      <button class="acc_ctrl"><h2>WHAT CITIES IS HOMEHERO SERVING?</h2></button>
      <div class="acc_panel">
        <p>Homehero is currently limited to the independent cities, component cities and some municipalities based on population. The information was based on the details of  in-philippines.com at https://www.in-philippines.com/visayas/cebu-province/cebu-province-cities-and-municipalities/.
            In the future, it is possible that home hero will expand to more regions in cebu depending on the popularity of the app.
        </p>
      </div>
    </li>
    <li>
      <button class="acc_ctrl"><h2>WHAT SERVICES ARE OFFERED?</h2></button>
      <div class="acc_panel">
        <p>Homehero is limited to 6 different categories namely: Plumbing, Electrical, Gardening, Carpentry, Home Improvement and Cleaning. In the future, the app may expand to other services other than trade jobs.</p>
      </div>
    </li>
    <li>
      <button class="acc_ctrl"><h2>HOW CAN I BOOK A WORKER?</h2></button>
      <div class="acc_panel">
        <p>A worker can be booked in the app through a project post. Create a project and add your address. After placing the details, you can submit your post to the postsfeed where it can be viewed by available workers. You can also take the proactive approach and browse workers to notify them about your project. A worker will then accept or decline your job post. Once a worker accepts your project post, he/she will be booked to your project. </p>
      </div>
    </li>
  </ul>
</div>
    </div>
        

        </div>



      

<?php 
    require_once dirname(__FILE__)."/$level/components/footer.php"; 
?>



    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
    <!-- <script src="../../js/pages/landing.js"></script> -->
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->

</body>
</html>
<script>
    $(function() {
  $('.acc_ctrl').on('click', function(e) {
    e.preventDefault();
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $(this).next()
      .stop()
      .slideUp(300);
    } else {
      $(this).addClass('active');
      $(this).next()
      .stop()
      .slideDown(300);
    }
  });
});
</script>