$("#support-login-form").validate({
    rules: {
        email:{
            email: true,
            required: true
        },
        password : {
            required: true,
            maxlength: 30
        },
    },
    messages: {
        email: {
            email: "Invalid email",
            required: "Please enter your email"
        },
        password:{
            required:  "Please enter your password",
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();
        const submitformData = getFormDataAsObj(form);

        // console.log(submitformData);
        // Grab DOM elements to freeze form and the submit button, initiate loading
        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
        
        // CHANGELINKDEVPROD
        // Ajax request to login
        $.ajax({
            type: 'POST',
            // url : '', //no prod link
             url: 'http://localhost/slim3homeheroapi/public/support/login', //dev
            data : submitformData,
            success : function(response) {
                console.log("your response after account login is:")
                // console.log(response);
             

                // Redirect to Support Auth then Support Home page
                let data = {};
                data['token'] = response["response"]["token"]
                data['userType'] = 3;
                data['email'] = response["response"]["email"];
                data['first_name'] = response["response"]["first_name"];
                data['last_name'] = response["response"]["last_name"];
                data['role'] = response["response"]["role"];
                // profile_pic_location
                data['profile_pic_location'] = response["response"]["profile_pic_location"];

                // console.log(data);

                // // console.log("your data token is");
                // // console.log(data);
                // an ajax to assign registration session token
                $.ajax({
                    type : 'POST',
                    url : getDocumentLevel()+'/auth/setSupportLoginSession.php',
                    data : data,
                    success : function(response) {
                        var res = JSON.parse(response);
                        // console.log("Your response after register-auth is")
                        // console.log(res)
                        if(res["status"] == 200){
                            // Unfreeze the form & Rest
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
                            $('#support-login-form')[0].reset();
                            Swal.fire({
                                title: 'login success!',
                                text: 'Redirecting you to the support dashboard...',
                                icon: "success",
                                timer: 2500,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                }).then(result => {
                                window.location = getDocumentLevel()+'/pages/support/home.php';
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
                // console.log(JSON.stringify(response.responseJSON.response));
                // console.log("your response response message is")
                // console.log(response.responseJSON);
                Swal.fire({
                    title: 'Login Error!',
                    text: response.responseJSON.response+". Please try again.",
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
            }
        });
    }
});

const test = () => {

}