<?php 
    if(is_object($output) && $output->success == false){
        $output_status = $output->response->status;
        $output_message = $output->response->message; // "JWT - Ex 1:Expired token"
        ?>
        <!-- <input type="hidden" id="output_status" value="<?php echo $output_status;?>">
        <input type="hideen" id="output_message" value="<?php echo $output_message ?>"> -->
         <script>
             let o_status  = "<?php echo $output_status;?>";
             let o_message = "<?php echo $output_message ?>";
             let o_text = o_message == "JWT - Ex 1:Expired token" ? "Your token has expired. Please login in again." : "Your token is expired or unrecognized. Please login in again."
             let o_title = o_message == "JWT - Ex 1:Expired token" ? 'Expired Token!' : 'Session Expired!'
             Swal.fire({
                title: "Sesion Expired!",
                text: o_text,
                icon: 'info',
                }).then((result) => {
                    $.ajax({
                    type : 'GET',
                    url : '../../auth/signout_action.php',
                    success : function(response) {
                        var res = JSON.parse(response);
                        if(res["status"] == 200){
                            window.location = getDocumentLevel()+'/pages/support/';
                        }
                    }
                    });
                })
         </script>
        <?php
     }
?>