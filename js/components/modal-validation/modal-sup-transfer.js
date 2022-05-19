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
        // console.log("Request Transfer");

        // console.log(formData);
        // console.log(formData?.date);
        // console.log(formData?.time);

        let isValid = true;
        let errMessage = "";
        let data = [];

        let supervisor_type = formData?.supervisor_type ?? "";
        let my_sup = formData?.my_sup ?? "";
        let sup_ID = formData?.sup_ID ?? "";
        let trans_code_1 = formData?.trans_code_1 ?? "";
        let trans_code_2 = formData?.trans_code_2 ?? "";

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
            }
        }

        // Get Relevant Data & Filter
        data['transfer_reason'] = formData?.transfer_reason ?? null;
        data['comments'] = formData?.agent_notes ?? null;

        console.log("Your data to submit to the api.");
        console.log(data);

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
            console.log("Call API");
        }


        // const monthArr = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        // let displayString = "";

        // let inpt_jo_start_display = document.getElementById("input_jo_time_start");
        // // let inpt_jo_start_value = document.getElementById("input_jo_time_start_value");
        // let input_jo_time_start_value_submit = document.getElementById("input_jo_time_start_value_submit");

        // // FORMAT FOR DISPLAY IS MM dd, yyyy - h:m am   ex: May 25, 2022 - 1:00 PM

        // input_jo_time_start_value_submit.value = newDateTime;
        // inpt_jo_start_display.value = displayString;

        // // console.log(inpt_jo_start_display);
        // // console.log(inpt_jo_start_value);
        // // console.log(inpt_jo_start_value.value);
        // // console.log(inpt_jo_start_display.placeholder);

        // $('#modal').modal('hide');
        // $('#modal-edit-jo-start-date')[0].reset();
    }
});