<?php 

$level ="../../";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/support.css">
<link rel="stylesheet" href="../../css/headers/support-side-nav.css">
<link rel="stylesheet" href="../../css/support-table.css">
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
        $current_side_tab = "Messages";
        require_once dirname(__FILE__)."/$level/components/headers/support-side-nav.php"; 
    ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Messages</h1>
    </div>

    <!-- Tab Header -->
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">All</a>

            <a class="nav-item nav-link" id="nav-Active-tab" data-toggle="tab" href="#nav-Active" role="tab" aria-controls="nav-Active" aria-selected="false">Active</a>
            
            <a class="nav-item nav-link" id="nav-Bookmarked-tab" data-toggle="tab" href="#nav-Bookmarked" role="tab" aria-controls="nav-Bookmarked" aria-selected="false">Bookmarked</a>

            <a class="nav-item nav-link" id="nav-Archived-tab" data-toggle="tab" href="#nav-Archived" role="tab" aria-controls="nav-Archived" aria-selected="false">Archived</a>

        </div>
    </nav>


    <!-- Tab Header -->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-All" role="tabpanel" aria-labelledby="nav-All-tab">
            <?php
                $tableName = "all";
                $basicSearchId = "allSearch";
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

        <div class="tab-pane fade" id="nav-Active" role="tabpanel" aria-labelledby="nav-Active-tab">
            <?php
                $tableName = "active";
                $basicSearchId = "activeSearch";
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

        <div class="tab-pane fade" id="nav-Bookmarked" role="tabpanel" aria-labelledby="nav-Bookmarked-tab">
            <?php
                $tableName = "bookmarked";
                $basicSearchId = "bookmarkedSearch";
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

        <div class="tab-pane fade" id="nav-Archived" role="tabpanel" aria-labelledby="nav-Archived-tab">
            <?php
                $tableName = "archived";
                $basicSearchId = "archivedSearch";
                include "$level/components/UX/support-table.php";

                // Reset Values after to prepare for the next iteration
                $tableName = null;
                $basicSearchId = null;
            ?>
        </div>

    </div>

    <!-- <form action="./messages.php" target="_blank" method="get">
        <label for="fname">First name:</label><br>
        <input type="text" id="fname" name="fname"><br>
        <label for="lname">Last name:</label><br>
        <input type="text" id="lname" name="lname">
        <button type="submit">sfsdf</button>
    </form> -->
</main>






    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__)."/$level/components/foot-meta.php"; ?>
<!-- Custom JS Scripts Below -->
    <script>

    </script>
</body>
</html>