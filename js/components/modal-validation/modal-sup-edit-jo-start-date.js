$("#modal-edit-jo-start-date").validate({
    rules: {
        date:{
            required: true,
        },
        time:{
            required: true,
        }
    },
    messages: {
        date:{
            required: "Please select the new start date."
        },
        time:{
            required: "Please select the new start time."
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        // const button = document.getElementById("RU-submit-btn");
        // const buttonTxt = document.getElementById("RU-submit-btn-txt");
        // const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        // disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
        // console.log("EDIT JOB START DATE");
        // console.log(formData);
        // console.log(formData?.date);
        // console.log(formData?.time);
        let newDate = formData?.date ?? "";
        let newTime = formData?.time+":00" ?? "";
        let newDateTime = newDate + " " + newTime;
        // console.log(newDateTime);

        const monthArr = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        let displayString = "";

        // Reformat values for display
        if(newDate != ""){
            // Format Date
            let dateArr = newDate.split("-"); 
            let dateIndex = dateArr[1] >= 1 && dateArr[1] <= 12 ?  dateArr[1] : 1;
            let monthStr = monthArr[dateIndex-1];
            let dayStr = parseInt(dateArr[2]).toString();

            // Format Time
            let timeArr = newTime.split(":"); 
            let hours = timeArr[0];
            let minStr = timeArr[1];
            let hoursStr = (hours % 12).toString();

            displayString = monthStr + " " + dayStr + ", "+ dateArr[0] + " - " + hoursStr + ":" + minStr + " " + (parseInt(hours) > 12 ? "PM" : "AM");

            // console.log(JSON.stringify(monthArr[dateIndex-1]));
        }

        let inpt_jo_start_display = document.getElementById("input_jo_time_start");
        // let inpt_jo_start_value = document.getElementById("input_jo_time_start_value");
        let input_jo_time_start_value_submit = document.getElementById("input_jo_time_start_value_submit");

        // FORMAT FOR DISPLAY IS MM dd, yyyy - h:m am   ex: May 25, 2022 - 1:00 PM

        input_jo_time_start_value_submit.value = newDateTime;
        inpt_jo_start_display.value = displayString;

        // console.log(inpt_jo_start_display);
        // console.log(inpt_jo_start_value);
        // console.log(inpt_jo_start_value.value);
        // console.log(inpt_jo_start_display.placeholder);

        $('#modal').modal('hide');
        $('#modal-edit-jo-start-date')[0].reset();
    }
});