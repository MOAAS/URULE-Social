<?php function draw_friend_requests() { ?>
  <div class="row">
      <?php draw_side_bar(3); ?> 

      <section class="page-section col-12 col-sm-10 p-0">
        <?php draw_top_bar("Friend Requests"); ?>

        <ul class="centered-content">
          <?php draw_request("Jessica Taylor", "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQ0JcbJZ3OOLv9ZeGiPBZ3g_g8bhU040g0Ae5VeJ3_GWysGi5oN") ?>
          <?php draw_request("Melissa Kim", "https://images-cdn.9gag.com/photo/anNxRq0_700b.jpg") ?>
          <?php draw_request("Ricky Gordon", "https://cdn-9chat-fun.9cache.com/media/photo/a3xprL9l3_480w_v1.jpg") ?>     
          <?php draw_request("William Randy", "https://miro.medium.com/max/2800/1*w96ZU0M9GOAGU_hVXvdH8g.jpeg") ?>       
        </ul>
      </section>
      <?php draw_bottom_bar(-1) ?>  
  </div>
<?php } ?>

<?php function draw_request($name, $photo) { ?>
  <li class="list-group-item d-flex align-items-center">
    <img class="profile-picture-small" src=<?=$photo?>>
    <span class="name text-truncate"><?=htmlentities($name)?></span>
    <button class="btn btn-danger btn-sm ml-auto mr-2" title="Delete"><i class="fa fa-times"></i></button>
    <button class="btn btn-success btn-sm" title="View"><i class="fa fa-check"></i></button>
  </li>
<?php } ?>