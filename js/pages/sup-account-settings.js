$(document).ready(()=>{
    // Validate Change Password form
    $("#profile-change-password").validate({
        rules: {
            current_pass:{
                required: true,
            },
            new_pass:{
                required: true,
            },
            confirm_pass:{
                required: true,
                equalTo : "#new_pass"
            }
        },
        messages: {
            current_pass:{
                required: "Please enter your current password",
            },
            new_pass:{
                required: "Please enter a new password",
            },
            confirm_pass:{
                required: "Please confirm your new password",
                equalTo : "This field must match entered password"
            }
        },
        submitHandler: function(form, event) { 
            event.preventDefault();
            const button = document.getElementById("CPs-submit-btn");
            const buttonTxt = document.getElementById("CPs-submit-btn-txt");
            const buttonLoadSpinner = document.getElementById("CPs-submit-btn-load");
            const formData = getFormDataAsObj(form);
            disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

            // // Add token retrieval then link with change password route
            // // Ajax to get the bearer token
            // $.ajaxSetup({cache: false})
            // $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
            //     //console.log(data)
            //     const parsedSession = JSON.parse(data);
            //     const token = parsedSession['token'];
            //     console.log(token);

            //     // Create new form 
            //     const samoka = new FormData();

            //     // Append information
            //     samoka.append('current_pass', formData["current_pass"]);
            //     samoka.append('new_pass', formData["new_pass"]);
            //     samoka.append('confirm_pass', formData["confirm_pass"]);

            //     // Ajax to save new pasword
            //     $.ajax({
            //         type: 'POST',
            //         // url : '', // prod (No current deployed prod route)
            //         url: 'http://localhost/slim3homeheroapi/public/homeowner/change-password', // dev
            //         contentType: false,
            //         processData: false,
            //         headers: {
            //             "Authorization": `Bearer ${token}`
            //         },
            //         data : samoka,
            //         success : function(response) {
            //             console.log("your response after change password is:")
            //             console.log(response);
            //             Swal.fire({
            //                 title: 'Password Change Success',
            //                 text: 'Password has been changed',
            //                 icon: 'success'
            //             });
            //             document.getElementById("profile-change-password").reset();
            //             enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "CHANGE");
            //         },
            //         error: function (response) {
            //             console.log(response);
            //             let res = response.responseJSON.response;

            //             console.log(res);
            //             if(res?.status == 400){
            //                 Swal.fire({
            //                     title: 'Error: Bad Request',
            //                     text: res?.message,
            //                     icon: 'error'
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     title: 'An error occurred',
            //                     text: 'Please try again',
            //                     icon: 'error'
            //                 });
            //             }
            //             enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "CHANGE");

            //         }
            //     });

            // }); //Ajax close for bearer token

        } // submit handler close in Jquery
        
    }); //JQUERY Validator Close
    

});



