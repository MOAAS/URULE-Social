<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/page_templates/about.php');   

    draw_header(["about.css"]);
    draw_about_page();
    draw_footer();
?> 