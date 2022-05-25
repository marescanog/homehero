$(document).ready(()=>{
    let button_anounce = document.getElementById("add-anouncement");

    if(button_anounce != null){
        button_anounce.addEventListener("click",()=>{
            loadModal("sup-add-anouncement",modalTypes, ()=>{}, getDocumentLevel(), {
            });
        });
    }
}); 