$(document).ready(()=>{
  let btn_accept = document.getElementById("tkt-accept-btn");

  if(btn_accept != undefined && btn_accept != null){
    btn_accept.addEventListener("click",()=>[
        console.log("accept button is clicked")
    ]);
  }
});