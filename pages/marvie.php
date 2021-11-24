<?php 

$level ="..";
require_once dirname(__FILE__)."/$level/components/head-meta.php"; 

?>
<!-- === Link your custom CSS pages below here ===-->
<link rel="stylesheet" href="../css/headers/user.css">

<!-- === Link your custom CSS  pages above here ===-->
</head>
 <body class="container-fluid m-0 p-0 main-container">  
    <!-- Add your Header NavBar here-->
    <?php 
        require_once dirname(__FILE__)."/$level/components/headers/user.php"; 
    ?>
    <div class="<?php echo $hasHeader ?? ""; ?>">
    <!-- === Your Custom Page Content Goes Here below here === -->
    <?php
        //putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\\users\\asus\\Downloads\\google-credentials.json'); // DEV
        putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\\users\\asus\\Downloads\\google-credentials.json'); //PROD
        require __DIR__."/$level/vendor/autoload.php";
        use Google\Cloud\Storage\StorageClient;

        $projectId = 'steel-fin-282304';

        $storage = new StorageClient([
            'projectID' => $projectId
        ]);

        $bucket = $storage->createBucket(bin2hex(random_bytes(8)));
    ?>

    <div class="container">
        <h1>marvie</h1>
            <!-- <form id="uploadForm" action="upload.php" method="POST" enctype="mulipart/form-data" onsubmit="fileSubmit(event)">
                <div class="form-group">
                    <label for="file">File</label>
                    <input id="fileUpload" name="file" type="file" class="form-control"aria-describedby="file">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <div class="container">
        <script>
            function fileSubmit(e){
                e.preventDefault();
                console.log("file press!")

                // Grab the form HTML DOM element
                let myForm = $("#uploadForm")[0];
                
                // // Place All the data in a new form data
                // let formData = new FormData(myForm);

                // New form Data
                let formData = new FormData();

                // Grab the uploaded images
                let imageFiles = $('#fileUpload')[0].files;

                //     // Append all images to form data
                //     for(var x = 0; x < imageFiles.length; x++){
                //         formData.append(`file${x}`, imageFiles[x]);
                //     }    

                // Append image to form data
                formData.append(`file`, imageFiles[0]);

                console.log(imageFiles[0])
                
                $.ajax({
                    type : 'POST',
                    url : '../auth/fileHandler/filetransfer.php',
                    data : formData,
                    contentType: false,
                    processData: false,
                    success : function(response) {

                        console.log(response);
                        // disableRehomeFormSpinner();
                        // //alert(response);
                        // var res = JSON.parse(response);
                        // setTimeout(function() {
                        //     alert(res["message"]);
                        // },100);
                        // if(res["status"] == 200){
                        //     myForm.reset();
                        //     var image_holder = $("#image-holder");
                        //     image_holder.empty();
                        // }     

                    }
                });
                    

                
            }
        </script>

        </div> -->

        <div class="container">
            <h3>Testing UI links</h3>
            <h2>Home Owner</h2>
            <ul>
                <li><a href="./homeowner/home.php">Home</a> (Has redirect set-up - redirects to index.php when no user token)</li>
                <li><a href="./homeowner/create-project.php">Project Creation</a></li>
                <li><a href="./homeowner/projects.php">projects</a></li>
                <li><a href=""></a>Single Project</li>
                <li><a href=""></a>Messages</li>
            </ul>
            <h2>Worker</h2>
            <ul>
                <li><a href="./worker/register.php">Registration</a> (Has redirect set-up - redirects to worker landing when no registration token)</li>
                <li><a href="./worker/home.php">Home</a></li>
                <li><a href="./worker/">Landing</a></li>
            </ul>
            <h2>Support</h2>
            <ul>
                <li><a href="./support/home.php">Home</a> </li>
                <li><a href="./support/index.php">Signup</a></li>
                <li><a href="./support/home.php">Dashboard</a></li>
            </ul>
        </div>


        <h3>testing get request</h3>
        <button id="tester"class="btn btn-secondary my-2">
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


$(document).ready(()=>{
    var tester = document.getElementById("tester");

    // Set events for elements
    tester.addEventListener("click", ()=>{
        console.log("click")
            let data = $.ajax({
            type: 'GET',
            url: 'https://slim3api.herokuapp.com/create-guest',
            success: response => {
                console.log(response)
            }, 
            error:  response => {
                console.log(response)
            }
            });
    });

    const loadData = () => {
        $.ajax({
            type: 'GET',
            url: 'https://slim3api.herokuapp.com/create-guest',
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
    }

    loadData();

});

    


    </script>
</body>
</html>