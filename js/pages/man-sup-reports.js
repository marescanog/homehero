$(document).ready(
    ()=>{
// ===================================
// ===================================
// For Radio Button Show/Hide UI
// ===================================
// ===================================
        $("input[name$='report_type']").click(function() {
            var test = $(this).val();
           
            if( !($("#trans_UI_1").hasClass("d-none"))){
                $("#trans_UI_1").addClass("d-none");
            }
            if( !($("#trans_UI_2").hasClass("d-none"))){
                $("#trans_UI_2").addClass("d-none");
            }

            if(  $("#trans_UI_" + test).hasClass("d-none")){
                $("#trans_UI_"+ test).removeClass("d-none");
            }

        });


    const card_generate_report = document.getElementById('report-settings');
    const card_graph_UI = document.getElementById('graph-UI');
    // To reset back to the forms generation
    const new_report_btn = document.getElementById("new-report");
    const print_report_btn = document.getElementById("print-report");

    new_report_btn.addEventListener("click",()=>{
        card_generate_report.classList.remove("d-none");
        card_graph_UI.classList.add("d-none");
    });

// ===================================
// ===================================
// GENERATE REPORT FUNCTIONALITY
// ===================================
// ===================================
const show_report = (btn_data) => {
// id="chart-title"

    // Reset form and show the Charts
    $('#form-sup')[0].reset();
    enableForm_hideLoadingButton(btn_data.button, btn_data.buttonTxt, btn_data.buttonLoadSpinner, btn_data.form, "GENERATE REPORT");
    card_generate_report.classList.add("d-none");
    card_graph_UI.classList.remove("d-none");





    data={};
    data['title'] = "Report Tool"
    // For table generation
    console.log("Loading table");
    $("#table-load").load("../../components/cards/load-table.php", data ,()=>{});

    // For chart Generation
    console.log("Loading chart");
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
          datasets: [{
            data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }
        ]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: false
              }
            }]
          },
          legend: {
            display: false,
          }
        }
      });
}

