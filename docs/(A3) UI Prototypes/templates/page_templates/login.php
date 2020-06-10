<?php function draw_login() { ?>
  <?php draw_auth_header() ?>

  <section id="sign-in" class="p-3">
    <h2>Sign In</h2>
    <form class="m-3" method="get" action="main.php">

      <?php draw_form_input("Email address", "email", "email", "Enter email"); ?>
      <?php draw_form_input("Password", "password", "password", "Password"); ?>

      <div class="form-group form-check">          
        <input id="remember-me" name="remember-me" type="checkbox" class="form-check-input">
        <label class="form-check-label" for="remember-me">Remember Me</label>
      </div>
      
      
      <input type="submit" class="btn btn-primary btn-block mb-3" value="Log In">
      <button type="button" class="btn-auth-secondary btn btn-primary btn-block mb-3"><?php draw_google_icon(24) ?>&nbsp; Log In with Google</button>
      <a href="main_guest.php" class="btn-auth-secondary btn btn-primary btn-block mb-3"><i class="fas fa-user-secret"></i>&nbsp; Enter as a Guest</a>
    </form>
    
    
    <div class="row text-center">
      <div class="col-6 border-right"><a href="signup.php" >Sign Up</a></div>        
      <div class="col-6"><a href="#">Forgot Password</a></div>
    </div>
  </section>
<?php } ?>