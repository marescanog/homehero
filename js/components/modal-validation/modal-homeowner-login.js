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
        submitformData['userType'] = 1;

        // Grab DOM elements to freeze form and the submit button, initiate loading
        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
        
        // Ajax request to login
        $.ajax({
            type: 'POST',
            url : 'https://slim3api.herokuapp.com/auth/client-login',
            // url: 'http://localhost/slim3homeheroapi/public/auth/client-login',
            data : submitformData,
            success : function(response) {
                // console.log("your response after account login is:")
                // console.log(response);
             

                // Redirect to User Auth then User Home page
                let data = {};
                data['token'] = response["response"]["token"]
                data['userType'] = 1;
                data['email'] = response["response"]["email"];
                data['first_name'] = response["response"]["first_name"];
                data['initials'] = response["response"]["initials"];
                data['role'] = response["response"]["role"];

                // console.log("your data token is");
                // console.log(data);
                // an ajax to assign registration session token
                $.ajax({
                    type : 'POST',
                    url : getDocumentLevel()+'/auth/setLoginSession.php',
                    data : data,
                    success : function(response) {
                        var res = JSON.parse(response);
                        // console.log("Your response after register-auth is")
                        // console.log(res)
                        if(res["status"] == 200){
                            // Unfreeze the form & Rest
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
                            $('#modal-login-form')[0].reset();
                            Swal.fire({
                                title: 'Phone number verification success!',
                                text: 'Redirecting you to the homeowner dashboard...',
                                icon: "success",
                                timer: 2500,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                }).then(result => {
                                window.location = getDocumentLevel()+'/pages/homeowner/home.php';
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
                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
            }
        });
    }
});