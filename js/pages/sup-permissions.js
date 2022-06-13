$(document).ready(()=>{
// ======================================
//              SUPERVISOR
// ======================================
    let btn_gen_transfer = document.getElementById("btn_gen_transfer");
    let btn_see_transfer_code = document.getElementById("btn-see-transfer-code");
    if(btn_gen_transfer != null){
        btn_gen_transfer.addEventListener("click",()=>{
        loadModal("sup-perm-enter-pass",modalTypes,()=>{},getDocumentLevel(),{
            permission_code: 3 // 1-External Agent Transfer Request, 2 -Reassign Ticket of External Agent, 3- Transfer Request
        });
      });
    }
    // Accompanying show hide for the code
    // console.log(btn_see_transfer_code);
    if(btn_see_transfer_code != null){
        var input_see_transfer_code = document.getElementById("input-see-transfer-code");
        $('#btn-see-transfer-code').hover(function() {
            if(input_see_transfer_code.value != "" && input_see_transfer_code.value != null){
                // console.log("hover in");
                $('#b-3-key').addClass( "d-none" );
                $('#b-3-eye').removeClass( "d-none" );
                input_see_transfer_code.type = "text";
            }
        }, function() {
            if(input_see_transfer_code.value != "" && input_see_transfer_code.value != null){
                // console.log("hover out");
                // on mouseout, reset
                $('#b-3-key').removeClass( "d-none" );
                $('#b-3-eye').addClass( "d-none" );
                input_see_transfer_code.type = "password";
            }
        });
    }

    let btn_gen_transfer_b = document.getElementById("btn_gen_transfer_b");
    let btn_see_transfer_code_b = document.getElementById("btn-see-transfer-code_b");
    if(btn_gen_transfer_b != null){
        btn_gen_transfer_b.addEventListener("click",()=>{
            console.log("Send Notification!");
            let data ={};
            $.ajax({
                type : 'POST',
                url : getDocumentLevel()+'/auth/ticket/send-request-to-manager.php',
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
                                title: 'Notification Sent!',
                                text: "The manager was notified to reset your access code.",
                                icon: "success",
                                }).then(result => {
                                    // form.reset();
                                    // $('#modal').modal('hide');
                                    // $('#modal-perm-password')[0].reset();
                                    // enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "GENERATE NEW CODE");
                                    window.location.reload(true);
                             });
                        } else if (status == 401){
                            Swal.fire({
                                title: 'Session Expired!',
                                text: message ?? 'Please log into your account!',
                                icon: "error",
                                confirmButtonText: 'ok'
                                }).then(result => {
                                enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
                            });
                        } else if (status == 400){
                            Swal.fire({
                                title: 'Oopsie! Error',
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
      
        });
    }
    // Accompanying show hide for the code
    // console.log(btn_see_transfer_code);
    if(btn_see_transfer_code_b != null){
        var input_see_transfer_code_b = document.getElementById("input-see-transfer-code_b");
        $('#btn-see-transfer-code_b').hover(function() {
            if(input_see_transfer_code_b.value != "" && input_see_transfer_code_b.value != null){
                // console.log("hover in");
                $('#b-3-key_b').addClass( "d-none" );
                $('#b-3-eye_b').removeClass( "d-none" );
                input_see_transfer_code_b.type = "text";
            }
        }, function() {
            if(input_see_transfer_code_b.value != "" && input_see_transfer_code_b.value != null){
                // console.log("hover out");
                // on mouseout, reset
                $('#b-3-key_b').removeClass( "d-none" );
                $('#b-3-eye_b').addClass( "d-none" );
                input_see_transfer_code_b.type = "password";
            }
        });
    }


// ======================================
//              MANAGER
// ======================================
    // Grab a list of supervisors
    const collection = document.getElementsByClassName("supList");
    // console.log(collection);
    let supID_arr = [];
    if(collection.length != 0){
        Array.from(document.getElementsByClassName("supList")).forEach(function(item) {
            let el_text = item?.innerText;
            if(el_text != null && el_text != ""){
                let E_ID = el_text.split(" - ")[0];
                let sup_id = E_ID.split(" ")[1];
                let final = parseInt(sup_id);
                // console.log(final);
                supID_arr.push(final);
            }  
            // console.log(item.innerText);
        });
    }
    // console.log(supID_arr)

    // Hook ID Format for the "See button" (one with eye & key) is btn_see-"supID"-"permission code"
        // Example:  "btn_see-28-1" or "btn_see-186-1"
        // To ensure there is a unique ID hook
        // let btn_see_arr = [];
        for(let xn = 0; xn < 3; xn++){
            supID_arr.forEach((id)=>{
                let Id_Hook = "#btn_see-"+id+"-"+(xn+1);
                let input_Hook = "input-"+id+"-"+(xn+1);
                let icon_key_Hook = "#b_key-"+id+"-"+(xn+1);
                let icon_eye_Hook = "#b_eye-"+id+"-"+(xn+1);
                let input_el = document.getElementById(input_Hook);
                let button_Hook = "btn_gen-"+id+"-"+(xn+1);
                if(input_el != null){
                    $(Id_Hook).hover(function() {
                        console.log(Id_Hook+" enter");
                        if(input_el.value != "" && input_el.value != null){
                            // console.log("hover in");
                            $(icon_key_Hook).addClass( "d-none" );
                            $(icon_eye_Hook).removeClass( "d-none" );
                            input_el.type = "text";
                        }
                    }, function() {
                        // console.log(Id_Hook+" leave");
                        if(input_el.value != "" && input_el.value != null){
                            // console.log("hover out");
                            // on mouseout, reset
                            $(icon_key_Hook).removeClass( "d-none" );
                            $(icon_eye_Hook).addClass( "d-none" );
                            input_el.type = "password";
                        }
                    });

                    let button = document.getElementById(button_Hook);
                    // console.log(button);
                    if(button != null){
                        button.addEventListener("click",()=>{
                            // console.log(button_Hook);
                            loadModal("sup-perm-enter-pass",modalTypes,()=>{},getDocumentLevel(),{
                                permission_code: 1, // 1-External Agent Transfer Request, 2 -Reassign Ticket of External Agent, 3- Transfer Request
                                supervisor_id: id
                            });
                        });
                    }
                }
                
            })
        }



    // Forget the code below, it has to be dynamically generated based on number of supervisors
    // ========== OVERRIDE A ============

    // let btn_gen_man_extrernal_transfer_generate = document.getElementById("btn_gen_man_extrernal_transfer_generate");
    // let btn_see_man_extrernal_transfer_generate = document.getElementById("btn-see-man-extrernal-transfer-generate");

    // if(btn_gen_man_extrernal_transfer_generate != null){
    //     btn_gen_man_extrernal_transfer_generate.addEventListener("click",()=>{
    //     loadModal("sup-perm-enter-pass",modalTypes,()=>{},getDocumentLevel(),{
    //         permission_code: 1 // 1-External Agent Transfer Request, 2 -Reassign Ticket of External Agent, 3- Transfer Request
    //     });
    //   });
    // }
    // // Accompanying show hide for the code
    // // console.log(btn_see_transfer_code);
    // if(btn_see_man_extrernal_transfer_generate != null){
    //     var input_see_man_extrernal_transfer_generate = document.getElementById("input-see-man-extrernal-transfer-generate");
    //     $('#btn-see-man-extrernal-transfer-generate').hover(function() {
    //         if(input_see_man_extrernal_transfer_generate.value != "" && input_see_man_extrernal_transfer_generate.value != null){
    //     //         // console.log("hover in");
    //             $('#b-3-key-man-extrernal').addClass( "d-none" );
    //             $('#b-3-eye-man-extrernal').removeClass( "d-none" );
    //             input_see_man_extrernal_transfer_generate.type = "text";
    //         }
    //     }, function() {
    //         if(input_see_man_extrernal_transfer_generate.value != "" && input_see_man_extrernal_transfer_generate.value != null){
    //     //         // console.log("hover out");
    //     //         // on mouseout, reset
    //             $('#b-3-key-man-extrernal').removeClass( "d-none" );
    //             $('#b-3-eye-man-extrernal').addClass( "d-none" );
    //             input_see_man_extrernal_transfer_generate.type = "password";
    //         }
    //     });
    // }


    // ========== OVERRIDE B ============

    // let btn_gen_man_approve_override = document.getElementById("btn_gen_man_approve_override");
    // let btn_see_man_approve_override = document.getElementById("btn-see-man-approve-override");
    // if(btn_gen_man_approve_override != null){
    //     btn_gen_man_approve_override.addEventListener("click",()=>{
    //     loadModal("sup-perm-enter-pass",modalTypes,()=>{},getDocumentLevel(),{
    //         permission_code: 2 // 1-External Agent Transfer Request, 2 -Reassign Ticket of External Agent, 3- Transfer Request
    //     });
    //   });
    // }
    // // // Accompanying show hide for the code
    // // // console.log(btn_see_transfer_code);
    // if(btn_see_man_approve_override != null){
    //     var input_see_man_approve_override = document.getElementById("input-see-man-approve-override");
    //     $('#btn-see-man-approve-override').hover(function() {
    //         if(input_see_man_approve_override.value != "" && input_see_man_approve_override.value != null){
    // //             // console.log("hover in");
    //             $('#b-3-key-man-approve-override').addClass( "d-none" );
    //             $('#b-3-eye-man-approve-override').removeClass( "d-none" );
    //             input_see_man_approve_override.type = "text";
    //         }
    //     }, function() {
    //         if(input_see_man_approve_override.value != "" && input_see_man_approve_override.value != null){
    // //             // console.log("hover out");
    // //             // on mouseout, reset
    //             $('#b-3-key-man-approve-override').removeClass( "d-none" );
    //             $('#b-3-eye-man-approve-override').addClass( "d-none" );
    //             input_see_man_approve_override.type = "password";
    //         }
    //     });
    // }

// ======================================
//              ADMIN
// ======================================

});