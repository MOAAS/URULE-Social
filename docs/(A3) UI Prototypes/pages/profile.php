<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/post.php');
    include_once('../templates/page_templates/profile.php');   

    draw_header(["profile.css"]);
    draw_profile_self();
    draw_footer();
?> 