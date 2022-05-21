$(document).ready(()=>{
   
// ==================
// Show Entries Limit
let show_entries = document.getElementById("btn-entry-select");
let show_10 = document.getElementById("btn-select-10");
let show_20 = document.getElementById("btn-select-20");
let show_30 = document.getElementById("btn-select-30");

show_10.addEventListener("click", ()=>{
    show_entries.innerText = "Show 10 Entries";
    addURLParameter(["limit","page"],[10,1]);
});

show_20.addEventListener("click", ()=>{
    show_entries.innerText = "Show 20 Entries";
    addURLParameter(["limit","page"],[20,1]);
});

show_30.addEventListener("click", ()=>{
    show_entries.innerText = "Show 30 Entries";
    addURLParameter(["limit","page"],[30,1]);
});

// // ==================
// // Pagination
// // Get Values
    let my_limit = document.getElementById("limit").value;
    let my_page = document.getElementById("page").value;
    let my_tab = document.getElementById("tab").value;
    let my_new = document.getElementById("total-new").value;
    let my_read = document.getElementById("total-read").value;
    let my_all = document.getElementById("total-all").value;
    let my_done = document.getElementById("total-done").value;
    let ultimatotal = [my_new,my_read,my_all,my_done];
// Get Hooks
    let prev_page = document.getElementById("pag-prev");
    let next_page = document.getElementById("pag-next");

prev_page.addEventListener("click", ()=>{
    if(my_page != 1){
        addURLParameter("page",parseInt(my_page)-1);   
    }
});

next_page.addEventListener("click", ()=>{
    if(my_page != Math.ceil(ultimatotal[my_tab]/my_limit)){
        addURLParameter("page",parseInt(my_page)+1);
    }
});

// //CLICKABLE BUTTONS
let starting_page = 0;
let paginationBaseTotal = ultimatotal[my_tab];
let numberOfPages = Math.ceil(paginationBaseTotal/my_limit);
let over_lim = numberOfPages-5;
let ciel_page =  over_lim <= 0 ? (numberOfPages <= 5 ? 1 : my_page ) : over_lim+1;
let my_page_buttons = [];

// console.log(paginationBaseTotal);

for(let titi = my_page > ciel_page ? ciel_page : my_page, pagLimit = 0; pagLimit < 5 && titi <= numberOfPages ; titi++, pagLimit++){
    my_page_buttons.push( document.getElementById("pag-"+titi));
    // console.log(document.getElementById("pag-"+titi));
}

// console.log(my_page_buttons);
my_page_buttons.map((btn, index)=>{
    btn.addEventListener("click", ()=>{
        if(numberOfPages <= 5){
            addURLParameter("page",index+1);
        } else {
            addURLParameter("page",btn.id.split("-")[1]);
        }
    })
});



// ==================
// Show Notifications List based on Role & Tab
//      Role -> Number of Cols are different
//      Tabs -> Number of Buttons are different
/* 
    Supervisor, Manager, Admin & SuperAdmin: (4,5,6,7) greater than or equal to 4... Matrix?
        New - 4 (Accept, Read, Decline, Delete)
        Read - 4 (Accept, Read, Decline, Delete)
        All - 3 Accept, Decline, Delete)
        Done -1 (Delete)

    Agents (Verification, etc..); Less than 4
        New -
        Read -
        All -
        Done -
*/
// -----------------------------------------------
// Variables used by all in notifications display
// let paginationBaseTotal = 1; //temp
let button_id_start_labels = ["new","read","all","done"]; // All pages have this
let current_label = button_id_start_labels[my_tab]; // console.log(current_label);
let table = document.getElementById('table-hook-'+button_id_start_labels[my_tab]);
// For reference
let notif_type_arr_complete = ["Follow Up","Transfer Req.","Escalation Req.","Access Req.","Override Req.","Override Notice"];

// Separate by role
    // Grab Role ID
    roleHook = document.getElementById("r");
    role = roleHook == null ? 0 : roleHook?.value;
    // -----------------------------------
    // Operations
    if(role >= 4){
        // console.log("This is operations!");
        let buttons_matrix // index is tab number
                = [
                    ["accept","read","decline","delete"],
                    ["accept","read","decline","delete"],
                    ["accept","decline","delete"],
                    ["delete"]
                  ]; 

        let supportTicket_col_num_arr = [10,0,0,0]; // index is tab number, starts at 0 for columns
        let type_col_num = 3; // index is tab number
        let noButtons = [
                [],
                [],
                [],
                ["Follow Up","Override Req.","Override Notice"], // Supervisor
                [],
                [],
                [] // Manager
        ]; // first index is role

        // // prints out the button names based on tab displayed - for debugging
        // for(let x_b = 0; x_b < (buttons_matrix[my_tab]).length; x_b++){
        //     console.log(buttons_matrix[my_tab][x_b]);
        // }

        // Get the button in the table.
            // Determine the number of entries - paginationBaseTotal & current tab
            // console.log("Pagination base total is: "+ paginationBaseTotal);
            let button_id_mid = buttons_matrix[my_tab];
            for(let x = 0; x < paginationBaseTotal; x++){
                // Variables Based on Row
                    let idHook = document.getElementById(current_label+'-'+x.toString());
                    let id = idHook==null?"":parseInt(idHook.innerText); // This is the notification ID

                    // let noButtons = table.rows[type_col_num].cells[2].innerText; // For reference
                    let row_type = (table==null||table==undefined)?"":(table?.rows.length <= 1 ? "" : table?.rows[x+1]?.cells[type_col_num-1]?.innerText);
                    let row_has_no_buttons = noButtons[role-1].includes(row_type);

                    let support_ticket_id_col_num = supportTicket_col_num_arr[my_tab];
                    let support_ticket_id = (table==null||table==undefined)?"":(table?.rows.length <= 1 ? "" :table?.rows[x+1]?.cells[support_ticket_id_col_num]?.innerText);
                    let transfer_type = 1+notif_type_arr_complete.findIndex(e=>{return e==row_type;});
                    
                    // console.log("notification is: "+id);

                    if(row_type != null){
                        button_id_mid.forEach((button_name)=>{
                            let button_hook_id = 'btn-'+current_label+'-'+button_name+'-'+x+'-'+id.toString();
                            // console.log(button_hook_id);
                            let button_hook = document.getElementById(button_hook_id);
                            if(row_has_no_buttons){
                                // Add Disable & Gray Out
                                if(button_name=="read"){
                                    button_hook.addEventListener("click",()=>{
                                        console.log("row-"+x+"-read");
                                    });
                                } else {
                                    button_hook.setAttribute("disabled", "true");
                                    button_hook.classList.remove("btn-primary");
                                    button_hook.classList.remove("btn-danger");
                                    button_hook.classList.remove("btn-success");
                                    button_hook.classList.add("btn-secondary");
                                    button_hook.style.opacity = "0.25";
                                }
                            } else {
                                // Add Event Listener
                                if(button_hook != null){
                                    button_hook.addEventListener("click", ()=>{
                                        switch(button_name){
                                            case "accept":
                                                console.log("row-"+x+"-accept");
                                                // console.log("notification is "+id);
                                                runAccept(
                                                    support_ticket_id, // support ticket ID
                                                    id,                 // notification ID
                                                    transfer_type            // transfer type 1-transfer, 2-escalation
                                                );
                                                break;
                                            case "read":
                                                console.log("row-"+x+"-read");
                                                runRead(id);
                                                break;
                                            case "decline":
                                                runDecline(id);
                                                console.log("row-"+x+"-decline");
                                                break;
                                            case "delete":
                                                console.log("row-"+x+"-delete");
                                                runDelete(id);
                                                break;
                                        }
                                    });
                                }
                            }

                        });
                    }

            }
            
    } else {
    // ----------------------------------- 
    // Agents
        console.log("This is an agent!");
    }


const runAccept = (ticketID, notifID, trans_type) => {
    loadModal("sup-trans-accept",modalTypes,()=>{},getDocumentLevel(),{
        "ticketID":ticketID,
        "notifID":notifID,
        "trans_type":trans_type,
        // "reason":reason
    });
}

const runRead = (notifID) => {
    console.log("Running Read Api for ID "+notifID);
}

const runDecline = (notifID) => {
    loadModal("sup-trans-decline",modalTypes,()=>{},getDocumentLevel(),{
        "notifID":notifID,
        // "previous":previous_name,
        // "date":date,
        // "reason":reason
        });
}

const runDelete = (notifID) => {
    Swal.fire({  
        title: 'Delete Request Permanently?',  
        text: 'The request and its notes will be permanently deleted. This action cannot be reversed.',
        // showDenyButton: true,  
        showCancelButton: true,  
        confirmButtonText: `YES`,  
        // denyButtonText: `Don't save`,
        }).then((result) => {  
            /* Read more about isConfirmed, isDenied below */  
            if (result.isConfirmed) {    
            //  ajax to process delete
            console.log("DELETE NOTIFICATION"); 
        } 
        //   else if (result.isDenied) {    
        //       Swal.fire('Changes are not saved', '', 'info')  
        //    }
    });
}
    






//     // console.log(table.rows[3].cells[2].innerText);
//     // console.log(current_label);
//     for(let x = 0; x < paginationBaseTotal; x++){




// // // $c_notif_arr = array("Follow Up","Transfer Req.","Escalation Req.","Access Req.","Override Req.","Override Notice");


// //         // console.log(idHook.innerText);
// //         // console.log('btn-'+current_label+'-'+'accept'+'-'+x+'-'+id.toString());
// //         //     console.log(button)
//         for(let y = 0; y < button_id_mid.length; y++){            

//             // let button = document.getElementById('btn-'+current_label+'-'+button_id_mid[y]+'-'+x+'-'+id.toString());
//             // console.log('btn-'+current_label+'-'+button_id_mid[y]+'-'+x+'-'+id.toString());
//             // if(!noButtons){
//             //     if(button_id_mid[y]=="accept"){
//             //         // console.log(button);
//             //         button.addEventListener("click",()=>{
//             //             console.log("Accept-Row-"+x);
//             //             loadModal("sup-trans-accept",modalTypes,()=>{},getDocumentLevel(),{
//             //                 // "current":current_name,
//             //                 // "previous":previous_name,
//             //                 // "date":date,
//             //                 // "reason":reason
//             //             });
//             //         });
//             //     }
    
//             //     if(button_id_mid[y]=="read"){
//             //         // console.log(button);
//             //         button.addEventListener("click",()=>{
//             //             console.log("Read-Row-"+x);
//             //             // DERECHO AJAX API
//             //         });
//             //     }
    
//             //     if(button_id_mid[y]=="decline"){
//             //         // console.log(button);
//             //         button.addEventListener("click",()=>{
//             //             console.log("Decline-Row-"+x);
//             //             loadModal("sup-trans-decline",modalTypes,()=>{},getDocumentLevel(),{
//             //                 // "current":current_name,
//             //                 // "previous":previous_name,
//             //                 // "date":date,
//             //                 // "reason":reason
//             //               });
//             //         });
//             //     }
    
//             //     if(button_id_mid[y]=="delete"){
//             //         // console.log(button);
//             //         button.addEventListener("click",()=>{
//             //             // console.log("Delete-Row-"+x);
//             //             Swal.fire({  
//             //                 title: 'Delete Request Permanently?',  
//             //                 text: 'The request and its notes will be permanently deleted. This action cannot be reversed.',
//             //                 // showDenyButton: true,  
//             //                 showCancelButton: true,  
//             //                 confirmButtonText: `YES`,  
//             //                 // denyButtonText: `Don't save`,
//             //               }).then((result) => {  
//             //                   /* Read more about isConfirmed, isDenied below */  
//             //                   if (result.isConfirmed) {    
//             //                     //  ajax to process delete
//             //                     console.log("DELETE NOTIFICATION"); 
//             //                   } 
//             //                 //   else if (result.isDenied) {    
//             //                 //       Swal.fire('Changes are not saved', '', 'info')  
//             //                 //    }
//             //               });
//             //         });
//             //     }
//             // } else {
//             //     // Exceptions to notifications
//             //     if(button_id_mid[y]=="read"){
//             //         // console.log(button);
//             //         button.addEventListener("click",()=>{
//             //             console.log("Read-Row-"+x);
//             //         });
//             //     } else {
//             //         button.setAttribute("disabled", "true");
//             //         button.classList.remove("btn-primary");
//             //         button.classList.remove("btn-danger");
//             //         button.classList.remove("btn-success");
//             //         button.classList.add("btn-secondary");
//             //         button.style.opacity = "0.25";
//             //     }
//             // }
            

//             //     button.addEventListener("click",()=>{
//             //         window.location = './ticket.php?id='+id;
//             //     });
//         }
//     }

});