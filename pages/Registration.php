<?php 

$level ="..";
require_once dirname(__FILE__).'/../components/head-meta.php'; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="/../css/headers/user.css">

<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body>  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/user.php"; 
    ?>
    <div style="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <div class="container">
        <h1>
            Worker Registration
        </h1>
    </div>



     
    






    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__).'/../components/foot-meta.php'; ?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
</body>
</html>