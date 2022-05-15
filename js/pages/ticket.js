const summonZeSpinner = () => {
    Swal.fire({
        title: "",
        imageUrl: getDocumentLevel()+"/images/svg/Spinner-1s-200px.svg",
        imageWidth: 200,
        imageHeight: 200,
        imageAlt: 'Custom image',
        showCancelButton: false,
        showConfirmButton: false,
        background: 'transparent',
        allowOutsideClick: false
    });
}

const killZeSpinner = () => {
    swal.close();
}

// For viweing more details on agent assigned
const popThePopper = (id) => {
  let current_name_el = document.getElementById("ta-new-"+id);
  let previous_name_el = document.getElementById("ta-prev-"+id);
  let date_el = document.getElementById("ta-date-"+id);
  let reason_el = document.getElementById("ta-reason-"+id);
  let current_name = current_name_el != null ? current_name_el.innerText : "";
  let previous_name = previous_name_el != null ? previous_name_el.value: "";
  let date = date_el != null ? date_el.innerText : "";
  let reason = reason_el != null ? reason_el.value: "";
  // console.log($id);
  // console.log(current_name);
  // console.log(previous_name);
  // console.log(date);
  // console.log(reason);

  loadModal("ticket-assignment-history",modalTypes,()=>{},getDocumentLevel(),{
    "current":current_name,
    "previous":previous_name,
    "date":date,
    "reason":reason
  });
}


// =======================================
//    PROCESS WORKER REGISTRATION AJAX
// =======================================
const Process_Worker_Registration = (data = null, button, buttonTxt, buttonLoadSpinner, form) => {
  // Proceed with ajax call using PHP page to add in token
  if(data?.form_action != null){
    // console.log(type)
    // data['type']= data?.form_action;
    // console.log(data);
    $.ajax({
      type : 'POST',
      url : getDocumentLevel()+'/auth/ticket/process-worker-registration.php',
      data : data,
      success : function(response) {
          var res = JSON.parse(response);
          // console.log("Your response after submission is:");
          // console.log(JSON.stringify(res));
          if(res["status"] == 200){
              // Unfreeze & Reset
              enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
              form.reset();
              Swal.fire({
                  title: 'Ticket Updated!',
                  text: 'You have successfully updated this ticket.',
                  icon: "success",
                  }).then(result => {
                    window.location.reload(true);
              })
          } else {
            if(res["status"] == 401){
              Swal.fire({
                title: 'Token Expired!',
                text: res["message"],
                icon: "error",
                confirmButtonText: 'ok'
                }).then(result => {
                  $.ajax({
                    type : 'GET',
                    url : '../../auth/signout_action.php',
                    success : function(response) {
                        var res = JSON.parse(response);
                        if(res["status"] == 200){
                            window.location = getDocumentLevel()+'/pages/support/';
                        }
                    }
                    });
              });
            } else if (res["status"] == 403) {
              Swal.fire({
                title: 'Ticket already assgined!',
                text: res["message"],
                icon: "error",
                confirmButtonText: 'ok'
                }).then(result => {
                  window.location.reload(true);
              });
            } else {
              Swal.fire({
                title: 'Oops! Error!',
                text: 'Something went wrong. Please try again!',
                icon: "error",
                confirmButtonText: 'ok'
                }).then(result => {
                  window.location.reload(true);
              });
            }
          }
      }
    });
  } else {
    console.log("Incorrect type or type is null.")
    Swal.fire({
      title: 'Error!',
      text: 'Something went wrong, Please try again!.',
      icon: 'error',
      confirmButtonText: 'ok'
    });
  }
}

// ====================================
//    PROCESS BILL AJAX
// ====================================
const Process_Bill = (data = null, button, buttonTxt, buttonLoadSpinner, form) => {
  // Proceed with ajax call using PHP page to add in token
  if(data?.form_action != null){
    console.log("Form Data inside of Process Bill"+JSON.stringify(data));
    $.ajax({
      type : 'POST',
      url : getDocumentLevel()+'/auth/ticket/process-bill.php',
      data : data,
      success : function(response) {
        var res = JSON.parse(response);
        // console.log("Your response after submission is:");
        console.log("Response JSON: "+response);

        let status = res["status"];
        let message = res["message"];

        if(status == 200){
              // Unfreeze & Reset
              enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
              form.reset();
              Swal.fire({
                  title: 'Bill Issue Updated!',
                  text: message ?? "The bill issue was sucessfully updated",
                  icon: "success",
                  }).then(result => {
                    window.location.reload(true);
              })
        } else if (status == 400){
          Swal.fire({
            title: 'Oops! Error!',
            text: message ?? 'Something went wrong. Please try again!',
            icon: "error",
            confirmButtonText: 'ok'
            }).then(result => {
              enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
          });
        }else {
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
            window.location.reload(true);
        });
      }
    });
  }
}


