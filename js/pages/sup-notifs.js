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
//     let my_limit = document.getElementById("limit").value;
//     let my_page = document.getElementById("page").value;
    let my_tab = document.getElementById("tab").value;
//     let my_new = document.getElementById("total-new").value;
//     let my_ongoing = document.getElementById("total-ongoing").value;
//     let my_completed = document.getElementById("total-completed").value;
//     let my_escalated = document.getElementById("total-escalated").value;
//     let my_transferred = document.getElementById("total-transferred").value;
//     let ultimatotal = [my_new,my_ongoing,my_completed,my_escalated,my_transferred];
// // Get Hooks
//     let prev_page = document.getElementById("pag-prev");
//     let next_page = document.getElementById("pag-next");

// prev_page.addEventListener("click", ()=>{
//     if(my_page != 1){
//         addURLParameter("page",parseInt(my_page)-1);   
//     }
// });

// next_page.addEventListener("click", ()=>{
//     if(my_page != Math.ceil(ultimatotal[my_tab]/my_limit)){
//         addURLParameter("page",parseInt(my_page)+1);
//     }
// });

// //CLICKABLE BUTTONS
// let starting_page = 0;
// let paginationBaseTotal = ultimatotal[my_tab];
// let numberOfPages = Math.ceil(paginationBaseTotal/my_limit);
// let over_lim = numberOfPages-5;
// let ciel_page =  over_lim <= 0 ? (numberOfPages <= 5 ? 1 : my_page ) : over_lim+1;
// let my_page_buttons = [];

// for(let titi = my_page > ciel_page ? ciel_page : my_page, pagLimit = 0; pagLimit < 5 && titi <= numberOfPages ; titi++, pagLimit++){
//     my_page_buttons.push( document.getElementById("pag-"+titi));
// }

// // console.log(my_page_buttons);
// my_page_buttons.map((btn, index)=>{
//     btn.addEventListener("click", ()=>{
//         if(numberOfPages <= 5){
//             addURLParameter("page",index+1);
//         } else {
//             addURLParameter("page",btn.id.split("-")[1]);
//         }
//     })
// });


let paginationBaseTotal = 4; //temp
// Get the button in the table.
    // Determine the number of entries - paginationBaseTotal & current tab
    // console.log("Pagination base total is: "+ paginationBaseTotal);

    let button_id_start_labels = ["new","read","all"]; //no buttons on deleted
    let button_id_mid = ["accept","read","decline","delete"];
    let current_label = button_id_start_labels[my_tab];
    let table = document.getElementById('table-hook-'+button_id_start_labels[my_tab]);

    // console.log(table.rows[3].cells[2].innerText);
    // console.log(current_label);
    for(let x = 0; x < paginationBaseTotal; x++){
        let idHook = document.getElementById(current_label+'-'+x.toString());
        let id = parseInt(idHook.innerText);

        let noButtons = table.rows[x+1].cells[2].innerText == "Override Notif";

        // console.log(idHook.innerText);
        // console.log('btn-'+current_label+'-'+'accept'+'-'+x+'-'+id.toString());
        //     console.log(button)
        for(let y = 0; y < button_id_mid.length; y++){            

            let button = document.getElementById('btn-'+current_label+'-'+button_id_mid[y]+'-'+x+'-'+id.toString());
     
            if(!noButtons){
                if(button_id_mid[y]=="accept"){
                    // console.log(button);
                    button.addEventListener("click",()=>{
                        console.log("Accept-Row-"+x);
                        loadModal("sup-trans-accept",modalTypes,()=>{},getDocumentLevel(),{
                            // "current":current_name,
                            // "previous":previous_name,
                            // "date":date,
                            // "reason":reason
                        });
                    });
                }
    
                if(button_id_mid[y]=="read"){
                    // console.log(button);
                    button.addEventListener("click",()=>{
                        console.log("Read-Row-"+x);
                        // DERECHO AJAX API
                    });
                }
    
                if(button_id_mid[y]=="decline"){
                    // console.log(button);
                    button.addEventListener("click",()=>{
                        console.log("Decline-Row-"+x);
                        loadModal("sup-trans-decline",modalTypes,()=>{},getDocumentLevel(),{
                            // "current":current_name,
                            // "previous":previous_name,
                            // "date":date,
                            // "reason":reason
                          });
                    });
                }
    
                if(button_id_mid[y]=="delete"){
                    // console.log(button);
                    button.addEventListener("click",()=>{
                        // console.log("Delete-Row-"+x);
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
                    });
                }
            } else {
                // Exceptions to notifications
                if(button_id_mid[y]=="read"){
                    // console.log(button);
                    button.addEventListener("click",()=>{
                        console.log("Read-Row-"+x);
                    });
                } else {
                    button.setAttribute("disabled", "true");
                    button.classList.remove("btn-primary");
                    button.classList.remove("btn-danger");
                    button.classList.remove("btn-success");
                    button.classList.add("btn-secondary");
                    button.style.opacity = "0.25";
                }
            }
            

            //     button.addEventListener("click",()=>{
            //         window.location = './ticket.php?id='+id;
            //     });
        }
    }

});