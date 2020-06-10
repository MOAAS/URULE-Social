<?php function draw_search() { ?>
  <div class="row">
    <?php draw_side_bar(4); ?>

    <section class="p-0 col-sm-10 page-section">
        <?php draw_top_bar("Search"); ?>

        <form id="search-form">
            <div class="input-group">
                <div class="input-group-prepend"><i class="fas fa-search input-group-text font-weight-bold"></i></div>        
                <input type="text" class="form-control" placeholder="Search for posts/people..." value="Pedro" aria-label="Search">        
            </div>

            <div id="search-settings" class="hide-settings">
                <div class="form-check">
                    <input id="post-filter"  class="form-check-input" type="checkbox" name="post-filter">
                    <label class="form-check-label" for="post-filter">Post</label>
                </div>

                <div class="form-check">          
                    <input id="person-filter" class="form-check-input" type="checkbox" name="person-filter">
                    <label class="form-check-label" for="person-filter">Person</label>
                </div>   

                <div class="row my-3">
                    <div class="col-12 col-md-6 input-group">
                        <div class="input-group-prepend"><span class="input-group-text">From</span></div>
                        <input type="date" class="form-control" name="filter-from">
                    </div>
                    <div class="col-12 col-md-6 input-group">
                        <div class="input-group-prepend"><span class="input-group-text">To</span></div>
                        <input type="date" class="form-control" name="filter-to">
                    </div>
                </div>

                <div class="border-top mt-4"></div>
            </div>

            <span id="search-settings-toggler">
                <button type="button" class="btn rounded-circle border"><i class="fas fa-angle-down"></i> </button>
                <span id="search-settings-label">Advanced search</span>
            </span>
            
            <input id="search-btn" type="submit" class="btn btn-primary" value="Search">

        </form>

        <div class="centered-content">
            <?php draw_post("Pedro Moas", "https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/s960x960/54435063_2186294784790588_3646571120003383296_o.jpg?_nc_cat=106&_nc_sid=8024bb&_nc_ohc=bBe6eWJ06fMAX9vRVLg&_nc_ht=scontent.flis7-1.fna&_nc_tp=7&oh=1005f1f599b8512044c87ce7510ca3e5&oe=5E8F2897", "Just watched Rise of the Skywalker - EPIC!", "22 Dec"); ?>
            
            <?php draw_user_result("Pedro Moas", "https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/s960x960/54435063_2186294784790588_3646571120003383296_o.jpg?_nc_cat=106&_nc_sid=8024bb&_nc_ohc=bBe6eWJ06fMAX9vRVLg&_nc_ht=scontent.flis7-1.fna&_nc_tp=7&oh=1005f1f599b8512044c87ce7510ca3e5&oe=5E8F2897"); ?>
            <?php draw_user_result("Pedro Fernandes", "https://pbs.twimg.com/profile_images/862397278866341890/Jo_vrTo5_400x400.jpg"); ?>

            <?php draw_post("Daniel Brandão", "https://ae01.alicdn.com/kf/HTB19PzaX0zvK1RkSnfoq6zMwVXaY.jpg", "Just destroyed Pedro in a TFT 1v1 lmao", "2 Feb"); ?>

        </div>    
        
    </section>

    <?php draw_bottom_bar(0); ?>
  </div>
<?php } ?>

<?php function draw_user_result($username, $img) { ?>
    <a href="profile.php" class="sidebar-profile list-group-item shadow-sm mx-md-3 my-3 d-flex align-items-center">
        <img class="profile-picture-small" src="<?=htmlentities($img); ?>" alt="Profile Picture">
        <h3 class="post-username"><?=htmlentities($username); ?></h3>
    </a>
<?php } ?>
