$(document).ready(()=>{
    let btn_gen_transfer = document.getElementById("btn_gen_transfer");

    if(btn_gen_transfer != null){
        btn_gen_transfer.addEventListener("click",()=>{
        loadModal("sup-perm-enter-pass",modalTypes,()=>{},getDocumentLevel(),{
        //   transferType: 1, // 1-transfer, 2-request override, 3-escalte to supervisor
        //   assigned_agent_name: trans_assigned_agent_name.value,
        //   assigned_agent_id: trans_assigned_agent_ID.value,
        //   assigned_on: trans_assigned_on.value
        });
      });
    }
});