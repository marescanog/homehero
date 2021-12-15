$("#registerForm").validate({
    rules: {
        first_name: {
            maxlength: 50
        },
        last_name:{
            maxlength: 50
        },
        phone:{
            phonePH: true
        },
        above18: { 
            required: true,
            equalTo : "#above18-yes",
        },
        password : {
            required: true,
            maxlength: 30
        },
        confirm_password : {
            required: true,
            equalTo : "#password"
        },
        agree: "required"
    },
    messages: {
        first_name:{
            required:  "Please enter your first name",
            maxlength: "First name cannot be beyond 50 characters"
        },
        last_name:{
            required:  "Please enter your last name",
            maxlength: "Last name cannot be beyond 50 characters"
        },
        phone: {
            required: "Please enter your mobile number"
        },
        password:{
            required:  "Please create a password",
            minlength: "Password must be at least 8 characters long",
            maxlength: "Password cannot be beyond 30 characters"
        },
        confirm_password :{
            required:  "Please re-enter the password",
            equalTo : "This field must match entered password"
        },
        above18:{
            required: "Please select Yes or No", 
            equalTo : "You must be over the age of 18 to register."
        },
        agree: "Please agree to our terms and conditions"
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        // Grab DOM elements & Form data
        const button = document.getElementById("RW-submit-btn");
        const buttonTxt = document.getElementById("RW-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RW-submit-btn-load");
        const formData = getFormDataAsObj(form);

        // Freeze Form & Disable
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        const phoneCheckInDatabase = {}
        phoneCheckInDatabase["phone"] = formData["phone"];
        
         // CHANGELINKDEVPROD x5
        // Send Post Request to API
        // Ajax to check phone number;
        $.ajax({
            type: 'GET',
            // url : '', // PROD NO DO NOT PROD
            url: 'http://localhost/slim3homeheroapi/public/auth/check-phone', // DEV
            data : phoneCheckInDatabase,
            success : function(response) {
                console.log(response);
                // Proceed to SMS verification to submit with Ajax for worker creation.
                // Verify password and get a hashed password
                 // CHANGELINKDEVPROD
                $.ajax({
                    type: 'POST',
                    // url : 'https://slim3api.herokuapp.com/auth/verify-password', // PROD
                    url: 'http://localhost/slim3homeheroapi/public/auth/verify-password', // DEV
                    data : formData,
                    success : function(response) {
                        // Note: if we were to skip the step of SMS verification, we would remove/comment out loadModal Code
                        // Then add in ajax code that adds the worker direct to the DB (the api route that adds a worker);

                        // Create a new object only containing: firstname, lastname, mobile number and hashedpass/securepass
                        const dataToPassToSMSModal = {};
                        dataToPassToSMSModal["first_name"] = formData["first_name"];
                        dataToPassToSMSModal["last_name"] = formData["last_name"];
                        dataToPassToSMSModal["phone"] = formData["phone"];
                        dataToPassToSMSModal["securepass"] = response['response']['data'];

                        const dataToPassToSMSGeneration = {};
                        dataToPassToSMSGeneration["phone"] = formData["phone"];

                         // CHANGELINKDEVPROD
                        // Before passing, we call api to generate an SMS code. If the number is inavlid, we display the error
                        // message here, otherwise if valid proceed to show SMS modal
                        $.ajax({
                            type: "POST",
                            // url : 'https://slim3api.herokuapp.com//auth/generate-SMS-dummy', // PROD-DUMMY
                            url: 'http://localhost/slim3homeheroapi/public/auth/generate-SMS-dummy', // DEV-DUMMY,
                            //url : 'https://slim3api.herokuapp.com/auth/verify-password', // PROD-API
                            // url: 'http://localhost/slim3homeheroapi/public/auth/verify-password', // DEV-API,
                            data: dataToPassToSMSGeneration,
                            success: function(response){
                                // If it is a success, then SMS was generated successfully
                                // console.log(response['response']['data']['messagebird_id']);
                                // grab the messagebird id and add it to the dataToPassToSMSModal
                                dataToPassToSMSModal["messagebird_id"] = response['response']['data']['messagebird_id'];

                                // pass the complete form data into the load modal
                                loadModal("SMS-verification-worker", modalTypes, ()=>{}, getDocumentLevel(), dataToPassToSMSModal);
                            },
                            error: function (response) {
                                console.log(response);
                                // Either Invalid phone number or something wrong with API
                                Swal.fire({
                                    title: 'Invalid phone number!',
                                    text: "Please try entering a valid phone number",
                                    icon: 'error',
                                    confirmButtonText: 'Try a different number'
                                })
                            }
                        });

                        // pass that form data into the load modal (This can be used in the event we skip SMS, just change load modal to ajax directly saving dataToPassToSMSModal to db)
                        // loadModal("SMS-verification-worker", modalTypes, ()=>{}, getDocumentLevel(), dataToPassToSMSModal);                        
                    },
                    error: function (response) {
                        // CLEANUP ADD/TOGGLE ERRORS FOR APPROPRIATE FEILDS
                        // enableErrorDisplayFor(ID,MESSAGE) for the ff:;
                        // id="(id_name)_formGroup" to the div with formgroup class for the ff.
                            // Password
                            // Confirm Password
                        console.log(response)
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong. Please try again",
                            icon: "error",
                            
                        })
                    }
                });
                
                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

            },
            // CLEANUP -> ADD APPROPRIATE NAVIGATION TO LOGIN MODALS INSTEAD OF SWAL DISPLAY
            error: function (response) {
                // display error message when phone number is taken
                // console.log(response);

                const data = response["responseJSON"]["response"]["data"];
                const message = response["responseJSON"]["response"]["message"];
                // console.log(data);
                // console.log(message);

                // For the fourth SWAL button
                $(document).on('click', "#try-diff-number", function() {
                    enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
                    enableErrorDisplayFor("RU_phone","Phone number is already associated with an existing account. Please enter a different number.");
                    swal.close();
                });

                const formatDataForHasRegistered = {};
                formatDataForHasRegistered["phone"] = formData["phone"];

                 // CHANGELINKDEVPROD
                // CLEANUP TO DO : CHECK IF WORKER HAS FINISHED REGISTRATION
                // IF NOT FINISHED, REDIRECT TO REGISTRATION PAGES
                // Check if the phone number already has an existing user or support account
                if(data.isWorker == true){
                    $.ajax({
                        type: "GET",
                        // url: "https://slim3api.herokuapp.com/auth/worker/hasRegistered", // PROD
                        url: "http://localhost/slim3homeheroapi/public/auth/worker/hasRegistered", // DEV
                        data: formatDataForHasRegistered,
                        success: function (response) {
                            console.log(response)
                            const responseObject = JSON.parse(response)
                            console.log(responseObject)
                            console.log(responseObject['response']['has_completed_registration'] == 1);
                            const hasCompletedRegistration = responseObject['response']['has_completed_registration'] == 1;
        
                            // if the user is a worker and has not completed the registration process
                            // it will prompt the user to continue with registration
                            if(hasCompletedRegistration == false){
                                const registrationTokenData = {};
                                registrationTokenData["password"] = "";
                                registrationTokenData["phone"] = responseObject['response']['phone_no'];
                                registrationTokenData["userID"] = responseObject['response']['user_id'];
                                $.fn.modal.Constructor.prototype._enforceFocus = function() {};
                                Swal.fire({
                                    title: 'Looks like you have already started the registration process',
                                    text: 'Please input your password to continue with registration.',
                                    input: 'text',
                                    inputAttributes: {
                                      autocapitalize: 'off'
                                    },
                                    showCancelButton: true,
                                    confirmButtonText: 'Continue Registration',
                                    showLoaderOnConfirm: true,
                                    // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form),
                                    preConfirm: (password) => {
                                        registrationTokenData['password'] = password;
                                         // CHANGELINKDEVPROD
                                        let result = $.ajax({
                                            type: "POST",
                                             // url: "https://slim3api.herokuapp.com/auth/worker/create-registration-token", // PROD
                                             url: "http://localhost/slim3homeheroapi/public/auth/worker/create-registration-token", // DEV
                                            data: registrationTokenData,
                                            success: (result)=>{
                                                // console.log(result)
                                                return result;
                                            }
                                        })
                                        //  Return result back of ajax to the next modal
                                        return result;
                                    },
                                    allowOutsideClick: () => !Swal.isLoading()
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                        // console.log(result.value);
                                        // console.log(result.value.response);
                                            if(result.value.response.message){
                                                // Swal.fire({
                                                //     title: 'password correct!',
                                                // })
                                                let data = {};
                                                data['registration_token'] = result.value.response.data.token;
                                                data['hasRegistered'] = result.value.response.data.has_registered;
                                                $.ajax({
                                                    type : 'POST',
                                                    url : getDocumentLevel()+'/auth/register-auth.php',
                                                    data : data,
                                                    success : function(response) {
                                                        var res = JSON.parse(response);
                                                        // Your response after register-auth is
                                                        console.log(res)
                                                        if(res["status"] == 200){
                                                            // Unfreeze the form & Rest

                                                            Swal.fire({
                                                                title: 'Verification success!',
                                                                text: 'Redirecting you to the registration pages...',
                                                                icon: "success",
                                                                timer: 3500,
                                                                showCancelButton: false,
                                                                showConfirmButton: false,
                                                                timerProgressBar: true,
                                                                }).then(result => {
                                                                window.location = getDocumentLevel()+'/pages/worker/register.php';
                                                            })
                                                        } else {
                                                            Swal.fire({
                                                                title: 'Error!',
                                                                text: 'Something went wrong! Please try again',
                                                                icon: 'error',
                                                                confirmButtonText: 'ok',
                                                                // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form)
                                                            })
                                                        }
                                                    }
                                                });
                                            } else {
                                                // Enable forms
                                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

                                                // Display Error message
                                                enableErrorDisplayFor("RU_phone","Phone number is already associated with an existing account. Please enter a different number.");
                                                Swal.fire({
                                                    title: 'password incorrect!',
                                                    text: 'Click on forgot password to recover your account.'
                                                })
                                            }
                                    }
                                  })
                            } else {
                                // Otherwise if the user is already done with registration, it will 
                                // prompt the user to either login his/her worker account or enter a different number
                                Swal.fire({
                                    title: 'Phone number already registered to an account!',
                                    text: message,
                                    icon: 'error',
                                    showCancelButton: true,
                                    confirmButtonText: 'Try a different number',
                                    cancelButtonText: 'Login with this number at worker portal',
                                    // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form)
                                }). then((result)=>{
                                    if (result.isConfirmed) {
                                        enableErrorDisplayFor("RU_phone","Phone number is already associated with an existing account. Please enter a different number.");
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Login with this number
                                        // Open Worker Portal
                                        loadModal("worker-login",modalTypes,()=>{},getDocumentLevel());
                                    }
                                });
                            }
                        },
                        error: function (response) {
                            console.log(response)
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong. Please try again",
                                icon: "error",
                                // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form)
                            })
                        }
                    });

                } else {
// Temporary
// Enable forms
enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

// Display Error message
enableErrorDisplayFor("RU_phone","Phone number is already associated with an existing account. Please enter a different number.");

                    if(data.isHomeowner == true && (data.isSupport == true||data.isAdmin == true)){
                        Swal.fire({
                            title: 'Phone number number already registered to an account!',
                            // text: "This phone number is associated with a homeowner account and a support account. Would you like to try a different number?",
                            icon: 'error',
                            html: "<p>This phone number is associated with a homeowner account and a support account. Would you like to try a different number?</p><button id='try-diff-number' class='btn btn-primary'>Try a different number</button>",
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Log in at the homeowner portal instead',
                            //cancelButtonText: 'Log in at the support portal instead.',
                           // denyButtonText: `Register as a worker with this number`,
                            // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form)
                        }). then((result)=>{
                            if (result.isConfirmed) {
                                // redirect to homeowner portal
                                Swal.fire('Redirect to homeowner portal', '', 'info');
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // redirect to worker portal
                                Swal.fire('Redirect to worker portal', '', 'info');
                            } else if (result.isDenied) {
                                // Proceed to SMS verification to submit with Ajax for worker creation.
                                Swal.fire('Proceed to SMS verification', '', 'info');
                            } 
                        })
                    } else if (data.isSupport == true||data.isAdmin == true){
                        Swal.fire({
                            title: 'Phone number number already registered to an account!',
                            text: "Looks like you have a support account associated with this number. Would you like to log in as support instead?",
                            icon: 'error',
                           // showCancelButton: true,
                           // showDenyButton: true,
                            confirmButtonText: 'Try a different phone number instead',
                           // cancelButtonText: 'Log in at the support portal instead.',
                           // denyButtonText: `Register as a worker with this number`,
                            // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form)
                        }).then((result)=>{
                            if (result.isConfirmed) {
                                // try different number
                                enableErrorDisplayFor("RU_phone","Phone number is already associated with an existing account. Please enter a different number.");
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // redirect to worker portal
                                Swal.fire('Redirect to support portal', '', 'info');
                            } else if (result.isDenied) {
                                // Proceed to SMS verification to submit with Ajax for worker creation.
                                Swal.fire('Proceed to SMS verification', '', 'info');
                            } 
                        });
                    } else {
                        Swal.fire({
                            title: 'Phone number number already registered to an account!',
                            text: "Looks like you have a homeowner account associated with this number. Would you like to log in to your homeowner account instead?",
                            icon: 'error',
                            //showCancelButton: true,
                            //showDenyButton: true,
                            confirmButtonText: 'Try a different phone number instead',
                           // cancelButtonText: 'Log in at the homeowner portal instead',
                           // denyButtonText: `Register as a worker with this number`,
                            // onClose: enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form)
                        }).then((result)=>{
                            if (result.isConfirmed) {
                                // try different number
                                enableErrorDisplayFor("RU_phone","Phone number is already associated with an existing account. Please enter a different number.");
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // redirect to worker portal
                                Swal.fire('Redirect to homeowner portal', '', 'info');
                            } else if (result.isDenied) {
                                // Proceed to SMS verification to submit with Ajax for worker creation.
                                Swal.fire('Proceed to SMS verification', '', 'info');
                            } 
                        });
                    }
                }

            }
        });


        
    }
});
// '[name="password"]'