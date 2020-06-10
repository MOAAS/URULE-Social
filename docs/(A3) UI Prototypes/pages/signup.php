<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/auth.php');
    include_once('../templates/page_templates/signup.php');   

    draw_header(["auth.css"]);
    draw_signup();
    draw_footer();
?> 