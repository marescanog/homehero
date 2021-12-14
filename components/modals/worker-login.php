

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

          <div class="modal-body" style="min-width: 350px;">
              <form id="wo-modal-login-form" type="POST" name="wo-login-form">
              <h4 style="font-weight: bold; font-size: 24px; color: #707070; text-align: center;">Worker Portal</h4>
                      <h5 style="font-size: 16px; color: #707070">Welcome back! Sign into your Worker account </h5>
                  
                      <div class="form-group mb-2 mt-1">    
                    <label for="HOLnm" class="font-weight-bold" style="color: #707070;font-size: 14px; text-align: center;">MOBILE NUMBER</label>
                    <input type="text" class="form-control mt-0" id="HOLnm" name="phone" placeholder="09XX-XXX-XXXX" autocomplete required maxlength="11">
                  </div>
                  <div class="form-group mb-1 mt-1">
                    <label for="HOLpassword" class="font-weight-bold" style="color: #707070;font-size: 14px; text-align: center;">PASSWORD</label>
                    <input type="password" class="form-control mt-0 mb-0" id="HOLps" name="password" placeholder="at least 6 characters" autocomplete required minlength="6">
                  </div>
                  <a class="mt-0 mb-2" href="#" style="font-size:0.8em;">
                    Forgot password?
                    </a> 
                  <br>
                  <button id="LU-submit-btn" type="button" class="btn btn-warning text-white font-weight-bold w-100 mb-3 mt-2" >
                  <span id="LU-submit-btn-txt">CONTINUE</span>
                  <div id="LU-submit-btn-load" class="d-none">
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      <span class="sr-only">Loading...</span></div>
                    </button>
                  <div class="text-center" style="font-size:0.8em;">
                        <p id="su">
                          Dont have an account? <a href="#">Sign-up</a>
                          <br>
                          
                          Registered as a hero? <a href="#">Log in</a> the Homeowner's Portal.
                      </p>
                  </div>
                </form>
          </div>
    </div>
</div>

<script src="<?php echo $level?>/js/components/modal-validation/modal-worker-login.js"></script>

                   