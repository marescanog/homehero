$("#modal-login-form").validate({
    rules: {
        phone:{
            phonePH: true
        },
        password : {
            required: true,
            maxlength: 30
        },
    },
    messages: {
        phone: {
            required: "Please enter your mobile number"
        },
        password:{
            required:  "Please enter your password",
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();
        const submitformData = getFormDataAsObj(form);
        submitformData['userType'] = 2; //for worker

        // Grab DOM elements to freeze form and the submit button, initiate loading
        const button = document.getElementById("LU-submit-btn");
        const buttonTxt = document.getElementById("LU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("LU-submit-btn-load");
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
        
        // CHANGELINKDEVPROD
        // Ajax request to login
        $.ajax({
            type: 'POST',
            // url : 'https://slim3api.herokuapp.com/auth/client-login', //prod
             url: 'http://localhost/slim3homeheroapi/public/auth/client-login', //dev
            data : submitformData,
            success : function(response) {
                // console.log("your response after account login is:")
                // console.log(response);
             

                // Redirect to User Auth then User Home page
                let data = {};
                data['token'] = response["response"]["token"]
                data['id'] = response["response"]["id"]
                data['userType'] = 2;
                data['email'] = response["response"]["email"];
                data['first_name'] = response["response"]["first_name"];
                data['initials'] = response["response"]["initials"];
                data['role'] = response["response"]["role"];
                // profile_pic_location
                data['profile_pic_location'] = response["response"]["profile_pic_location"];

                // console.log(data);

                // console.log("your data token is");
                // console.log(data);
                // an ajax to assign registration session token
                $.ajax({
                    type : 'POST',
                    url : getDocumentLevel()+'/auth/setLoginSessionWorker.php',
                    data : data,
                    success : function(response) {
                        var res = JSON.parse(response);
                        // console.log("Your response after register-auth is")
                        // console.log(res)
                        if(res["status"] == 200){
                            // Unfreeze the form & Rest
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
                            $('#modal-login-form')[0].reset();
                            Swal.fire({
                                title: 'Phone number verification success!',
                                text: 'Redirecting you to the worker dashboard...',
                                icon: "success",
                                timer: 2500,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                }).then(result => {
                                window.location = getDocumentLevel()+'/pages/worker/home.php';
                            })
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong! Please try again',
                                icon: 'error',
                                confirmButtonText: 'ok'
                            })
                        }
                    }
                });
            },
            error: function (response) {
                // console.log("your response message is")
                // console.log(response);
                // console.log("your response response message is")
                // console.log(response.responseJSON);
                Swal.fire({
                    title: 'Error!',
                    text: response.responseJSON.response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
            }
        });
    }
});