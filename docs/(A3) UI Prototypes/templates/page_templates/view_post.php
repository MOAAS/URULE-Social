<?php function draw_view_post() { ?>
    <div class="row">
        <?php draw_side_bar(-1); ?> 
        <section class="page-section col-12 col-sm-10 p-0">
            <?php draw_top_bar("Post"); ?>

            <div class="centered-content">
                <!-- main post -->
                <article class="post card shadow-sm my-3">
                    <header class="p-2 card-title bg-light d-flex align-items-center">
                        <a href="profile.php" class="sidebar-profile">
                            <img class="profile-picture-small"
                                src="https://scontent.fopo3-1.fna.fbcdn.net/v/t1.0-9/48166344_2157217511010561_4089047094743007232_n.jpg?_nc_cat=101&_nc_sid=85a577&_nc_ohc=AHnWh0H3aVIAX_qoUZP&_nc_ht=scontent.fopo3-1.fna&oh=4122b6f9da68aa46c5ceef7af8e700e7&oe=5E93EF88"
                                alt="Profile Picture" />
                            <h3 class="post-username">Miguel Pinto</h3>
                        </a>
                        <small class="ml-auto mr-2 text-muted"><date>1 Feb</date></small>
                    </header>
                    <div class="card-body">    
                        <p class="card-text text-prewrap mb-4">Beautiful view!!</p>
                        <img class="img-fluid" src="https://wallpapercave.com/wp/7N01m53.jpg" alt="view" />
                    </div>

                    <div class="d-flex post-stats border-top">
                        <span class="react-btn"><i class="fas fa-thumbs-up"></i> 7</span>
                        <span class="react-btn react-btn-downvote"><i class="fas fa-thumbs-down"></i> 1</span> 
                        <span class="react-btn ml-auto"><i class="fas fa-comment"></i> 5</span>
                    </div>
                </article>

                <!-- comment form -->
                <div class="card shadow-sm mx-md-3 my-3">
                    <div class="card-title bg-light p-2 m-0">
                        <a href="profile.php" class="sidebar-profile">
                            <img class="profile-picture-small"
                                src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560"
                                alt="profilePic" />
                            <span>Arnold Schwarzenegger</span>
                        </a>
                    </div>
                   

                    <form id="post-form">
                        <textarea id="post-input" class="form-control not-resizable" placeholder="Write your comment here..." rows="4"></textarea>     
                        <input id="post-btn" type="submit" class="ml-auto btn btn-primary" value="Comment">
                    </form>
                </div>

                <!-- comment -->
                <?php draw_comment("Pedro Moás", "https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/s960x960/54435063_2186294784790588_3646571120003383296_o.jpg?_nc_cat=106&_nc_sid=8024bb&_nc_ohc=bBe6eWJ06fMAX9vRVLg&_nc_ht=scontent.flis7-1.fna&_nc_tp=7&oh=1005f1f599b8512044c87ce7510ca3e5&oe=5E8F2897", "Aren't you supposed to be in quarantine?", "1 Feb"); ?>
                <?php draw_comment("Daniel Brandão", "https://ae01.alicdn.com/kf/HTB19PzaX0zvK1RkSnfoq6zMwVXaY.jpg", "So beautiful!!", "1 Feb"); ?>
                <?php draw_comment("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "Nice", "1 Feb", true); ?>


            </div>
        </section>
        <?php draw_bottom_bar(-1) ?>  
    </div>
<?php } ?>