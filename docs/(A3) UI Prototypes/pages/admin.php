<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/page_templates/admin.php');   

    draw_header(["admin.css"]);
    draw_admin();
    draw_footer();
?> 