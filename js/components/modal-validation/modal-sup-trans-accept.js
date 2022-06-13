$("#modal-trans-accept").ready(()=>{
    $("input[name$='agent_type']").click(function() {
        var test = $(this).val();

        $("#trans_UI_1").hide();
        $("#trans_UI_2").hide();
        $("#trans_UI_" + test).show();
    });
});


$("#modal-trans-accept").validate({
    rules: {
        // date:{
        //     required: true,
        // },
        // time:{
        //     required: true,
        // }
    },
    messages: {
        // date:{
        //     required: "Please select the new start date."
        // },
        // time:{
        //     required: "Please select the new start time."
        // }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
        // console.log("PROCESS TRANSFER BEFORE CURL CALL");
        // console.log(formData);

        // Notes:
        // The following information is needed to call api
        //      Notif ID
        //      transfer_to_agent_id -> agent_ID_UI_1 or Agent_ID_UI_2 based on agent_type
        //      approval code if external -> maybe check with trans_type ? <- double check this info later when you are writing this part

        let data = {}; // data to be submitted to the curl

        // Determine which to submit in data if agent_ID_UI_1 OR Agent_ID_UI_2  based on agent_type
        // Default is Agent_ID_UI_2
        let agent_UI_type = formData?.agent_type ?? 2;

        if(agent_UI_type != null && agent_UI_type == 1){
            data["transfer_to_agent_id"] = formData?.agent_ID_UI_1; 
        } else {
            data["transfer_to_agent_id"] = formData?.agent_ID_UI_2;
            data["approval_code"] = formData?.approval_code; 
            data["agent_type"] = formData?.agent_type ?? 2;
        }

        data["notif_ID"] = formData?.notif_no;

        console.log("Your data to be submitted to the auth ajax: ");
        console.log(JSON.stringify(data));

// Check if the select is selected
let selectInputHook = document.getElementById("agent_ID_UI_1");
if(selectInputHook != null && agent_UI_type == 1 && selectInputHook.value == "From your team"){
    // console.log("The value of the select option is: ");
    // console.log(selectInputHook.value);
    // console.log(selectInputHook.value == "From your team");
    Swal.fire({
        title: 'Incomplete Details!',
        text: 'Please select an agent to transfer to!',
        icon: "error",
        confirmButtonText: 'ok'
        }).then(result => {
        enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
    });

} 

else if(data["notif_ID"] == "" || data["transfer_to_agent_id"] == ""){
    Swal.fire({
        title: 'Incomplete Details!',
        text: 'Please select an agent to transfer to!',
        icon: "error",
        confirmButtonText: 'ok'
        }).then(result => {
        enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
    });
    
} else {

        // Call the cURL request trhough ajax:
        // Call Api
        // Proceed with ajax call to send a notification to the supervisor
        $.ajax({
            type : 'POST',
            url : getDocumentLevel()+'/auth/ticket/process-transfer-request.php',
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
                            title: 'Transfer is successful!',
                            text: message ?? "The ticket has sucessfully been transferred to the agent.",
                            icon: "success",
                            }).then(result => {
                                form.reset();
                                $('#modal').modal('hide');
                                $('#modal-trans-accept')[0].reset();
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

        }
        // $('#modal').modal('hide');
        // $('#modal-edit-jo-start-date')[0].reset();
    }
});