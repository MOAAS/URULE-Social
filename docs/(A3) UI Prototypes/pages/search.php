<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/post.php');
    include_once('../templates/page_templates/search.php');   

    draw_header(["search.css"]);
    draw_search();
    draw_footer();
?> 