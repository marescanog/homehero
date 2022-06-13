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

            let data ={};
            data['current_pass'] =formData?.current_pass;
            data['new_pass'] =formData?.new_pass;
            data['confirm_pass'] =formData?.confirm_pass;
            $.ajax({
                type : 'POST',
                url : getDocumentLevel()+'/auth/support/change-password.php',
                data : data,
                success : function(response) {
                    console.log("Your response after submission is:");
                    console.log("Response JSON: "+response);
                    if(isJson(response)){
                        let res = JSON.parse(response);
                        let status = res["status"];
                        let message = res["message"];
                    //     // console.log("status: "+status);
                    //     // console.log("message: "+message);
                        if(status==200){      
                            // Unfreeze & Reset
                            Swal.fire({
                                title: 'Password Changed!',
                                text: "You can now use the new password you saved.",
                                icon: "success",
                                }).then(result => {
                                    // form.reset();
                                    // $('#modal').modal('hide');
                                    // $('#modal-perm-password')[0].reset();
                                    // enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "GENERATE NEW CODE");
                                    window.location.reload(true);
                             });
                        } else if (status == 401){
                            Swal.fire({
                                title: 'Session Expired!',
                                text: message ?? 'Please log into your account!',
                                icon: "error",
                                confirmButtonText: 'ok'
                                }).then(result => {
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
                            });
                        } else if (status == 400){
                            Swal.fire({
                                title: 'Oopsie! Error',
                                text: message ?? 'Something went wrong with your request. Please try again!',
                                icon: "error",
                                confirmButtonText: 'ok'
                                }).then(result => {
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
                            });
                        }else{
                            Swal.fire({
                                title: 'Oops! Error!',
                                text: JSON.stringify(message)  ?? 'Something went wrong. Please try again!',
                                icon: "error",
                                confirmButtonText: 'ok'
                                }).then(result => {
                                window.location.reload(true);
                            });
                        }
                    } else {
                        // Error
                        console.log("Your ERROR response after submission is:");
                        console.log("Response JSON: "+response);
                        let message = null;
                        Swal.fire({
                            title: 'Oopsie! Error!',
                            text: JSON.stringify(message) ?? 'Something went wrong. Please try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            window.location.reload(true);
                        });
                    }
                }, 
                error: function(response) {
                    console.log("ERROR - Response JSON: "+response);
                    Swal.fire({
                    title: 'An error occured!',
                    text: 'Something went wrong. Please try again!',
                    icon: "error",
                    confirmButtonText: 'ok'
                    }).then(result => {
                        // window.location.reload(true);
                    });
                    }
                });

        } // submit handler close in Jquery
        
    }); //JQUERY Validator Close
    

});



