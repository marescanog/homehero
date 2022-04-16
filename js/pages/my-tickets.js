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

// ==================
// Pagination
// Get Values
    let my_limit = document.getElementById("limit").value;
    let my_page = document.getElementById("page").value;
    let my_tab = document.getElementById("tab").value;
    let my_ongoing = document.getElementById("total-ongoing").value;
    let my_completed = document.getElementById("total-completed").value;
    let my_escalated = document.getElementById("total-escalated").value;
    let my_transferred = document.getElementById("total-transferred").value;
    let ultimatotal = [my_ongoing,my_completed,my_escalated,my_transferred];
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

//CLICKABLE BUTTONS
let starting_page = 0;
let paginationBaseTotal = ultimatotal[my_tab];
let numberOfPages = Math.ceil(paginationBaseTotal/my_limit);
let over_lim = numberOfPages-5;
let ciel_page =  over_lim <= 0 ? (numberOfPages <= 5 ? 1 : my_page ) : over_lim+1;
let my_page_buttons = [];

for(let titi = my_page > ciel_page ? ciel_page : my_page, pagLimit = 0; pagLimit < 5 && titi <= numberOfPages ; titi++, pagLimit++){
    my_page_buttons.push( document.getElementById("pag-"+titi));
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