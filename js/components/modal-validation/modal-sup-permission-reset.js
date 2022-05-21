$("#modal-perm-password").validate({
    rules: {
        password:{
            required: true,
        },
    },
    messages: {
        password:{
            required: "Please enter your password."
        },
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
    //     // console.log("GET NEW CODE");
        // console.log(formData);

        data = {};

        data["password"] = formData?.password;
        data["permission_id"] = formData?.permission_id;
        supID = formData?.supervisor_id;
        if(supID != null){
            data["supervisor_id"] = supID;
        }

        // console.log(formData);

        // Proceed with ajax call to request a new code
        $.ajax({
        type : 'POST',
        url : getDocumentLevel()+'/auth/support/generate-code.php',
        data : data,
        success : function(response) {
            // console.log("Your response after submission is:");
            console.log("Response JSON: "+response);
            if(isJson(response)){
                let res = JSON.parse(response);
                // // console.log("Your response after submission is:");
                // console.log("Response JSON: "+res);
                let status = res["status"];
                let message = res["message"];

                if(status==200){
                    
                    // Unfreeze & Reset
                    Swal.fire({
                        title: 'Permission Code Updated!',
                        text: message ?? "The code  was sucessfully updated",
                        icon: "success",
                        }).then(result => {
                            form.reset();
                            $('#modal').modal('hide');
                            $('#modal-perm-password')[0].reset();
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "GENERATE NEW CODE");
                            window.location.reload(true);
                     });
                } else if (status == 400){
                    Swal.fire({
                        title: 'Oops! Error!',
                        text: message ?? 'Something went wrong. Please try again!',
                        icon: "error",
                        confirmButtonText: 'ok'
                        }).then(result => {
                        enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
                    });
                } else {
                    Swal.fire({
                        title: 'Oops! Error!',
                        text: message ?? 'Something went wrong. Please try again!',
                        icon: "error",
                        confirmButtonText: 'ok'
                        }).then(result => {
                        window.location.reload(true);
                    });
                }
            } else {
                // Error

                // // console.log("Your response after submission is:");
                // console.log("Response JSON: "+res);
                Swal.fire({
                    title: 'Oops! Error!',
                    text: message ?? 'Something went wrong. Please try again!',
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
                window.location.reload(true);
            });
        }
    });
        


    }
});