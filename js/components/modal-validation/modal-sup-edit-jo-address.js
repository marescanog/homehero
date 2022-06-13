let jo_selected_option_text = null;
let jo_selected_option_value = null;

let close_one_address = document.getElementById("close_one_address");
let close_no_address = document.getElementById("close_no_address"); 

if(close_one_address != null){
    close_one_address.addEventListener("click",()=>{
        $('#modal').modal('hide');
    });
}

if(close_no_address != null){
    close_no_address.addEventListener("click",()=>{
        $('#modal').modal('hide');
    })
}

if($("#modal-enter-address") != null){
    $("#modal-enter-address").ready(()=>{   
        let jo_select_change_address = document.getElementById("jo_change_address_select");

        if(jo_select_change_address != null){
            jo_select_change_address.addEventListener("change",()=>{
                jo_selected_option_text = jo_select_change_address.options[jo_select_change_address.selectedIndex].text;
                jo_selected_option_value = jo_select_change_address.options[jo_select_change_address.selectedIndex].value
            });
        }
    });
    
    $("#modal-enter-address").validate({
        rules: {
            date:{
                required: true,
            }
        },
        messages: {
            date:{
                min: "Please select a date that is beyond today's date"
            }
        },
        submitHandler: function(form, event) { 
            event.preventDefault();
    
            // const button = document.getElementById("RU-submit-btn");
            // const buttonTxt = document.getElementById("RU-submit-btn-txt");
            // const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
            const formData = getFormDataAsObj(form);
            // disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
            // console.log("CHANGE ADDRESS");
            // console.log(formData);

            let homeID = formData["home_id"];

            if(homeID != null){
                let input_jo_address_value = document.getElementById("input_jo_address_value");
                let currentHomeID = input_jo_address_value?.value;

                let input_jo_address_value_submit = document.getElementById("input_jo_address_value_submit");
                let input_jo_address_display = document.getElementById("input_jo_address");
                
                if(jo_selected_option_value != null && input_jo_address_value_submit.value != jo_selected_option_value){
                    input_jo_address_value_submit.value = jo_selected_option_value;
                    input_jo_address_display.value = jo_selected_option_text;
                    // console.log(currentHomeID);
                    // console.log(jo_selected_option_value);
                    // console.log(JSON.stringify(jo_selected_option_value != null));
                    // console.log(JSON.stringify(currentHomeID != jo_selected_option_value));
                    // console.log(JSON.stringify(jo_selected_option_value != null && currentHomeID != jo_selected_option_value));
                    // console.log("Different Address as page!");
                    $('#modal').modal('hide');
                    // $('#modal-enter-address')[0].reset();
                } else {
                    // console.log("Same Address as page!");
                    $('#modal').modal('hide');
                    // $('#modal-enter-address')[0].reset();
                }

                // console.log(homeID);
                // console.log(input_jo_address_value_submit);
                // console.log(jo_selected_option_text);
                // console.log(jo_selected_option_value);
            }
    
        }
    });
}
