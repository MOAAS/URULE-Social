<?php 
    include_once('../templates/common/header.php');
    include_once('../templates/common/footer.php');   
    include_once('../templates/navbars.php');
    include_once('../templates/post.php');
    include_once('../templates/page_templates/news_feed.php');   

    draw_header(["main_page.css"]);
    draw_news_feed(false);
    draw_footer();
?> 