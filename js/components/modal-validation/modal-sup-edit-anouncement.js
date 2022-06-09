$("#modal-sup-anouncement").validate({
    rules: {
        title:{
            required: true,
        },
        content:{
            required: true,
        }
    },
    messages: {
        title:{
            required: "Please enter a title for this post."
        },
        content:{
            required: "Please enter the content for this anouncement."
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        console.log("ADD ANOUNCEMENT BEFORE CURL CALL");
        console.log(formData);

        data = {}

        data["title"] = formData?.title;
        data["content"] = formData?.content;
        data["aid"] = formData?.aid;

        // if(formData?.role_restrict != null){
        //     data["role_restrict"] = formData?.role_restrict;
        // }

        // if(formData?.team_restrict != null){
        //     data["team_restrict"] = formData?.team_restrict;
        // }


        console.log("Your data to be submitted to the auth ajax: ");
        console.log(JSON.stringify(data));


        $.ajax({
            type : 'POST',
            url : getDocumentLevel()+'/auth/support/edit-anouncement.php',
            data : data,
            success : function(response) {
                console.log("Your response after submission is:");
                console.log("Response JSON: "+response);
                if(isJson(response)){
                    let res = JSON.parse(response);
                    let status = res["status"];
                    let message = res["message"];
                    console.log("status: "+status);
                    console.log("message: "+message);
                    if(status==200){      
                        // Unfreeze & Reset
                        Swal.fire({
                            title: 'Sucessfully Edited Anouncement!',
                            text: message ?? "Your anouncement has sucessfully been edited.",
                            icon: "success",
                            }).then(result => {
                                form.reset();
                                $('#modal').modal('hide');
                                $('#modal-sup-anouncement')[0].reset();
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
                                window.location.reload(true);
                        });
                    } else if (status == 401 || status == 400){
                        Swal.fire({
                            title: 'Bad Request! Check your submission details.',
                            text: message ?? 'Please check your details and try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
                        });
                    } else if (status == 404){
                        Swal.fire({
                            title: '404: Anouncement not found!',
                            text: message ?? 'Something went wrong with your request. Please try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
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


    //     // $('#modal').modal('hide');
    //     // $('#modal-edit-jo-start-date')[0].reset();
    }
});