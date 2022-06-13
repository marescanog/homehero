$("#modal-trans-decline").validate({
    rules: {
        decline_notes:{
            required: true,
        }
    },
    messages: {
        decline_notes:{
            required: "Please enter the reason for declining the agents request."
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        // console.log("PROCESS DECLINE BEFORE CURL CALL");
        // console.log(formData);

        data = {}

        data["comment"] = formData?.decline_notes;
        data["transfer_type"] = formData?.trans_type;
        data["notif_no"] = formData?.notif_no;

        // console.log("Your data to be submitted to the auth ajax: ");
        // console.log(JSON.stringify(data));

        $.ajax({
            type : 'POST',
            url : getDocumentLevel()+'/auth/ticket/decline-transfer-request.php',
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
                            title: 'Declined sucessfully!',
                            text: message ?? "The agent's request has sucessfully been declined.",
                            icon: "success",
                            }).then(result => {
                                form.reset();
                                $('#modal').modal('hide');
                                $('#modal-trans-decline')[0].reset();
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
                                window.location.reload(true);
                        });
                    } else if (status == 401 || status == 404){
                        Swal.fire({
                            title: 'Bad Request! Check your submission details.',
                            text: message ?? 'Please check your details and try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
                        });
                    } else if (status == 400){
                        Swal.fire({
                            title: 'Bad Request! Check your submission details',
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


        // $('#modal').modal('hide');
        // $('#modal-edit-jo-start-date')[0].reset();
    }
});