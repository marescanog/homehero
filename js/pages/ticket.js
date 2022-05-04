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
});