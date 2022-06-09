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
    console.log("Edit anouncement for "+id);
    loadModal("sup-anouncement-edit",modalTypes, ()=>{}, getDocumentLevel(), {
        id:id
    });
}


const viewAnouncement = (id) => {
    console.log("View anouncement for "+id);
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
            console.log("DELETE ANOUNCEMENT"); 
        } 
    });
}