// ===================================
// ===================================
// For Support Reporting generation
// ===================================
// ===================================
$("#form-sup").validate({
    rules: {
        ticket_type:{
            required: true,
        },
        ticket_status:{
            required: true,
        },
        ticket_filter:{
            required: true,
        },
        ticket_time_period:{
            required: true,
        },
        date_start:{
            required: true,
        },
        date_end:{
            required: true,
        }
    },
    messages: {
        ticket_type:{
            required: "Please select a ticket type."
        },
        ticket_status:{
            required: "Please select a status."
        },
        ticket_filter:{
            required: "Please select a filter."
        },
        ticket_time_period:{
            required: "Please select a time period."
        },
        date_start:{
            required: "Please select a start date."
        },
        date_end:{
            required: "Please select an end date."
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        console.log("SUP REPORTING TOOL BEFORE CURL CALL");
        console.log(formData);

    //     data = {}

    //     data["title"] = formData?.title;
    //     data["content"] = formData?.content;
    //     data["aid"] = formData?.aid;

    //     // if(formData?.role_restrict != null){
    //     //     data["role_restrict"] = formData?.role_restrict;
    //     // }

    //     // if(formData?.team_restrict != null){
    //     //     data["team_restrict"] = formData?.team_restrict;
    //     // }


    //     // console.log("Your data to be submitted to the auth ajax: ");
    //     // console.log(JSON.stringify(data));


    //     $.ajax({
    //         type : 'POST',
    //         url : getDocumentLevel()+'/auth/support/edit-anouncement.php',
    //         data : data,
    //         success : function(response) {
    //             // console.log("Your response after submission is:");
    //             // console.log("Response JSON: "+response);
    //             if(isJson(response)){
    //                 let res = JSON.parse(response);
    //                 let status = res["status"];
    //                 let message = res["message"];
    //                 // console.log("status: "+status);
    //                 // console.log("message: "+message);
    //                 if(status==200){      
    //                     // Unfreeze & Reset
    //                     Swal.fire({
    //                         title: 'Sucessfully Edited Anouncement!',
    //                         text: message ?? "Your anouncement has sucessfully been edited.",
    //                         icon: "success",
    //                         }).then(result => {
    //                             form.reset();
                                    show_report(
                                        {button:button, buttonTxt:buttonTxt, buttonLoadSpinner:buttonLoadSpinner, form:form}
                                    );

    //                             window.location.reload(true);
    //                     });
    //                 } else if (status == 401 || status == 400){
    //                     Swal.fire({
    //                         title: 'Bad Request! Check your submission details.',
    //                         text: message ?? 'Please check your details and try again!',
    //                         icon: "error",
    //                         confirmButtonText: 'ok'
    //                         }).then(result => {
    //                         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
    //                     });
    //                 } else if (status == 404){
    //                     Swal.fire({
    //                         title: '404: Anouncement not found!',
    //                         text: message ?? 'Something went wrong with your request. Please try again!',
    //                         icon: "error",
    //                         confirmButtonText: 'ok'
    //                         }).then(result => {
    //                         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
    //                     });
    //                 }else{
    //                     Swal.fire({
    //                         title: 'Oops! Error!',
    //                         text: JSON.stringify(message)  ?? 'Something went wrong. Please try again!',
    //                         icon: "error",
    //                         confirmButtonText: 'ok'
    //                         }).then(result => {
    //                         window.location.reload(true);
    //                     });
    //                 }
    //             } else {
    //                 // Error
    //                 console.log("Your ERROR response after submission is:");
    //                 console.log("Response JSON: "+response);
    //                 let message = null;
    //                 Swal.fire({
    //                     title: 'Oopsie! Error!',
    //                     text: JSON.stringify(message) ?? 'Something went wrong. Please try again!',
    //                     icon: "error",
    //                     confirmButtonText: 'ok'
    //                     }).then(result => {
    //                     window.location.reload(true);
    //                 });
    //             }
    //         }, 
    //         error: function(response) {
    //             console.log("ERROR - Response JSON: "+response);
    //             Swal.fire({
    //             title: 'An error occured!',
    //             text: 'Something went wrong. Please try again!',
    //             icon: "error",
    //             confirmButtonText: 'ok'
    //             }).then(result => {
    //                 // window.location.reload(true);
    //             });
    //             }
    //         });


    // //     // $('#modal').modal('hide');
    // //     // $('#modal-edit-jo-start-date')[0].reset();
    }
});





// ===================================
// ===================================
// for app reporting generation
// ===================================
// ===================================
$("#form-app").validate({
    rules: {
        app_type:{
            required: true,
        },
        app_filter:{
            required: true,
        },
        app_time_period:{
            required: true,
        },
        date_start:{
            required: true,
        },
        date_end:{
            required: true,
        }
    },
    messages: {
        app_type:{
            required: "Please select a type"
        },
        app_filter:{
            required: "Please select a filter."
        },
        app_time_period:{
            required: "Please select a time period."
        },
        date_start:{
            required: "Please enter a start date."
        },
        date_end:{
            required: "Please enter an end date."
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();

        const button = document.getElementById("AR-submit-btn");
        const buttonTxt = document.getElementById("AR-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("AR-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        console.log("GENERATE APP REPORT BEFORE CURL CALL");
        console.log(formData);

    //     data = {}

    //     data["title"] = formData?.title;
    //     data["content"] = formData?.content;
    //     data["aid"] = formData?.aid;

    //     // if(formData?.role_restrict != null){
    //     //     data["role_restrict"] = formData?.role_restrict;
    //     // }

    //     // if(formData?.team_restrict != null){
    //     //     data["team_restrict"] = formData?.team_restrict;
    //     // }


    //     // console.log("Your data to be submitted to the auth ajax: ");
    //     // console.log(JSON.stringify(data));


    //     $.ajax({
    //         type : 'POST',
    //         url : getDocumentLevel()+'/auth/support/edit-anouncement.php',
    //         data : data,
    //         success : function(response) {
    //             // console.log("Your response after submission is:");
    //             // console.log("Response JSON: "+response);
    //             if(isJson(response)){
    //                 let res = JSON.parse(response);
    //                 let status = res["status"];
    //                 let message = res["message"];
    //                 // console.log("status: "+status);
    //                 // console.log("message: "+message);
    //                 if(status==200){      
    //                     // Unfreeze & Reset
    //                     Swal.fire({
    //                         title: 'Sucessfully Edited Anouncement!',
    //                         text: message ?? "Your anouncement has sucessfully been edited.",
    //                         icon: "success",
    //                         }).then(result => {
    //                             form.reset();
                                show_report(
                                    {button:button, buttonTxt:buttonTxt, buttonLoadSpinner:buttonLoadSpinner, form:form}
                                );
    //                     });
    //                 } else if (status == 401 || status == 400){
    //                     Swal.fire({
    //                         title: 'Bad Request! Check your submission details.',
    //                         text: message ?? 'Please check your details and try again!',
    //                         icon: "error",
    //                         confirmButtonText: 'ok'
    //                         }).then(result => {
    //                         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
    //                     });
    //                 } else if (status == 404){
    //                     Swal.fire({
    //                         title: '404: Anouncement not found!',
    //                         text: message ?? 'Something went wrong with your request. Please try again!',
    //                         icon: "error",
    //                         confirmButtonText: 'ok'
    //                         }).then(result => {
    //                         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
    //                     });
    //                 }else{
    //                     Swal.fire({
    //                         title: 'Oops! Error!',
    //                         text: JSON.stringify(message)  ?? 'Something went wrong. Please try again!',
    //                         icon: "error",
    //                         confirmButtonText: 'ok'
    //                         }).then(result => {
    //                         window.location.reload(true);
    //                     });
    //                 }
    //             } else {
    //                 // Error
    //                 console.log("Your ERROR response after submission is:");
    //                 console.log("Response JSON: "+response);
    //                 let message = null;
    //                 Swal.fire({
    //                     title: 'Oopsie! Error!',
    //                     text: JSON.stringify(message) ?? 'Something went wrong. Please try again!',
    //                     icon: "error",
    //                     confirmButtonText: 'ok'
    //                     }).then(result => {
    //                     window.location.reload(true);
    //                 });
    //             }
    //         }, 
    //         error: function(response) {
    //             console.log("ERROR - Response JSON: "+response);
    //             Swal.fire({
    //             title: 'An error occured!',
    //             text: 'Something went wrong. Please try again!',
    //             icon: "error",
    //             confirmButtonText: 'ok'
    //             }).then(result => {
    //                 // window.location.reload(true);
    //             });
    //             }
    //         });


    // //     // $('#modal').modal('hide');
    // //     // $('#modal-edit-jo-start-date')[0].reset();
    }
});



















    }
);