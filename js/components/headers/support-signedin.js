console.log("support-signed-in.js is loaded!");

// appendStyleSheet("/css/headers/header-homeowner.css");

$( document ).ready(()=>{
    const logoutDesktop = document.getElementById("logout-btn-desktop");
    // const logoutMobile = document.getElementById("logout-link-mobile");
    // // const tempDisabledBrowse = document.getElementById('browse-temp');

    // // var modal = document.getElementById("modal");

    // // Set events for elements
    // // tempDisabledBrowse.addEventListener("click", ()=>{
    // //     // console.log("desktop click")
    // //     Swal.fire({
    // //         title: 'Under Development',
    // //         text: 'This feature is currently under development!',
    // //         icon: 'info',
    // //         confirmButtonText: 'Continue'
    // //     })
    // // });

    // Set events for elements
    logoutDesktop.addEventListener("click", ()=>{
        // console.log("desktop click")
        logout()
    });

    // logoutMobile.addEventListener("click", ()=>{
    //     // console.log("mobile click")
    //     logout()
    // });

    const logout = () => {
        $.ajax({
            type : 'GET',
            url : '../../auth/signout_action.php',
            success : function(response) {
                var res = JSON.parse(response);
                if(res["status"] == 200){
                    Swal.fire({
                        title: 'Logged out',
                        text: 'You have been sucessfully logged out of your account!',
                        icon: 'success',
                        confirmButtonText: 'Continue'
                    }).then(result => {
                        window.location = '../../pages/support/';
                    })
                }
            }
        });
    }

    // signUpHeaderLink.addEventListener("click", ()=>{
    //     loadModal("signup", modalTypes);
    // });

//     const registerHandler =(e)=>{
//         e.preventDefault();

//         console.log('register!')
    
//         // // Grab DOM elements
//         // const myForm = document.getElementById('registerForm');
//         // const RUSignupSubmitButton = document.getElementById("RU-submit-btn");
//         // const RUSignupSubmitTxt = document.getElementById("RU-submit-btn-txt");
//         // const RUSignupSubmitLoad = document.getElementById("RU-submit-btn-load");
    
//         // // Disable and show loading
//         // RUSignupSubmitButton.setAttribute("disabled", "true");
//         // RUSignupSubmitTxt.innerHTML = "Loading"
//         // RUSignupSubmitLoad.setAttribute("class", "d-inline");
//         // myForm.style.opacity = "0.5";
    
//         // var elements = myForm.elements;
//         // for (var i = 0, len = elements.length; i < len; ++i) {
//         //     elements[i].readOnly = true;
//         // }
    
//         // // Convert Form Data to Object
//         // let formData = new FormData(myForm);
//         // let data = {};
//         // formData.forEach((value, key) => data[key] = value);
    
//         // // Send Post Request to API
//         // $.ajax({
//         //     type : 'POST',
//         //     url : 'https://slim3api.herokuapp.com/user/register',
//         //     data : data,
//         //     success : function(response) {
//         //         var res = JSON.parse(response);
//         //         console.log(response);
    
//         //         // Enable and hide loading
//         //         RUSignupSubmitButton.removeAttribute("disabled");
//         //         RUSignupSubmitTxt.innerHTML = "Register"
//         //         RUSignupSubmitLoad.removeAttribute("class");
//         //         RUSignupSubmitLoad.setAttribute("class", "d-none");
//         //         myForm.style.opacity = "1";
    
//         //         var elements = myForm.elements;
//         //         for (var i = 0, len = elements.length; i < len; ++i) {
//         //             elements[i].readOnly = false;
//         //         }
    
//         //         Swal.fire({
//         //             title: res["success"] ? 'Success!': 'Error!',
//         //             text: res["success"].message,
//         //             icon: res["success"] ? 'success': 'error',
//         //             confirmButtonText: 'Close'
//         //         }).then(result => {
//         //             if(res["success"]){
//         //                 //  Reset Form, Close Modal
//         //                 myForm.reset();
//         //                 $('#modal').modal('hide');
//         //             }
//         //         })
//         //     },
//         //     error: function (response) {
    
//         //         // Enable and hide loading
//         //         RUSignupSubmitButton.removeAttribute("disabled");
//         //         RUSignupSubmitTxt.innerHTML = "Register"
//         //         RUSignupSubmitLoad.removeAttribute("class");
//         //         RUSignupSubmitLoad.setAttribute("class", "d-none");
//         //         myForm.style.opacity = "1";
    
//         //         var elements = myForm.elements;
//         //         for (var i = 0, len = elements.length; i < len; ++i) {
//         //             elements[i].readOnly = false;
//         //         }
    
//         //         console.log(response.responseJSON)
//         //         Swal.fire({
//         //             title:'Error!',
//         //             text: 'Fields must not be empty',
//         //             icon: 'error',
//         //             confirmButtonText: 'Close'
//         //         })
//         //     },
//         // });
//     }
})
