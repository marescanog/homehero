

<?php 
    $level = isset($_POST['level']) ? $_POST['level'] : '.';
?>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content px-3">
      <div class="modal-header" style="border-bottom: 0;">
          <div class="mx-auto" style="width: auto;">
              <img src='<?php echo $level;?>/images/logo/HH_Logo_Light.svg'>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem 0;">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>

          <div class="modal-body">
              <form id="modal-login-form" type="POST"  name="hoLoginForm">
              <h4 style="font-weight: bold; font-size: 26px; color: #707070">Welcome Back!</h4>
                      <h5 style="font-size: 16px; color: #707070">Sign into your Homeowner account </h5>
                  
                      <div class="form-group mb-1 mt-1">    
                    <label for="HOLnm" class="font-weight-bold" style="color: #707070;font-size: 14px;">MOBILE NUMBER</label>
                               <!-- Mobile Number input feild -->
                      <div id="RU_phone_formGroup" class="form-group">
                          <input type="text" class="form-control" id="RU_phone" name="phone" placeholder="Mobile number (09XXXXXXXXX)" autocomplete required maxlength="15">
                      </div>
                  </div>
                  <div class="form-group mb-1 mt-0">
                    <label for="HOLpassword" class="font-weight-bold" style="color: #707070;font-size: 14px;">PASSWORD</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" minlength="8" maxlength="31" autocomplete >
                  </div>
                  <span class="mt-0 mb-2 clicky" href="#" style="font-size:0.8em;" onclick="summon_forgotpass_homeowner()">
                    Forgot password?
                    </span> 
                    <button id="RU-submit-btn"  type="submit" value="Submit"  class="btn btn-warning text-white font-weight-bold w-100 mb-3 py-2 mt-3">
                      <span id="RU-submit-btn-txt">LOGIN</span>
                      <div id="RU-submit-btn-load" class="d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          <span class="sr-only">Loading...</span>
                      </div>
                  </button>
                  <div class="text-center" style="font-size:0.8em;">
                        <p id="su">
                          Dont have an account? <span class="clicky" onclick="redirect_to_homeonwer_signup_modal()">Sign-up</span>
                          <br>
                          
                          Registered as a worker? <span class="clicky" onclick="redirect_to_worker_login_modal ()">Log in</span> the Worker's Portal.
                      </p>
                  </div>
                </form>
          </div>
    </div>
</div>
<script src="<?php echo $level?>/js/components/modal-validation/modal-homeowner-login.js"></script>
                   