$(document).ready(()=>{

// ===================================
//   For Initial Ticket Assignment
// ===================================
  let btn_accept = document.getElementById("tkt-accept-btn");

  if(btn_accept != undefined && btn_accept != null){
    btn_accept.addEventListener("click",()=>{
      let btn_goBack = document.getElementById("tkt-goBack-btn");
      btn_accept.setAttribute("disabled", true);
      btn_goBack.setAttribute("disabled", true);
      summonZeSpinner();

      let tkt_id = document.getElementById("tkt-id");

      // Other additional data necessary for calling api
      let data = {};

      if(tkt_id?.value != null){
          $.ajax({
            type : 'POST',
            url : getDocumentLevel()+'/auth/assign-ticket.php?id='+tkt_id?.value,
            data : data,
            success : function(response) {
                // btn_accept.removeAttribute('disabled');
                // btn_goBack.removeAttribute('disabled');
                killZeSpinner();
                var res = JSON.parse(response);
                // console.log("Your response after asssignment is:");
                // console.log(JSON.stringify(res));
                if(res["status"] == 200){
                    // Unfreeze & Reset
                    // enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
                    // $('#support-login-form')[0].reset();
                    Swal.fire({
                        title: 'Assignment Success!',
                        text: 'You are assigned to this ticket',
                        icon: "success",
                        }).then(result => {
                          window.location.reload(true);
                    })
                } else {
                  if(res["status"] == 401){
                    Swal.fire({
                      title: 'Token Expired!',
                      text: res["message"],
                      icon: "error",
                      confirmButtonText: 'ok'
                      }).then(result => {
                        $.ajax({
                          type : 'GET',
                          url : '../../auth/signout_action.php',
                          success : function(response) {
                              var res = JSON.parse(response);
                              if(res["status"] == 200){
                                  window.location = getDocumentLevel()+'/pages/support/';
                              }
                          }
                          });
                    });
                  } else if (res["status"] == 403) {
                    Swal.fire({
                      title: 'Ticket already assgined!',
                      text: res["message"],
                      icon: "error",
                      confirmButtonText: 'ok'
                      }).then(result => {
                        window.location.reload(true);
                    });
                  } else {
                    Swal.fire({
                      title: 'Oops! Error!',
                      text: 'Something went wrong. Please try again!',
                      icon: "error",
                      confirmButtonText: 'ok'
                      }).then(result => {
                        window.location.reload(true);
                    });
                  }
                }
            }
          });
      }else{ // ticket value if else 
        // Error message saying ticket not found or an aerror has occured
          Swal.fire({
              title: 'Error!',
              text: 'Something went wrong. Please try again!',
              icon: "error",
              }).then(result => {
              window.location.reload(true);
          })
      } // ticket value if else closing bracket
    }); // btn accept add event listener closing bracket
  } // btn accept if closing bracket


let input_form_action = document.getElementById("form_action"); // This is the type
// let input_form_submisssion_type = document.getElementById("form_issue"); 

// This is the route type  
  /*
    1 - Worker Registration
    7 - Billing Issue
  */
// ============================================
//   For Processing a ticket & ticket actions
// ============================================
$("#submit-action").validate({
  rules: {
      // form_comment:{
      //     required: isCommentRequired
      // },
      // password : {
      //     required: true,
      //     maxlength: 30
      // },
  },
  messages: {
      // email: {
      //     email: "Invalid email",
      //     required: "Please enter your email"
      // },
      // password:{
      //     required:  "Please enter your password",
      // }
  },
  submitHandler: function(form, event) { 
      event.preventDefault();
      const submitformData = getFormDataAsObj(form);

      // console.log(submitformData);
      // Grab DOM elements to freeze form and the submit button, initiate loading
      const button = document.getElementById("RU-submit-btn");
      const buttonTxt = document.getElementById("RU-submit-btn-txt");
      const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
      disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
      // console.log(JSON.stringify(submitformData));

      console.log("Form Data Before Switch Types: "+JSON.stringify(submitformData));
// =========================
// Submission Types
// =========================
      switch(submitformData?.form_issue){
        // =========================
        // Just Submit Comment when not authorized
        // =========================
        case "0": 
          console.log("Submit comment")
        break;
        // =========================
        // Worker Registration
        // =========================
        case "1": 
          switch(submitformData?.form_action){
            case "1":
              // console.log("Approve");
              Process_Worker_Registration(
                submitformData, button, buttonTxt, buttonLoadSpinner, form
              );
              break;
            case "2":
              // console.log("Deny");
              if(submitformData?.form_comment == null || submitformData?.form_comment == ""){
                Swal.fire({
                  title: 'Required information!',
                  text: 'Please provide information for your application denial in the comment section.',
                  icon: 'error',
                  confirmButtonText: 'ok'
                });
                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
              } else {
                Process_Worker_Registration( 
                  submitformData, button, buttonTxt, buttonLoadSpinner, form
                );
              }
              break;
            case "3":
              // console.log("Notify");
              if(submitformData?.form_comment == null || submitformData?.form_comment == ""){
                Swal.fire({
                  title: 'Required information!',
                  text: 'Please provide information for your notification in the comment section.',
                  icon: 'error',
                  confirmButtonText: 'ok'
                });
                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
              }else {
                Process_Worker_Registration( 
                  submitformData, button, buttonTxt, buttonLoadSpinner, form
                );
              }
              break;
            case "4":
                // console.log("Comment");
                if(submitformData?.form_comment == null || submitformData?.form_comment == ""){
                  Swal.fire({
                    title: 'Required information!',
                    text: 'Please provide a comment when submitting notes in the comment section.',
                    icon: 'error',
                    confirmButtonText: 'ok'
                  });
                  enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
                }else {
                  Process_Worker_Registration(
                    submitformData, button, buttonTxt, buttonLoadSpinner, form
                  );
                }
              break;
            default:
              break;
          }
          break;
        // =========================
        // Billing Issue
        // =========================
        case "4":
            // console.log("Edit Bill");
            // comment is required
            if(submitformData?.form_comment == null || submitformData?.form_comment == ""){
              Swal.fire({
                title: 'Required information!',
                text: 'Please provide a comment when processing your bill issue.',
                icon: 'error',
                confirmButtonText: 'ok'
              });
              enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "SUBMIT");
            }else {
              switch(submitformData?.form_action){
                case "1":
                  submitformData["type"] = 1;
                  Process_Bill(
                    submitformData, button, buttonTxt, buttonLoadSpinner, form
                  );
                  break;
                case "2":
                  console.log("Cancel Bill");
                  break;
                case "3":
                  console.log("Notify");
                  break;
                case "4":
                  console.log("Add Note");
                  break;
                case "5":
                  console.log("Close Ticket");
                  break;
                default:
                  break;
              }
            }
          break;
        // =========================
        // Job Order Issue
        // =========================
        case "7":
          break;

        // =========================
        // Case Not Found
        // =========================
        default:
          console.log("Out of range");
          break;
      }

  }
});

// 0 - none selected, 1 - approve, 2 - disapprove, 3 - comment only
// =============================================
// Process Worker Registration Code
// =============================================
let btn_action_worker_approve = document.getElementById("btn-worker-reg-approve");
let btn_action_worker_reject = document.getElementById("btn-worker-reg-reject");
let btn_action_worker_notify = document.getElementById("btn-worker-reg-notify");
let btn_action_worker_addInfo = document.getElementById("btn-worker-reg-comment");
let input_comment = document.getElementById("comment");
let arr_btn_worker_reg = [btn_action_worker_approve,btn_action_worker_reject,btn_action_worker_notify,btn_action_worker_addInfo];
  const clear_buttons_worker_reg = () => {
    arr_btn_worker_reg.forEach((btn)=>{
      if (btn.classList.contains('btn-info')) {
        btn.classList.remove('btn-info');
        if (!btn.classList.contains('btn-outline-info')) {
          btn.classList.add('btn-outline-info');
        }
      } 
    });
  }

  if(btn_action_worker_approve != null){
    btn_action_worker_approve.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_approve.classList.add('btn-info');
      input_form_action.setAttribute('value',1);
      input_comment.setAttribute('placeholder',
      "This optional comment visible to the request/ticket author and may be used to add notes or relevatant information."
      );
    });
  }

  if(btn_action_worker_reject != null){
    btn_action_worker_reject.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_reject.classList.add('btn-info');
      input_form_action.setAttribute('value',2);
      input_comment.setAttribute('placeholder',
      "Please provide a reason as to why the application is declined."
      );
    });
  }

  if(btn_action_worker_notify != null){
    btn_action_worker_notify.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_notify.classList.add('btn-info');
      input_form_action.setAttribute('value',3);
      input_comment.setAttribute('placeholder',
      "Please provide a note as to why the ticket author is being notified."
      );
    });
  }

  if(btn_action_worker_addInfo != null){
    btn_action_worker_addInfo.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_addInfo.classList.add('btn-info');
      input_form_action.setAttribute('value',4);
      input_comment.setAttribute('placeholder',
      "Add a note or relevant information for this ticket."
      );
    });
  }


