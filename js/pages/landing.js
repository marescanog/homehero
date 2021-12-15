
$(document).ready(()=>{
    // Grab the DOM elements
    // const button_jumbo_search = document.getElementById("button-jumbo-search");
    // const button_jumbo_register = document.getElementById("button-jumbo-register");

    // button_jumbo_search.addEventListener("click", ()=>{
    //     loadModal("tempAddr",modalTypes,tempAddSubmissionHandler,getDocumentLevel());
    // })

    // button_jumbo_register.addEventListener("click", ()=>{
    //    loadModal('tempRegister',modalTypes,tempWorkerRegisterHandler,getDocumentLevel()); // This code loads in your modal HTML
    // })

    // // Specify the Modal's callback function after it loads;
    // const tempWorkerRegisterHandler = () => {
    //     const button = document.getElementById('RU-submit-btn');
    //     button.addEventListener('click',()=>{
    //         workerRegister();
    //     })
    // }


    // const tempAddSubmissionHandler = () => {
    //     const button = document.getElementById('enterAddressButton');
    //     button.addEventListener("click",()=>{
    //         submitAddress();
    //     })
    // }

    // const workerRegister = () =>{
    //     // where your ajax call would be
    //     // get the form data
    //     // make an ajax call to save the address into the database
    //     Swal.fire({
    //         title: "success",
    //         icon: "success",
    //         text: "You have sucessfully registered as a worker!",
    //         confirmButtonText: "close"
    //     }).then(()=>{
    //         // perform closing code wuch as cleaning form, reactivating form, closing the modal etc.
    //         $('#modal').modal('hide');
    //     })
    // }

    // const submitAddress = () => {
    //     // where your ajax call would be
    //     // get the form data
    //     // make an ajax call to save the address into the database
    //     Swal.fire({
    //         title: "success",
    //         icon: "success",
    //         text: "You have sucessfully searched an address!",
    //         confirmButtonText: "close"
    //     }).then(()=>{
    //         // perform closing code wuch as cleaning form, reactivating form, closing the modal etc.
    //         $('#modal').modal('hide');
    //     })
    // }



    // GET THE 4 BUTTONS ON THE PAGE NAMELY
    // JUMBOTRON - SIGN UP NOW HOMEOWNER
    // JUMBOTRON - RESGISTER AS WORKER
    // CTA - SIGN UP NOW HOMEOWNER
    // CTA - RESGISTER AS WORKER
    const button_jumbo_signup = document.getElementById("button_jumbo_signup");
    const button_jumbo_register = document.getElementById("button_jumbo_register");
    const button_cta_signup = document.getElementById("button_cta_signup");
    const button_cta_register = document.getElementById("button_cta_register");

    // Assign the load modals for each appropriate button
    button_jumbo_signup.addEventListener("click", ()=>{
        loadModal("signup",modalTypes,()=>{},getDocumentLevel());
    });
    button_jumbo_register.addEventListener("click", ()=>{
        loadModal("worker-signup",modalTypes,()=>{},getDocumentLevel());
    });
    button_cta_signup.addEventListener("click", ()=>{
        loadModal("signup",modalTypes,()=>{},getDocumentLevel());
    });
    button_cta_register.addEventListener("click", ()=>{
        loadModal("worker-signup",modalTypes,()=>{},getDocumentLevel());
    });
})










// Grab DOM elements
// var buttonDesktop = document.getElementById("header-btn-desktop");
// var buttonMobile = document.getElementById("header-btn-mobile");
// var signUpHeaderLink = document.getElementById("RU-signup");
// var modal = document.getElementById("modal");

// // Set events for elements
// buttonDesktop.addEventListener("click", ()=>{
//     Swal.fire({
//         title: "Not Available",
//         confirmButtonText: 'Close',
//         html: "<img src='./images/svg/construction_icon.svg' style='height:100px; width:100px;'class='rounded mr-2 mb-3' alt='...'> <p>This feature is under construction. Please check back again later!</>"
//     })
// });

// buttonMobile.addEventListener("click", ()=>{
//     Swal.fire({
//         title: "Not Available",
//         confirmButtonText: 'Close',
//         html: "<img src='./images/construction_icon.svg' style='height:100px; width:100px;'class='rounded mr-2 mb-3' alt='...'> <p>This feature is under construction. Please check back again later!</>"
//     })
// });

// signUpHeaderLink.addEventListener("click", ()=>{
//     loadModal("signup");
// });


// Private functions

// const loadModal = (modalType) => {
//     const modalTypes = {
//         "signup" : "./components/modals/homeowner-register.php",
//         "error" : "./components/modals/error.php" ,
//         "check-avail-est-date" : "./components/modals/check-avail-est-date.php" ,
//         "check-avail-exact-date" : "./components/modals/check-avail-exact-date.php" 
//     }

//     if(modalTypes.hasOwnProperty(modalType)){
//         $("#modal-contents").load(modalTypes[modalType]);
//     } else {
//         $("#modal-contents").load(modalTypes["error"]);
//     }
// }

// $("#RU-close-btn").click((modalType) => {
//     $("#test").load("./components/modals/empty.php");
// });

// const registerHandler =(e)=>{
//     e.preventDefault();

//     // Grab DOM elements
//     const myForm = document.getElementById('registerForm');
//     const RUSignupSubmitButton = document.getElementById("RU-submit-btn");
//     const RUSignupSubmitTxt = document.getElementById("RU-submit-btn-txt");
//     const RUSignupSubmitLoad = document.getElementById("RU-submit-btn-load");

//     // Disable and show loading
//     RUSignupSubmitButton.setAttribute("disabled", "true");
//     RUSignupSubmitTxt.innerHTML = "Loading"
//     RUSignupSubmitLoad.setAttribute("class", "d-inline");
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
//         url : 'https://slim3api.herokuapp.com/user/register',
//         data : data,
//         success : function(response) {
//             var res = JSON.parse(response);
//             console.log(response);

//             // Enable and hide loading
//             RUSignupSubmitButton.removeAttribute("disabled");
//             RUSignupSubmitTxt.innerHTML = "Register"
//             RUSignupSubmitLoad.removeAttribute("class");
//             RUSignupSubmitLoad.setAttribute("class", "d-none");
//             myForm.style.opacity = "1";

//             var elements = myForm.elements;
//             for (var i = 0, len = elements.length; i < len; ++i) {
//                 elements[i].readOnly = false;
//             }

//             Swal.fire({
//                 title: res["success"] ? 'Success!': 'Error!',
//                 text: res["success"].message,
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

//             // Enable and hide loading
//             RUSignupSubmitButton.removeAttribute("disabled");
//             RUSignupSubmitTxt.innerHTML = "Register"
//             RUSignupSubmitLoad.removeAttribute("class");
//             RUSignupSubmitLoad.setAttribute("class", "d-none");
//             myForm.style.opacity = "1";

//             var elements = myForm.elements;
//             for (var i = 0, len = elements.length; i < len; ++i) {
//                 elements[i].readOnly = false;
//             }

//             console.log(response.responseJSON)
//             Swal.fire({
//                 title:'Error!',
//                 text: 'Fields must not be empty',
//                 icon: 'error',
//                 confirmButtonText: 'Close'
//             })
//         },
//     });
// }