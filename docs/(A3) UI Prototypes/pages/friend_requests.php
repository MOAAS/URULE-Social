<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/page_templates/friend_requests.php');   

    draw_header(["friend_requests.css"]);
    draw_friend_requests();
    draw_footer();
?> 