// 0 - none selected, 1 - approve, 2 - disapprove, 3 - comment only
// =============================================
// Process Bill Issue Code
// =============================================
let btn_action_bill_edit = document.getElementById("btn-bill-edit");
let btn_action_bill_cancel = document.getElementById("btn-bill-cancel");
let btn_action_bill_notify = document.getElementById("btn-bill-notify");
let btn_action_bill_addInfo = document.getElementById("btn-bill-comment");
let btn_action_bill_close = document.getElementById("btn-bill-close");

let grp_bill_pay = document.getElementById("grp-bill-pay");
let grp_bill_stat = document.getElementById("grp-bill-stat");
let grp_bill_fee = document.getElementById("grp-bill-fee");
let inpt_bill_pm = document.getElementById("inpt_bill_payment_method");
let inpt_bill_stat = document.getElementById("inpt_bill_status");
let inpt_bill_fee = document.getElementById("inpt_bill_fee_adjustment");

let grp_bill_resolve = document.getElementById("bill-grp-close");
let inpt_bill_resolve1 = document.getElementById("isResolved1");
let inpt_bill_resolve2 = document.getElementById("isResolved2");

let arr_btn_bill_actions = [btn_action_bill_edit,btn_action_bill_cancel,btn_action_bill_notify,btn_action_bill_addInfo,btn_action_bill_close];
  const clear_buttons_bill_actions = () => {
    arr_btn_bill_actions.forEach((btn)=>{
      if (btn!= null && btn.classList.contains('btn-info')) {
        btn.classList.remove('btn-info');
        if (!btn.classList.contains('btn-outline-info')) {
          btn.classList.add('btn-outline-info');
        }
      } 
    });
  }

  const toggleBillForm = () => {
    if (grp_bill_pay.classList.contains('hidden')) {
      grp_bill_pay.classList.remove('hidden');
    } else {
      grp_bill_pay.classList.add('hidden');
    }
    if (grp_bill_stat.classList.contains('hidden')) {
      grp_bill_stat.classList.remove('hidden');
    } else {
      grp_bill_stat.classList.add('hidden');
    }
    if (grp_bill_fee.classList.contains('hidden')) {
      grp_bill_fee.classList.remove('hidden');
    } else {
      grp_bill_fee.classList.add('hidden');
    }
    if(inpt_bill_pm.getAttribute("disabled") == null){
      inpt_bill_pm.setAttribute('disabled',true);
    }else{
      inpt_bill_pm.removeAttribute('disabled');
    }
    if(inpt_bill_stat.getAttribute("disabled") == null){
      inpt_bill_stat.setAttribute('disabled',true);
    }else{
      inpt_bill_stat.removeAttribute('disabled');
    }
    if(inpt_bill_fee.getAttribute("disabled") == null){
      inpt_bill_fee.setAttribute('disabled',true);
    }else{
      inpt_bill_fee.removeAttribute('disabled');
    }
  }

  const openBillForm = () => {
    if (grp_bill_pay.classList.contains('hidden')) {
      grp_bill_pay.classList.remove('hidden');
    } 
    if (grp_bill_stat.classList.contains('hidden')) {
      grp_bill_stat.classList.remove('hidden');
    } 
    if (grp_bill_fee.classList.contains('hidden')) {
      grp_bill_fee.classList.remove('hidden');
    } 
    if(inpt_bill_pm.getAttribute("disabled") != null){
      inpt_bill_pm.removeAttribute('disabled');
    }
    if(inpt_bill_stat.getAttribute("disabled") != null){
      inpt_bill_stat.removeAttribute('disabled');
    }
    if(inpt_bill_fee.getAttribute("disabled") != null){
      inpt_bill_fee.removeAttribute('disabled');
    }
  }

  const closeBillForm = () => {
    if (!grp_bill_pay.classList.contains('hidden')) {
      grp_bill_pay.classList.add('hidden');
    } 
    if (!grp_bill_stat.classList.contains('hidden')) {
      grp_bill_stat.classList.add('hidden');
    } 
    if (!grp_bill_fee.classList.contains('hidden')) {
      grp_bill_fee.classList.add('hidden');
    } 
    if(inpt_bill_pm.getAttribute("disabled") == null){
      inpt_bill_pm.setAttribute('disabled',true);
    }
    if(inpt_bill_stat.getAttribute("disabled") == null){
      inpt_bill_stat.setAttribute('disabled',true);
    }
    if(inpt_bill_fee.getAttribute("disabled") == null){
      inpt_bill_fee.setAttribute('disabled',true);
    }
  }

  const openTicketStatForm = () => {
    if (grp_bill_resolve.classList.contains('hidden')) {
      grp_bill_resolve.classList.remove('hidden');
    } 
    if(inpt_bill_resolve1.getAttribute("disabled") != null){
      inpt_bill_resolve1.removeAttribute('disabled');
    }
    if(inpt_bill_resolve2.getAttribute("disabled") != null){
      inpt_bill_resolve2.removeAttribute('disabled');
    }
  }

  const closeTicketStatForm = () => {
    if (!grp_bill_resolve.classList.contains('hidden')) {
      grp_bill_resolve.classList.add('hidden');
    } 
    if(inpt_bill_resolve1.getAttribute("disabled") == null){
      inpt_bill_resolve1.setAttribute('disabled',true);
    }
    if(inpt_bill_resolve2.getAttribute("disabled") == null){
      inpt_bill_resolve2.setAttribute('disabled',true);
    }
  }

