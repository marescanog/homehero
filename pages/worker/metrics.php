<?php

session_start();
if (!isset($_SESSION["token"])) {
    header("Location: ../../");
    exit();
}

$level = "../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";


require_once dirname(__FILE__) . "/$level/components/head-meta.php";

//SQL Queries
//1-4. Job status by worker (today union 7 days union 30 days union total)

//5-8. Ratings 








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
    $headerLink_Selected = 4;
    require_once dirname(__FILE__) . "/$level/components/headers/worker-signed-in.php";
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
        <!-- === Your Custom Page Content Goes Here below here === -->

        <?php
        $current_nav_side_tab = "Metrics";
        require_once dirname(__FILE__) . "/$level/components/headers/worker-side-nav.php";
        ?>
        <div class="col-md-10">
            <div class="container container-full  w-100 m-lg-0 p-0 min-height ml-3">
                <main role="main" class="col-md-9 col-lg-10 pt-3 px-4 " style="margin-left:30%; margin-right:40%">

                    <h1 class="text-center">Metrics</h1>
                    <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                        View your work statistics and ratings to see your performance.
                    </h5>

                    <hr color="yellow" style="height:5px">

                    <h4 class="mb-4 mt-2 mx-2">Job Summary</h4>
                    <table class="table table-bordered">

                        <thead class="text-center thead-light">
                            <th style="width:35%">Statistics</th>
                            <th style="width:15%">Today</th>
                            <th style="width:15%">Past 7 Days</th>
                            <th style="width:15%">Past 30 Days</th>
                            <th style="width:20%">Overall Total</th>
                        </thead>

                        <tbody>
                            <tr>
                                <th>Jobs accepted</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-size:large"></td>
                            </tr>

                            <tr>
                                <th>Jobs in progress</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-size:large"></td>
                            </tr>

                            <tr>
                                <th>Jobs cancelled</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-size:large"></td>
                            </tr>


                            <tr>
                                <th>Jobs completed</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-size:large"></td>
                            </tr>


                            <tr>
                                <th>Bills fulfilled</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-size:large"></td>
                            </tr>

                            <tr>
                                <th>Revenue generated</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-size:large"></td>
                            </tr>

                        </tbody>
                    </table>

                    <hr color="yellow" style="height:5px">

                    <h4 class="mb-4 mt-2 mx-2">Rating Summary</h4>
                    <table class="table table-bordered">

                        <thead class="text-center thead-light">
                            <th style="width:35%"></th>
                            <th style="width:15%">Today</th>
                            <th style="width:15%">Avg. for past 1-30 days</th>
                            <th style="width:15%">Avg. for past 31-60 days</th>
                            <th style="width:20%">Overall Average</th>
                        </thead>

                        <tbody>
                            <tr>
                                <th>Overall quality</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Professionalism</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Reliability</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>


                            <tr>
                                <th>Punctuality</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>


                            <tr>
                                <th>General Average</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="font-size:large"></th>
                            </tr>


                        </tbody>
                    </table>

                    <hr color="yellow" style="height:5px">

                    <div class="container">
                        <div class="row">
                        <div class="col-md-6">

                            <h4 class="mb-4 mt-2 mx-2">Jobs completed by category</h4>
                            <table class="table table-bordered">

                                <thead class="text-center thead-light">
                                    <th style="width:60%">Category</th>
                                    <th style="width:40%">Completed</th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="col-md-6">

                            <h4 class="mb-4 mt-2 mx-2">Jobs completed by location</h4>
                            <table class="table table-bordered">

                                <thead class="text-center thead-light">
                                    <th style="width:60%">Location/City</th>
                                    <th style="width:40%">Completed</th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Collapsible Group Item #1
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    Some placeholder content for the first accordion panel. This panel is shown by default, thanks to the <code>.show</code> class.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Collapsible Group Item #2
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    Some placeholder content for the second accordion panel. This panel is hidden by default.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Collapsible Group Item #3
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default.
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- obtaining worker personal info -->


                    <!-- forms -->
                    <!-- 
    personal info - f name, l name, phone no
    skillset - skills (6)
    city - cities (12)
    password - old, new, confirm
-->

                    <!-- validations -->


                    <!-- submission -->








                </main>
            </div>
        </div>


        <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
    <?php require_once dirname(__FILE__) . "/$level/components/foot-meta.php"; ?>
    <!-- Custom JS Scripts Below -->
    <!-- <script src="../../js/pages/user-home.js"></script> -->
    <script>



    </script>
</body>

</html>