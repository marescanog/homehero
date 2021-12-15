<!-- 
    PHP to detect if there is already values filled in. Echo back into modal value.
 -->
<?php 
    $level = isset($_POST['level']) ? $_POST['level'] : '.';
?>
<div class="modal-content">
    <div class="modal-header">
        <div class="mx-auto" style="width: auto;">
            <img src="<?php echo $level?>/images/logo/HH_Logo_Light.svg">
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem 0;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <h5 class="font-weight-bold mb-3" style="color: #707070; text-align: center; font-size:24px">Worker Registration</h5>
        <form  id="registerForm" method="POST">
            <fieldset>
                <!-- First Name and Last Name Input Feilds -->
                <div class="d-flex">
                    <div class="form-group w-100 mr-1">
                        <input id="RU_firstname" class="form-control" name="first_name" minlength="2" placeholder="First Name" type="text" maxlength="51" autocomplete required>
                    </div>
                    <div class="form-group w-100 ml-1">
                        <input id="RU_lastname" class="form-control" name="last_name" minlength="2" placeholder="Last Name" type="text" maxlength="51" autocomplete required>
                    </div>
                </div>
                <!-- Mobile Number input feild -->
                <div id="RU_phone_formGroup" class="form-group">
                    <input type="text" class="form-control" id="RU_phone" name="phone" placeholder="Mobile number (09XXXXXXXXX)" autocomplete required maxlength="15">
                </div>
                <!-- Password and Confirm password input feild -->
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" minlength="8" maxlength="31" autocomplete >
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter password" autocomplete >
                </div>
                <!-- Radio Button (Above 18) Input-->
                <div class="form-group">
                    <p class="text-center p-0 m-0">Are you above the age of 18? <input class="form-check-input" type="radio" name="exampleRadios" id="exasdfs" value="option1" disabled style="opacity:0"></p>
                    
                    <div class="d-flex flex-row justify-content-center mb-3">
                        <div class="d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="above18" id="above18-yes" value="yes" >
                                <label class="form-check-label" for="exampleRadios1">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="above18" id="above18-no" value="no" >
                                <label class="form-check-label" for="exampleRadios2">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Check box (Above 18) Input-->
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="agree" name="agree">
                    <label class="form-check-label" for="agree" style="font-size:0.8em;">I agree to HomeHero's <span class="cclicky" onclick="summonTerms()">Terms and Conditions</span> and <span class="cclicky" onclick="summonPrivacy()">Privacy Policy</span>.</label>
                </div>
                <!-- Submit Button -->
                <button id="RW-submit-btn" type="submit" value="Submit" class="btn btn-warning text-white font-weight-bold w-100 mb-3 submit">
                    <span id="RW-submit-btn-txt">CREATE ACCOUNT</span>
                    <div id="RW-submit-btn-load" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>

            <div class="text-center" style="font-size:0.8em;">
                <p>
                    Already have an account? <span class="cclicky" onclick="redirect_to_worker_login_modal()">Login</span>
                    </br>
                    Looking to hire a worker? <span class="cclicky" onclick="redirect_to_homeonwer_signup_modal()">Sign-up</span> at the homeowner's portal.
                </p>
            </div>

            </fieldset>
        </form>
    </div>
</div>
<script src="<?php echo $level?>/js/components/modal-validation/modal-worker-register.js"></script>