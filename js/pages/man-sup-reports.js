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
const show_report = (report_data, btn_data) => {

    // console.log(JSON.stringify(report_data))
    const chartTitle = document.getElementById("chart-title");

    // Reset form and show the Charts
    $('#form-sup')[0].reset();
    enableForm_hideLoadingButton(btn_data.button, btn_data.buttonTxt, btn_data.buttonLoadSpinner, btn_data.form, "GENERATE REPORT");
    card_generate_report.classList.add("d-none");
    card_graph_UI.classList.remove("d-none");

    let ticketsData = report_data?.ticketsData ?? [];
    let ticket_time_period = report_data?.ticket_time_period ?? 1; // 1-Daily (default), 2-Weekly, 3-Monthly
    let ticket_type = report_data?.ticket_type ?? 1; // 1-All (default), 2-Verification, 3-Support
    let ticket_filter = report_data?.ticket_filter ?? 1; // 1-All (default), 2-By Team, 3-By Agent
    let agent_name = report_data?.agent_name ?? "";
    let table_data_per_team = report_data?.table_data_per_team ?? [];
    let my_team = report_data?.my_team ?? [];
    let table_data_per_team_totals = report_data?.table_data_per_team_totals ?? [];

    let chartTitleText = ticket_type == 1 ? "All Tickets" : (ticket_type == 2 ? "Verification Tickets" : (ticket_type == 3 ? "Customer Support Tickets" : "Support Tickets"));
    
    chartTitleText = chartTitleText + (ticket_filter == 1 ? " by all agents" : (ticket_filter == 2 ? " by Team" : (ticket_filter == 3 ? " by Agent" : "")));
    
    chartTitle.innerText = chartTitleText; 

    // console.log(JSON.stringify(report_data));
    console.log(ticketsData);

    const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    const dow = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    let chartLabels = [];
    let chartData = [];

    ticketsData.forEach(el=>{
        if(ticket_time_period == 2){

        } else if (ticket_time_period == 3){
            let labl = months[el?.month-1]+'-'+el?.year;
            chartLabels.unshift(labl);
        } else {
            let labl = months[el?.month-1]+'-'+el?.day;
            chartLabels.unshift(labl);
        }
        chartData.unshift(el?.totalCount);
    });

    // ======================
    // For table generation
    data={};
    let subTitleText = ticket_time_period == 1 ? "Daily Tickets Filed" : (ticket_time_period == 2 ? "Tickets Filed Per Week": (ticket_time_period == 3 ? "Tickets Filed Per Month": "For the Time Period"));
    subTitleText = subTitleText + (ticket_filter == 1 ? " by all agents" : (ticket_filter == 2 ? " by "+agent_name : (ticket_filter == 3 ? " by "+agent_name : "")));
    data['title'] = subTitleText;
    
    // data['data'] = ticketsData;
    data['chartLabels'] = chartLabels;
    data['chartData'] = chartData;
    data['filterType'] = ticket_filter;
    if(ticket_filter == 2 ){
        data['table_data_per_team'] = table_data_per_team;
        data['my_team'] = my_team;
        data['table_data_per_team_totals'] = table_data_per_team_totals;
    }

    console.log("Loading table");
    $("#table-load").load("../../components/cards/load-table.php", data ,()=>{});


    // =======================
    // For chart Generation
    console.log("Loading chart");
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: [{
            data: chartData,
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
    // var myChart = new Chart(ctx, {
    //     type: 'line',
    //     data: {
    //       labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    //       datasets: [{
    //         data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
    //         lineTension: 0,
    //         backgroundColor: 'transparent',
    //         borderColor: '#007bff',
    //         borderWidth: 4,
    //         pointBackgroundColor: '#007bff'
    //       }
    //     ]
    //     },
    //     options: {
    //       scales: {
    //         yAxes: [{
    //           ticks: {
    //             beginAtZero: false
    //           }
    //         }]
    //       },
    //       legend: {
    //         display: false,
    //       }
    //     }
    //   });
}

// ===================================
// ===================================
// For Support Reporting generation
// ===================================
// ===================================
const ticket_filter_select = document.getElementById("ticket_filter");
const ticket_filter_team = document.getElementById("ticket_filter_team");
const ticket_filter_agent = document.getElementById("ticket_filter_agent");

const ticket_type_select = document.getElementById("ticket_type");
const ticket_select_agent =  document.getElementById("ticket_select_agent");

// UX for dropdown select of Ticket Type
// If Cx agents and Cx ticket filter, then only Cx agents will show, etc.
ticket_type_select.addEventListener('change', function () {
    var value = this.value;
    let options = ticket_select_agent.options;

    // Enable Ticket Filter
    if( ticket_filter_select.hasAttribute("disabled")){
        ticket_filter_select.removeAttribute("disabled");
    }

    // Set the options for agent select
    for (var option of options) {
        // console.log(option);
        // console.log(value==1);

        if(value==1){
            if(option.classList.contains("d-none")){
                option.classList.remove("d-none");
            }
        }

        if(value==2){
            if(option.classList.contains("d-none") && option.classList.contains("ver") ){
                option.classList.remove("d-none");
            }
            if(!(option.classList.contains("d-none")) && option.classList.contains("cx")){
                option.classList.add("d-none");
            }
        }

        if(value==3){
            if(option.classList.contains("d-none") && option.classList.contains("cx")){
                option.classList.remove("d-none");
            }
            if(!(option.classList.contains("d-none")) && option.classList.contains("ver")){
                option.classList.add("d-none");
            }
        }
    }
});



ticket_filter_select.addEventListener('change', function () {
    var value = this.value;
    if(value == 1){
        if(!(ticket_filter_agent.classList.contains("d-none"))){
            ticket_filter_agent.classList.add("d-none");
        }
        if(!(ticket_filter_team.classList.contains("d-none"))){
            ticket_filter_team.classList.add("d-none");
        }
    }
    if(value == 2){ // team
        if(ticket_filter_team.classList.contains("d-none")){
            ticket_filter_team.classList.remove("d-none");
        }
        if(!(ticket_filter_agent.classList.contains("d-none"))){
            ticket_filter_agent.classList.add("d-none");
        }
    }
    if(value == 3){ // agent
        if(!(ticket_filter_team.classList.contains("d-none"))){
            ticket_filter_team.classList.add("d-none");
        }
        if(ticket_filter_agent.classList.contains("d-none")){
            ticket_filter_agent.classList.remove("d-none")
        }
    }
});

$("#form-sup").validate({
    rules: {
        ticket_type:{
            required: true,
        },
        ticket_status:{
            required: true,
        },
        agent_id:{
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
        agent_id:{
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

        // console.log("SUP REPORTING TOOL BEFORE CURL CALL");
        // console.log(formData);

        data = {}

        data["ticket_type"] = formData?.ticket_type;
        data["agent_id"] = formData?.agent_id;
        data["ticket_status"] = formData?.ticket_status;
        data["ticket_filter"] = formData?.ticket_filter;
        data["ticket_time_period"] = formData?.ticket_time_period;
        data["date_start"] = formData?.date_start;
        data["date_end"] = formData?.date_end;


        if(formData?.ticket_filter == 1){
            data["agent_name"] = "";
            data["agent_id"] = -1;
        } else if (formData?.ticket_filter == 2) {
            let team_dom = document.getElementById("ticket_select_team");
            let strUser = team_dom.options[team_dom.selectedIndex].text;
            data["agent_name"] = strUser;
            data["agent_id"] = team_dom.value;
        } else if (formData?.ticket_filter == 3) {
            let agent_dom = document.getElementById("ticket_select_agent");
            let strUser = agent_dom.options[agent_dom.selectedIndex].text;
            data["agent_name"] = strUser;
            data["agent_id"] = agent_dom.value;
        }

      
        // console.log("Your data to be submitted to the ajax call: ");
        // console.log(JSON.stringify(data));


        $.ajax({
            type : 'POST',
             url : "http://localhost/slim3homeheroapi/public/ticket/get-report", // DEV
            data : data,
            success : function(response) {
                // console.log("Your response after submission is:");
                // console.log("Response JSON: "+response);
                // console.log(JSON.stringify(response));
                // if(isJson(response)){
                    // let res = JSON.parse(response);
                    // let status = res["status"];
                    // let message = res["message"];
                    let success = response?.success;
                    let message = response?.response;
                    // console.log("success: "+success);
                    // console.log("message: "+message);
                    // console.log(response['response']);
                    if(success==true){      
    //                     // Unfreeze & Reset
    //                     Swal.fire({
    //                         title: 'Sucessfully Edited Anouncement!',
    //                         text: message ?? "Your anouncement has sucessfully been edited.",
    //                         icon: "success",
    //                         }).then(result => {
    //                             form.reset();
                                    show_report(
                                        response['response'],
                                        {button:button, buttonTxt:buttonTxt, buttonLoadSpinner:buttonLoadSpinner, form:form}
                                    );

    //                             window.location.reload(true);
                        // });
                    // } else if (status == 401 || status == 400){
                    //     Swal.fire({
                    //         title: 'Bad Request! Check your submission details.',
                    //         text: message ?? 'Please check your details and try again!',
                    //         icon: "error",
                    //         confirmButtonText: 'ok'
                    //         }).then(result => {
                    //         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
                    //     });
                    // } else if (status == 404){
                    //     Swal.fire({
                    //         title: '404: Anouncement not found!',
                    //         text: message ?? 'Something went wrong with your request. Please try again!',
                    //         icon: "error",
                    //         confirmButtonText: 'ok'
                    //         }).then(result => {
                    //         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "EDIT ANOUNCEMENT");
                    //     });
                    }else{
                        Swal.fire({
                            title: 'Oops! Error!',
                            text: JSON.stringify(message)  ?? 'Something went wrong. Please try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            // window.location.reload(true);
                            enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "GENERATE REPORT");
                        });
                    }
                // } else {
                //     // Error
                //     console.log("Your ERROR response after submission is:");
                //     console.log("Response JSON: "+response);
                //     let message = null;
                //     Swal.fire({
                //         title: 'Oopsie! Error!',
                //         text: JSON.stringify(message) ?? 'Something went wrong. Please try again!',
                //         icon: "error",
                //         confirmButtonText: 'ok'
                //         }).then(result => {
                //         window.location.reload(true);
                //     });
                // }
            }, 
            error: function(response) {
                console.log("ERROR - Response JSON: ");
                console.log(JSON.stringify(response))
                console.log(JSON.stringify(response.responseText))
                let he = response.responseText;
                console.log(JSON.stringify(he == null ? "" : he?.response))
                Swal.fire({
                title: 'An error occured!',
                text: he == null ? 'Something went wrong. Please try again!' : he,
                icon: "error",
                confirmButtonText: 'ok'
                }).then(result => {
                    enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "GENERATE REPORT");
                    // window.location.reload(true);
                });
                }
            });


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