<?php

session_start();
if (!isset($_SESSION["token"])) {
    header("Location: ../../");
    exit();
}

$level = "../..";
$fistName = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : "Guest";
$initials = isset($_SESSION["initials"]) ? $_SESSION["initials"] : "GU";

require_once("$level/db/conn.php");

//PART 1: PERSONAL INFO
// CREATE query
$sql = "SELECT hh.first_name,hh.last_name,hh.phone_no
        FROM hh_user hh
        WHERE hh.user_id=:id AND hh.user_type_id=2";

// Prepare statement
$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $personalInfo = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $lname = $personalInfo[0]['last_name'];
    $fname = $personalInfo[0]['first_name'];
    $mobile = $personalInfo[0]['phone_no'];
}

$stmt = null;

//PART 2: SKILLSET
// CREATE query
$sql = "SELECT skill
        FROM skillset
        WHERE worker_id=:id AND is_deleted=0";

// Prepare statement
$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $skills = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $skillset = [];
    for ($x = 0; $x < count($skills); $x++) {
        array_push($skillset, $skills[$x]['skill']);
    }
}

$stmt = null;
//PART 3: CITY PREFERENCES
// CREATE query
$sql = "SELECT city_id
        FROM city_preference
        WHERE worker_id=:id";

// Prepare statement
$stmt =  $conn->prepare($sql);
$result = "";

// Only fetch if prepare succeeded
if ($stmt !== false) {
    $stmt->bindparam(':id', $_SESSION['id']);
    $stmt->execute();
    $cities = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $cityset = [];
    for ($x = 0; $x < count($cities); $x++) {
        array_push($cityset, $cities[$x]['city_id']);
    }
}

$stmt = null;

require_once dirname(__FILE__) . "/$level/components/head-meta.php";

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../../css/headers/header-worker.css">
<link rel="stylesheet" href="../../css/headers/worker-side-nav.css">
<script src="https://kit.fontawesome.com/d10ff4ba99.js" crossorigin="anonymous"></script>
<script src="./worker-actions/worker-actions.js"></script>
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
        $current_nav_side_tab = "Settings";
        require_once dirname(__FILE__) . "/$level/components/headers/worker-side-nav.php";
        ?>
        <<div class="col-md-10">
            <div class="container container-full  w-100 m-lg-0 p-0 min-height ml-3">
                <main role="main" class="col-md-9 col-lg-10 pt-3 px-4 " style="margin-left:30%; margin-right:40%">

                    <h1 class="text-center">Edit Profile</h1>
                    <h5 class="jumbotron-h1 text-center mt-lg-3 mt-0 mt-md-3 mt-lg-0">
                        Click on any of the menu items below to edit its relevent information.
                    </h5>
                    <br>

                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <span style="font-size:larger"><b>Edit personal information</b></span>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">    
                                <form id="personalInfoForm">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputFname">First name</label>
                                                <input type="text" class="form-control" id="inputFName" value=<?php echo $fname; ?>>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputLName">Last name</label>
                                                <input type="text" class="form-control" id="inputLName" value=<?php echo $lname; ?>>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputMobile">Mobile number</label>
                                                <input type="text" class="form-control" id="inputMobile" value=<?php echo $mobile; ?>>
                                            </div>
                                        </div>
                                        <button type="button" onclick="updatePersonalInfo()" class="btn btn-primary">Update personal information</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <span style="font-size:larger"><b>Edit skillset</b></span>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <p>Select from the following project categories that you are comfortable in working on.</p>
                                    <form id="skillSetForm">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="skill1" value="1" type="checkbox" id="skillcheck1"
                                                        <?php if (in_array('1', $skillset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="skillcheck1">
                                                    Plumbing
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="skill2" value="2" type="checkbox" id="skillcheck2"
                                                        <?php if (in_array('2', $skillset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="skillcheck2">
                                                    Carpentry
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="skill3" value="3" type="checkbox" id="skillcheck3"
                                                        <?php if (in_array('3', $skillset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="skillcheck3">
                                                    Electrical
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="skill4" value="4" type="checkbox" id="skillcheck4"
                                                        <?php if (in_array('4', $skillset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="skillcheck4">
                                                    Gardening
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="skill5" value="5" type="checkbox" id="skillcheck5"
                                                        <?php if (in_array('5', $skillset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="skillcheck5">
                                                    Home Improvement
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="skill6" value="6" type="checkbox" id="skillcheck6"
                                                        <?php if (in_array('6', $skillset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="skillcheck6">
                                                    Cleaning
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="updateSkillset()" class="btn btn-primary">Save skillset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <span style="font-size:larger"><b>Edit preferred work areas</b></span>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                <p>Select from the following cities where you are comfortable to work in.</p>
                                    <form id="citysetForm">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city1" value="1" type="checkbox" id="cityCheck1"
                                                        <?php if (in_array(1, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck1">
                                                    Bantayan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city2" value="2" type="checkbox" id="cityCheck2"
                                                        <?php if (in_array(2, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck2">
                                                    Carcar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city3" value="3" type="checkbox" id="cityCheck3"
                                                        <?php if (in_array(3, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck3">
                                                    Cebu City
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city4" value="4" type="checkbox" id="cityCheck4"
                                                        <?php if (in_array(4, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck4">
                                                    Daanbantayan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city5" value="5" type="checkbox" id="cityCheck5"
                                                        <?php if (in_array(5, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck5">
                                                    Danao
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city6" value="6" type="checkbox" id="cityCheck6"
                                                        <?php if (in_array(6, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck6">
                                                    Lapu-lapu
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city7" value="7" type="checkbox" id="cityCheck7"
                                                        <?php if (in_array(7, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck7">
                                                    Liloan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city8" value="8" type="checkbox" id="cityCheck8"
                                                        <?php if (in_array(8, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck8">
                                                    Mandaue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city9" value="9" type="checkbox" id="cityCheck9"
                                                        <?php if (in_array(9, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck9">
                                                    Minglanilla
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city10" value="10" type="checkbox" id="cityCheck10"
                                                        <?php if (in_array(10, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck10">
                                                    Naga
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city11" value="11" type="checkbox" id="cityCheck11"
                                                        <?php if (in_array(11, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck11">
                                                    Talisay
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="city12" value="12" type="checkbox" id="cityCheck12"
                                                        <?php if (in_array(12, $cityset)) echo "checked"; ?>>
                                                    <label class=" form-check-label" for="cityCheck12">
                                                    Toledo
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="updateCityPreferences()" class="btn btn-primary">Save city preferences</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span style="font-size:larger"><b>Change password</b></span>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                <div class="card-body">
                                <form id="passwordForm">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="oldPass">Current password</label>
                                                <input type="password" class="form-control" id="oldPass" placeholder="Enter current password">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="newPass">New password</label>
                                                <input type="password" class="form-control" id="newPass" placeholder="At least 8 characters">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="confirmPass">Confirm new password</label>
                                                <input type="password" class="form-control" id="confirmPass" placeholder="At least 8 characters">
                                            </div>
                                        </div>
                                        <button type="button" onclick="updatePassword()" class="btn btn-primary">Update password</button>
                                    </form>
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