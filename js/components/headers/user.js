// console.log("User.js is loaded!");

// Check if stylesheet for this component is loaded in document
appendStyleSheet("/css/headers/user.css");

$( document ).ready(()=>{
    const buttonDesktop = document.getElementById("header-btn-desktop");
    const buttonMobile = document.getElementById("header-btn-mobile");
    const signUpHeaderLink = document.getElementById("RU-signup");
    // var modal = document.getElementById("modal");


    // Set events for elements
    buttonDesktop.addEventListener("click", ()=>{
        loadModal("user-login",modalTypes,()=>{},getDocumentLevel());
    });

    buttonMobile.addEventListener("click", ()=>{
        loadModal("user-login",modalTypes,()=>{},getDocumentLevel());
    });

    signUpHeaderLink.addEventListener("click", ()=>{
        loadModal("signup", modalTypes, submitSignUpModalhandler, getDocumentLevel());
    });

    const submitLoginHandler = () => {
        // const button = document.getElementById("LU-submit-btn");
        // button.addEventListener("click", ()=>{
        //     login();
        // })
    }

    const submitSignUpModalhandler = () => {
        // var signUpSubmitButton = document.getElementById("RU-submit-btn");
        // signUpSubmitButton.addEventListener("click", ()=>{
        //     registerHandler();
        // });
    }
/*
    const login = () =>{
        // Grab DOM elements
        const myForm = document.getElementById('modal-login-form');
        const button = document.getElementById("LU-submit-btn");
        const buttonTxt = document.getElementById("LU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("LU-submit-btn-load");

        // Disable and show loading
        button.setAttribute("disabled", "true");
        buttonTxt.innerHTML = "Loading"
        buttonLoadSpinner.setAttribute("class", "d-inline");
        myForm.style.opacity = "0.5";

        var elements = myForm.elements;
        for (var i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
        }
    
        // Convert Form Data to Object
        let formData = new FormData(myForm);
        let data = {};
        formData.forEach((value, key) => data[key] = value);

        // CHANGELINKDEVPROD
        // Send Post Request to API
        $.ajax({
            type: 'POST',
            // url : 'https://slim3api.herokuapp.com/auth/login', // PROD
             url: 'http://localhost/slim3homeheroapi/public/auth/login', // DEV
            data : data,
            success : function(response) {
                // Enable and hide loading
                button.removeAttribute("disabled");
                buttonTxt.innerHTML = "Login"
                buttonLoadSpinner.removeAttribute("class");
                    buttonLoadSpinner.setAttribute("class", "d-none");
                myForm.style.opacity = "1";
    
                var elements = myForm.elements;
                for (var i = 0, len = elements.length; i < len; ++i) {
                    elements[i].readOnly = false;
                }

                // console.log(response.success);
                // console.log(response.response.data);

                if(response.success){
                    let data = {};
                    data['token'] = response.response.data.token;
                    data['first_name'] = response.response.data.first_name;
                    data['initials'] = response.response.data.initials;

                    $.ajax({
                        type : 'POST',
                        url : getDocumentLevel()+'/auth/user-auth.php',
                        data : data,
                        success : function(response) {
                            var res = JSON.parse(response);
                            // console.log(res)
                            if(res["status"] == 200){
                                myForm.reset();
                                $('#modal').modal('hide');

                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Login Successful!',
                                    icon: 'success',
                                    confirmButtonText: 'Continue'
                                }).then(result => {
                                    window.location = getDocumentLevel()+'/pages/homeowner/home.php'
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
                }
            },
            error: function (response) {
                console.log(response.responseJSON.response);
                // var res = JSON.parse(response);
                // console.log(res);

                var message = JSON.stringify(response.responseJSON.response.message);

                // Enable and hide loading
                button.removeAttribute("disabled");
                buttonTxt.innerHTML = "Login"
                buttonLoadSpinner.removeAttribute("class");
                 buttonLoadSpinner.setAttribute("class", "d-none");
                myForm.style.opacity = "1";
    
                var elements = myForm.elements;
                for (var i = 0, len = elements.length; i < len; ++i) {
                    elements[i].readOnly = false;
                }
    
                // console.log(response.responseJSON)
                Swal.fire({
                    title:'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'Close'
                })

            }
        })
    }
*/
    // const registerHandler =(e)=>{
    //     // e.preventDefault();
    
    //     // Grab DOM elements
    //     const myForm = document.getElementById('registerForm');
    //     const RUSignupSubmitButton = document.getElementById("RU-submit-btn");
    //     const RUSignupSubmitTxt = document.getElementById("RU-submit-btn-txt");
    //     const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
    
    //     // Disable and show loading
    //     RUSignupSubmitButton.setAttribute("disabled", "true");
    //     RUSignupSubmitTxt.innerHTML = "Loading"
    //     buttonLoadSpinner.setAttribute("class", "d-inline");
    //     myForm.style.opacity = "0.5";
    
    //     var elements = myForm.elements;
    //     for (var i = 0, len = elements.length; i < len; ++i) {
    //         elements[i].readOnly = true;
    //     }
    
    //     // Convert Form Data to Object
    //     let formData = new FormData(myForm);
    //     let data = {};
    //     formData.forEach((value, key) => data[key] = value);
    
    //     // Send Post Request to API
    //     $.ajax({
    //         type : 'POST',
    //         url : 'https://slim3api.herokuapp.com/auth/user-registration',
    //         data : data,
    //         success : function(response) {
    //             var res = JSON.parse(response);
    //             // console.log(response);
    //             let message = res["response"];
         
    //             // Enable and hide loading
    //             RUSignupSubmitButton.removeAttribute("disabled");
    //             RUSignupSubmitTxt.innerHTML = "Register"
    //          buttonLoadSpinner.removeAttribute("class");
    //          buttonLoadSpinner.setAttribute("class", "d-none");
    //             myForm.style.opacity = "1";
    
    //             var elements = myForm.elements;
    //             for (var i = 0, len = elements.length; i < len; ++i) {
    //                 elements[i].readOnly = false;
    //             }
    
    //             Swal.fire({
    //                 title: res["success"] ? 'Success!': 'Error!',
    //                 text: message,
    //                 icon: res["success"] ? 'success': 'error',
    //                 confirmButtonText: 'Close'
    //             }).then(result => {
    //                 if(res["success"]){
    //                     //  Reset Form, Close Modal
    //                     myForm.reset();
    //                     $('#modal').modal('hide');
    //                 }
    //             })
    //         },
    //         error: function (response) {
    //             var message = JSON.stringify(response.responseJSON.response.message);
    //             // Enable and hide loading
    //             RUSignupSubmitButton.removeAttribute("disabled");
    //             RUSignupSubmitTxt.innerHTML = "Register"
    //             buttonLoadSpinner.removeAttribute("class");
    //             buttonLoadSpinner.setAttribute("class", "d-none");
    //             myForm.style.opacity = "1";
    
    //             var elements = myForm.elements;
    //             for (var i = 0, len = elements.length; i < len; ++i) {
    //                 elements[i].readOnly = false;
    //             }
    
    //             // console.log(response.responseJSON)
    //             Swal.fire({
    //                 title:'Error!',
    //                 text: message,
    //                 icon: 'error',
    //                 confirmButtonText: 'Close'
    //             })
    //         },
    //     });
    // }

    // const pageUnavailable = () => {
    //     Swal.fire({
    //         title: "Not Available",
    //         confirmButtonText: 'Close',
    //         html: "<img src='./images/svg/construction_icon.svg' style='height:100px; width:100px;'class='rounded mr-2 mb-3' alt='...'> <p>This feature is under construction. Please check back again later!</>"
    //     })
    // }
})
