<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/page_templates/view_post.php');   
    include_once('../templates/post.php');   

    draw_header(["post.css"]);
    draw_view_post();
    draw_footer();
?> 