$("#modal-report-bill").validate({
    // rules: {
    //     cancellation_reason:{
    //         required: true,
    //     }
    // },
    // messages: {
    //     cancellation_reason:{
    //         required: "Please tell us why you'd like to cancel your project."
    //     }
    // },
    submitHandler: function(form, event) { 
        event.preventDefault();
        const button = document.getElementById("RU-submit-btn");
        const buttonTxt = document.getElementById("RU-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("RU-submit-btn-load");
        const formData = getFormDataAsObj(form);
        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

        console.log("REPORT BILL");
        console.log(formData);

       

            // Create new form 
            const samoka = new FormData();

            // Append information
            samoka.append('author_description', formData["author_description"]);
            // samoka.append('id', formData["id"]);

            $.ajax({
                type: 'POST',
                // url : '', // prod (No current live deployed api route)
                url: 'http://localhost/slim3homeheroapi/public/homeowner/report-billing-issue2/'+formData["id"], // dev
                contentType: false,
                processData: false,
                headers: {
                    "Authorization": `Bearer ${token}`
                },
                data : samoka,
                success : function(response) {
                    console.log("your response after filing biling dispute is:");
                    console.log(response);

                    // Inform user & refresh page
                    Swal.fire({
                    title: 'Success!',
                    text: 'Billing dispute has been filed.',
                    icon: 'success'
                    }).then( result =>{
                        window.location = getDocumentLevel()+'/pages/homeowner/projects.php?tab=closed';
                    });
                    // Enable buttons & close Modal
                    $('#modal').modal('hide');
                },

                // START - ON SERVER ERROR OR JWT ERROR
                error: function (response) {
                    console.log(response);
                    console.log(response.responseJSON);
                    console.log(response.responseJSON['message']);
                    if(response.responseJSON['success'] == false){
                        let message = response.responseJSON['response']['message'];
                        let JWTisssue = false;
                        if(message != undefined && message != null && message != "" && message.substring(0, 3) == "JWT"){
                            message = "Token expired or not recognized. Please try logging into your account again.";
                            JWTisssue = true;
                        }
                        Swal.fire({
                        title: 'An error occurred',
                        text: message,
                        icon: 'error'
                        }).then((result) => {
                            // logout if token expired
                            if(JWTisssue == true){
                                $.ajax({
                                type : 'GET',
                                url : '../../auth/signout_action.php',
                                success : function(response) {
                                    var res = JSON.parse(response);
                                    if(res["status"] == 200){
                                        window.location = '../../';
                                    }
                                }
                                });
                            } else {
                                // Enable buttons & close Modal
                                $('#modal').modal('hide');
                            }
                        })
                    } else {
                        // Inform user
                        Swal.fire({
                        title: 'An error occurred',
                        text: 'Please try again',
                        icon: 'error'
                        });
                        // Enable buttons & close Modal
                        $('#modal').modal('hide');
                    }
                }
                // END - ON SERVER ERROR OR JWT ERROR

            }); // ajax for submission of ticket

        
    } // Bracket close for submit handler

}); // Bracket close ofr $JQUERY Validation