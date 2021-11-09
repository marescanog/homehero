<?php 

$level ="..";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->


<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body class="container-fluid m-0 p-0 main-container">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__).'/../components/header.php'; 
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->

    <div class="container">
        <h1>marvie</h1>

        <div class="container">
            <h3>Testing UI links</h3>
            <h2>Home Owner</h2>
            <ul>
                <li><a href="./homeowner/home.php">Home</a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
            </ul>
            <h2>Worker</h2>
            <ul>
                <li><a href="./worker/home.php">Home</a></li>
                <li><a href="./worker/landing.php">Landing</a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
            </ul>
            <h2>Support</h2>
            <ul>
                <li><a href="./support/home.php">Home</a> </li>
                <li><a href="./support/index.php">Signup</a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
                <li><a href=""></a></li>
            </ul>
        </div>


        <h3>testing get request</h3>
        <button class="btn btn-secondary my-2">
            TEST
        </button>
        <div class="container">
            <div id="users">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- === Your Custom Page Content Goes Here above here === -->
    </div>
<?php require_once dirname(__FILE__).'/../components/foot-meta.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Custom JS Scripts Below -->
    <script>
    var buttonDesktop = document.getElementById("header-btn-desktop");

    // Set events for elements
    buttonDesktop.addEventListener("click", ()=>{
        $.ajax({
        type: 'GET',
        url: 'http://localhost/slim3homeheroapi/public/create-guest',
        success: response => {
            // convert response to javascript object
            let data = JSON.parse(response);

            // access the javascript object's data which is under "response"
            // The datatype for this is an array
            let arr = data.response.data;

            // create an empty object
            let obj = {};

            // Convert the array into a javascript object
             arr.forEach((value, key) => {
                 let newObj = {
                    id : value['0'],
                    type: value['1'],
                    status: value['2'],
                    fname: value['3'],
                    lname: value['4'],
                    phone: value['5'],
                    pass: value['6']
                 }
                 obj[key] = newObj;
             });

            $('#users').load("../components/cards/a.php", obj)
        },
        error: response => {
            $('#users').load("../components/cards/b.php")
        }
    });

    });

    $.ajax({
        type: 'GET',
        url: 'http://localhost/slim3homeheroapi/public/create-guest',
        success: response => {
            // convert response to javascript object
            let data = JSON.parse(response);

            // access the javascript object's data which is under "response"
            // The datatype for this is an array
            let arr = data.response.data;

            // create an empty object
            let obj = {};

            // Convert the array into a javascript object
             arr.forEach((value, key) => {
                 let newObj = {
                    id : value['0'],
                    type: value['1'],
                    status: value['2'],
                    fname: value['3'],
                    lname: value['4'],
                    phone: value['5'],
                    pass: value['6']
                 }
                 obj[key] = newObj;
             });

             // The reason why there is a 414 error is because when we pass "response" directly
             // as a parameter, it is a string
             // thus, the POST method attaches it to the end of the URL similar to a GET request
             // By converting it to a javascript object, the data is passed through
             // the header and not through the URL (URLs have a maxlength)
            $('#users').load("../components/cards/a.php", obj)
        },
        error: response => {
            $('#users').load("../components/cards/b.php")
        }
    });


    </script>
</body>
</html>