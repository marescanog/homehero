$(document).ready(()=>{
    let btn_gen_transfer = document.getElementById("btn_gen_transfer");
    let btn_see_transfer_code = document.getElementById("btn-see-transfer-code");

    if(btn_gen_transfer != null){
        btn_gen_transfer.addEventListener("click",()=>{
        loadModal("sup-perm-enter-pass",modalTypes,()=>{},getDocumentLevel(),{
            permission_code: 3 // 1-External Agent Transfer Request, 2 -Reassign Ticket of External Agent, 3- Transfer Request
        });
      });
    }

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

});