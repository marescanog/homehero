$("#modal-transfer").ready(()=>{
    $("input[name$='supervisor_type']").click(function() {
        var test = $(this).val();

        $("#trans_UI_1").hide();
        $("#trans_UI_2").hide();
        $("#trans_UI_" + test).show();
    });
});


$("#modal-transfer").validate({
    rules: {
        transfer_reason:{
            required: true,
        },
        agent_notes:{
            required: true,
        }
    },
    messages: {
        transfer_reason:{
            required: "Please select a transfer reason."
        },
        agent_notes:{
            required: "Please explain the reason for the transfer."
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        // console.log("Request Transfer Data: ");
        // console.log(formData);

        let isValid = true;
        let errMessage = "";
        let data = {};

        let supervisor_type = formData?.supervisor_type ?? "";
        let my_sup = formData?.my_sup ?? "";
        let sup_ID = formData?.sup_ID ?? "";
        let trans_code_1 = formData?.trans_code_1 ?? "";
        let trans_code_2 = formData?.trans_code_2 ?? "";
        let ticket_id = formData?.ticket_id ?? "";

        // Basic Validation to Check if trans code/sup id is blank based on the supervisor type selected
        if((supervisor_type =="" || supervisor_type == null) || (supervisor_type == 2) ){
            // console.log("Different Supervisor");
            if(isValid == true && sup_ID == ""){
                errMessage = "Please enter a Supervisor ID for the transfer request.";
                isValid = false;    
            } 

            if(isValid == true && trans_code_2 ==""){
                errMessage = "Please enter a transfer code for the transfer request.";
                isValid = false; 
            }

            if(isValid == true){
                data['sup_id'] = sup_ID;
                data['transfer_code'] = trans_code_2; 
                data['permission_code'] = 1; // Externa Agent Transfer Request
            }
        } else {
            // console.log("Same Sup");
            if(isValid == true && my_sup == ""){
                errMessage = "There was an error processing the request. Please refresh and try again.";
                isValid = false;    
            } 

            if(isValid == true && trans_code_1==""){
                errMessage = "Please enter a transfer code for the transfer request.";
                isValid = false; 
            }

            if(isValid == true){
                data['sup_id'] = my_sup;
                data['transfer_code'] = trans_code_1;
                data['permission_code'] = 3; // Normal Transfer Request
            }
        }

        // Get Relevant Data & Filter
        data['transfer_reason'] = formData?.transfer_reason ?? null;
        data['comments'] = formData?.agent_notes ?? null;
        data['ticket_id'] = ticket_id ?? null;

        // console.log("your ticket id is "+ticket_id);

        // console.log("Your data to submit to the api.");
        // console.log(data);

        if(isValid != true){
            // Swal Error
            Swal.fire({
                title: 'Incomplete information!',
                text: errMessage == ""? 'Something went wrong. Please try again!' : errMessage,
                icon: "error",
                confirmButtonText: 'ok'
                }).then(result => {
                    enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT REQUEST");
                });
        } else{

            // Call Api
            // Proceed with ajax call to send a notification to the supervisor
            $.ajax({
                type : 'POST',
                url : getDocumentLevel()+'/auth/ticket/send_transfer_request.php',
                data : data,
                success : function(response) {
                    // console.log("Your response after submission is:");
                    // console.log("Response JSON: "+response);
                    if(isJson(response)){
                        let res = JSON.parse(response);
                        // // console.log("Your response after submission is:");
                        // // console.log("Response JSON: "+res);
                        let status = res["status"];
                        let message = res["message"];
                        // console.log("status: "+status);
                        // console.log("message: "+message);
                        if(status==200){      
                            // Unfreeze & Reset
                            Swal.fire({
                                title: 'Your supervisor has been notified!',
                                text: message ?? "The request has been sucessfully sent to the supervisor. Please wait for their response.",
                                icon: "success",
                                }).then(result => {
                                    form.reset();
                                    $('#modal').modal('hide');
                                    $('#modal-transfer')[0].reset();
                                    enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "GENERATE NEW CODE");
                                    window.location.reload(true);
                            });
                        } else if (status == 401){
                            Swal.fire({
                                title: 'Incorrect Transfer Code!',
                                text: message ?? 'Please check your transfer code or login details and try again!',
                                icon: "error",
                                confirmButtonText: 'ok'
                                }).then(result => {
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
                            });
                        } else if (status == 400){
                            Swal.fire({
                                title: 'Bad Request! Check your submission details',
                                text: message ?? 'Something went wrong with your request. Please try again!',
                                icon: "error",
                                confirmButtonText: 'ok'
                                }).then(result => {
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
                            });
                        }else{
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
                        console.log("Your ERROR response after submission is:");
                        console.log("Response JSON: "+response);
                        let message = null;
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
                        // window.location.reload(true);
                    });
                    }
                });
            }
    }
});