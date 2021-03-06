$("#modal-reschedule-project").validate({
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
            required: 'Please select a new date for your project.',
            min: "Please select a date that is beyond today's date"
        },
        time:{
            required: 'Please select a start time for your project.',
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();
        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        console.log("CANCEL & REPOST");
        console.log(formData);
    }
});