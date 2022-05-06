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

$(document).ready(()=>{
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

// =============================================
// Process Worker Registration Code
// =============================================

$("#submit-action").validate({
  rules: {
      // email:{
      //     email: true,
      //     required: true
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
      
      console.log("test")
      console.log(JSON.stringify(submitformData))
      // // NO PROD LINK
      // // Ajax request to login
      // $.ajax({
      //     type: 'POST',
      //     // url : '', //no prod link
      //      url: 'http://localhost/slim3homeheroapi/public/support/login', //dev
      //     data : submitformData,
      //     success : function(response) {
      //         // console.log("your response after account login is:")
      //         // console.log(response);
           

      //         // Redirect to Support Auth then Support Home page
      //         let data = {};
      //         data['token'] = response["response"]["token"]
      //         data['userType'] = 3;
      //         data['email'] = response["response"]["email"];
      //         data['first_name'] = response["response"]["first_name"];
      //         data['last_name'] = response["response"]["last_name"];
      //         data['role'] = response["response"]["role"];
      //         // profile_pic_location
      //         data['profile_pic_location'] = response["response"]["profile_pic_location"];

      //         // console.log(data);

      //         // // console.log("your data token is");
      //         // // console.log(data);
      //         // an ajax to assign registration session token
      //         $.ajax({
      //             type : 'POST',
      //             url : getDocumentLevel()+'/auth/setSupportLoginSession.php',
      //             data : data,
      //             success : function(response) {
      //                 var res = JSON.parse(response);
      //                 // console.log("Your response after register-auth is")
      //                 // console.log(res)
      //                 if(res["status"] == 200){
      //                     // Unfreeze the form & Rest
                          enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
                          $('#submit-action')[0].reset();
      //                     Swal.fire({
      //                         title: 'login success!',
      //                         text: 'Redirecting you to the support dashboard...',
      //                         icon: "success",
      //                         timer: 2500,
      //                         showCancelButton: false,
      //                         showConfirmButton: false,
      //                         timerProgressBar: true,
      //                         }).then(result => {
      //                         window.location = './home.php';
      //                     })
      //                 } else {
      //                     Swal.fire({
      //                         title: 'Error!',
      //                         text: 'Something went wrong! Please try again',
      //                         icon: 'error',
      //                         confirmButtonText: 'ok'
      //                     })
      //                 }
      //             }
      //         });
      //     },
      //     error: function (response) {
      //         // console.log("your response message is")
      //         // console.log(JSON.stringify(response.responseJSON.response));
      //         // console.log("your response response message is")
      //         // console.log(response.responseJSON);
      //         Swal.fire({
      //             title: 'Login Error!',
      //             text: response.responseJSON.response+". Please try again.",
      //             icon: 'error',
      //             confirmButtonText: 'OK'
      //         })
      //         enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "LOGIN");
      //     }
      // });
  }
});


let input_form_action = document.getElementById("form_action");

// =============================================
// Process Worker Registration Code
// =============================================
let btn_action_worker_approve = document.getElementById("btn-worker-reg-approve");
let btn_action_worker_reject = document.getElementById("btn-worker-reg-reject");
let btn_action_worker_addInfo = document.getElementById("btn-worker-reg-comment");
let arr_btn_worker_reg = [btn_action_worker_approve,btn_action_worker_reject,btn_action_worker_addInfo];
  const clear_buttons_worker_reg = () => {
    arr_btn_worker_reg.forEach((btn)=>{
      if (btn.classList.contains('btn-info')) {
        btn.classList.remove('btn-info');
      } 
    });
  }

  if(btn_action_worker_approve != null){
    btn_action_worker_approve.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_approve.classList.add('btn-info');
      input_form_action.setAttribute('value','approve');
    });
  }

  if(btn_action_worker_reject != null){
    btn_action_worker_reject.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_reject.classList.add('btn-info');
      input_form_action.setAttribute('value','reject');
    });
  }

  if(btn_action_worker_addInfo != null){
    btn_action_worker_addInfo.addEventListener("click",()=>{
      clear_buttons_worker_reg();
      btn_action_worker_addInfo.classList.add('btn-info');
      input_form_action.setAttribute('value','comment');
    });
  }

}); // Document Ready if closing bracket