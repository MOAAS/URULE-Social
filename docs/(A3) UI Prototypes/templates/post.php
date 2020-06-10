<?php function draw_edit_modal($isPost, $content) { ?>
    <div class="modal fade edit-post-modal" tabindex="-1" role="dialog">
        <form class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit <?=$isPost?"Post":"Comment"?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body form-group">
                    <label for="message-text" class="col-form-label"><?=$isPost?"Post":"Comment"?> Content:</label>
                    <textarea class="form-control" id="message-text" rows="4"><?=htmlentities($content) ?></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>

<?php function draw_generic_comment($username, $photo, $content, $date, $showDropdown, $isPost) { ?>
    <article class="card shadow-sm mx-md-3 my-3">
        <header class="p-2 card-title bg-light d-flex align-items-center m-0">
            <a href="profile.php" class="sidebar-profile">
                <img class="profile-picture-small"
                    src=<?=$photo?>
                    alt="Profile Picture" />
                <h3 class="post-username"><?=htmlentities($username)?></h3>                
            </a>
            <small class="ml-auto mr-2 text-muted"><date><?=htmlentities($date)?></date></small>

            <?php if ($showDropdown) { ?>
                <div class="dropleft p-2">
                    <button class="btn text-dark" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>                
                    <div class="dropdown-menu">
                        <button class="dropdown-item" data-toggle="modal" data-target=".edit-post-modal">Edit</button>
                        <button class="dropdown-item">Delete</button>
                    </div>
                </div>

                <?php draw_edit_modal($isPost, $content) ?>
            <?php } ?>

        </header>
        <div class="card-body <?=$isPost?"clickable-post":""?>">    
            <p class="card-text text-prewrap m-0"><?=htmlentities($content)?></p>
        </div>

        <div class="d-flex post-stats border-top">
            <span class="react-btn"><i class="fas fa-thumbs-up"></i> <?=rand(1,6)?></span>
            <span class="react-btn react-btn-downvote"><i class="fas fa-thumbs-down"></i> <?=rand(0,2)?></span> 
            <?php if ($isPost) { ?>
                <span class="react-btn ml-auto"><i class="fas fa-comment"></i> <?=rand(0, 3)?> </span>
            <?php } ?>
        </div>
    </article>
<?php } ?>

<?php function draw_post($username, $photo, $content, $date, $showDropdown = false) { 
    draw_generic_comment($username, $photo, $content, $date, $showDropdown, true);
} ?>

<?php function draw_comment($username, $photo, $content, $date, $showDropdown = false) { 
    draw_generic_comment($username, $photo, $content, $date, $showDropdown, false);
} ?>