if(btn_action_bill_edit != null){
  btn_action_bill_edit.addEventListener("click",()=>{
    clear_buttons_bill_actions();
    btn_action_bill_edit.classList.add('btn-info');
    input_form_action.setAttribute('value',1);
    input_comment.setAttribute('placeholder',
    "Please provide a reason as to why this bill is being edited."
    );
    // Edit bill form enable 
    openBillForm();
    if(grp_bill_resolve != null){
      closeTicketStatForm();
    }
  });
}

if(btn_action_bill_cancel != null){
  btn_action_bill_cancel.addEventListener("click",()=>{
    clear_buttons_bill_actions();
    btn_action_bill_cancel.classList.add('btn-info');
    input_form_action.setAttribute('value',2);
    input_comment.setAttribute('placeholder',
    "Please provide the bill cancellation reason."
    );
    closeBillForm();
    if(grp_bill_resolve != null){
      closeTicketStatForm();
    }
  });
}

if(btn_action_bill_notify != null){
  btn_action_bill_notify.addEventListener("click",()=>{
    clear_buttons_bill_actions();
    btn_action_bill_notify.classList.add('btn-info');
    input_form_action.setAttribute('value',3);
    input_comment.setAttribute('placeholder',
    "Notify the author of this ticket."
    );
    closeBillForm();
    if(grp_bill_resolve != null){
      closeTicketStatForm();
    }
  });
}

if(btn_action_bill_addInfo != null){
  btn_action_bill_addInfo.addEventListener("click",()=>{
    clear_buttons_bill_actions();
    btn_action_bill_addInfo.classList.add('btn-info');
    input_form_action.setAttribute('value',4);
    input_comment.setAttribute('placeholder',
    "Add a note or relevant information for this ticket."
    );
    closeBillForm();
    if(grp_bill_resolve != null){
      closeTicketStatForm();
    }
  });
}

if(btn_action_bill_close != null){
  btn_action_bill_close.addEventListener("click",()=>{
    clear_buttons_bill_actions();
    btn_action_bill_close.classList.add('btn-info');
    input_form_action.setAttribute('value',5);
    input_comment.setAttribute('placeholder',
    "Add a note or relevant information for this ticket."
    );
    closeBillForm();
    if(grp_bill_resolve != null){
      openTicketStatForm();
    }
  });
}




















}); // Document Ready if closing bracket