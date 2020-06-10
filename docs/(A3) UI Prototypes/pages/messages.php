<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/page_templates/messages.php');   

    draw_header(["messages.css"]);
    draw_conversations();
    draw_footer();
?> 