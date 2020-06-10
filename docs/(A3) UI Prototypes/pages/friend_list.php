<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/page_templates/friend_list.php');   

    draw_header(["friend_list.css"]);
    draw_friend_list();
    draw_footer();
?> 