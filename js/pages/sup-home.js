$(document).ready(()=>{
    let button_anounce = document.getElementById("add-anouncement");

    if(button_anounce != null){
        button_anounce.addEventListener("click",()=>{
            loadModal("sup-add-anouncement",modalTypes, ()=>{}, getDocumentLevel(), {
            });
        });
    }
}); 


const editAnouncement = (id) => {
    // console.log("Edit anouncement for "+id);
    loadModal("sup-anouncement-edit",modalTypes, ()=>{}, getDocumentLevel(), {
        id:id
    });
}


const viewAnouncement = (id) => {
    // console.log("View anouncement for "+id);
    loadModal("sup-anouncement-show",modalTypes, ()=>{}, getDocumentLevel(), {
        id:id
    });
}


const deleteAnouncement = (id) => {
    // console.log("Delete anouncement for "+id);
    Swal.fire({  
        title: 'Delete anouncement?',  
        text: 'The anouncments and its notes will be deleted.',
        showCancelButton: true,  
        confirmButtonText: `YES`,  
        }).then((result) => {  
            if (result.isConfirmed) {    
            //  ajax to process delete
        // console.log("DELETE ANOUNCEMENT"); 
        data = {}

        data["aid"] = id;

        // console.log("Your data to be submitted to the auth ajax: ");
        // console.log(JSON.stringify(data));

        $.ajax({
            type : 'POST',
            url : getDocumentLevel()+'/auth/support/delete-anouncement.php',
            data : data,
            success : function(response) {
                // console.log("Your response after submission is:");
                // console.log("Response JSON: "+response);
                if(isJson(response)){
                    let res = JSON.parse(response);
                    let status = res["status"];
                    let message = res["message"];
                //     // console.log("status: "+status);
                //     // console.log("message: "+message);
                    if(status==200){      
                        // Unfreeze & Reset
                        // Swal.fire({
                        //     title: 'Sucessfully Deleted Anouncement!',
                        //     text: message ?? "Your anouncement has sucessfully been deleted.",
                        //     icon: "success",
                        //     }).then(result => {
                                $('#modal').modal('hide');
                                window.location.reload(true);
                        // });
                    } else if (status == 401 || status == 404){
                        Swal.fire({
                            title: 'Bad Request! Check your submission details.',
                            text: message ?? 'Please check your details and try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            // enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
                        });
                    } else if (status == 400){
                        Swal.fire({
                            title: 'Bad Request! Check your submission details',
                            text: message ?? 'Something went wrong with your request. Please try again!',
                            icon: "error",
                            confirmButtonText: 'ok'
                            }).then(result => {
                            // enableForm_hideLoadingButton(button, buttonTxt, buttonLoadSpinner, form, "NEXT");
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
        } 
    });
}