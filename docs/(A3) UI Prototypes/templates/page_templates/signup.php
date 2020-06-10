<?php function draw_signup() { ?>
  <?php draw_auth_header() ?>

  <section id="sign-in" class="p-3">
    <h2>Sign Up</h2>
    <form class="m-3" method="get" action="main.php">
    
      <?php draw_form_input("Name", "name", "text", "First and last name", "How other users will see you."); ?>
      <?php draw_form_input("Email address", "email", "email", "Enter email", "We'll never share your email with anyone else."); ?>
      <?php draw_form_input("Password", "password", "password", "Password"); ?>
      <?php draw_form_input("Confirm Password", "confirmPassword", "password", "Confirm Password"); ?>

      <input type="submit" class="btn btn-primary btn-block mb-3" value="Sign Up">
      <button type="button" class="btn-google btn btn-primary btn-block mb-3"><?php draw_google_icon(24) ?>&nbsp; Sign Up with Google</button>
    </form>

    <p class="text-center">
        Already have an account? 
        <a href="login.php">Sign In!</a>    
    </p>
  </section>
<?php } ?>