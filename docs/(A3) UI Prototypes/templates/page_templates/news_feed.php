<?php function draw_news_feed($signed_in = true) { ?> 
    <div class="row">    
        <?php draw_side_bar(0, $signed_in); ?>

        <section id="main-page" class="p-0 col-sm-10 page-section">
            <?php draw_top_bar("Main Page", true && $signed_in); ?>
            
            <div class="centered-content">
                <?php draw_announcement("Attention users, don't leave your house. You might catch coronavirus.") ?>

                <?php if ($signed_in) draw_post_form(); ?>

                <?php draw_post("Miguel Pinto", "https://scontent.fopo3-1.fna.fbcdn.net/v/t1.0-9/48166344_2157217511010561_4089047094743007232_n.jpg?_nc_cat=101&_nc_sid=85a577&_nc_ohc=AHnWh0H3aVIAX_qoUZP&_nc_ht=scontent.fopo3-1.fna&oh=4122b6f9da68aa46c5ceef7af8e700e7&oe=5E93EF88", "Just watched Rise of the Skywalker - EPIC!", "09 Mar"); ?>
                
                <article class="clickable-post card shadow-sm mx-md-3 my-3">
                    <header class="p-2 card-title bg-light d-flex align-items-center">
                        <a href="profile.php" class="sidebar-profile">
                            <img class="profile-picture-small"
                                src="https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/s960x960/54435063_2186294784790588_3646571120003383296_o.jpg?_nc_cat=106&_nc_sid=8024bb&_nc_ohc=bBe6eWJ06fMAX9vRVLg&_nc_ht=scontent.flis7-1.fna&_nc_tp=7&oh=1005f1f599b8512044c87ce7510ca3e5&oe=5E8F2897"
                                alt="Profile Picture" />
                            <h3 class="post-username">Pedro Moás</h3>
                        </a>
                        <small class="ml-auto mr-2 text-muted"><date>08 Mar</date></small>
                    </header>
                    <div class="card-body">    
                        <p class="card-text text-prewrap mb-4">Beautiful view!!</p>
                        <img class="img-fluid" src="https://wallpapercave.com/wp/7N01m53.jpg" alt="view" />
                    </div>

                    <div class="d-flex post-stats border-top">
                        <span class="react-btn"><i class="fas fa-thumbs-up"></i> 3</span>
                        <span class="react-btn react-btn-downvote"><i class="fas fa-thumbs-down"></i> 0</span> 
                        <span class="react-btn ml-auto"><i class="fas fa-comment"></i> 5</span>
                    </div>
                </article>

                <?php draw_post("Daniel Brandão", "https://ae01.alicdn.com/kf/HTB19PzaX0zvK1RkSnfoq6zMwVXaY.jpg", "People around the world face violence and inequality—and sometimes torture, even execution—because of who they love, how they look, or who they are. Sexual orientation and gender identity are integral aspects of our selves and should never lead to discrimination or abuse. Human Rights Watch works for lesbian, gay, bisexual, and transgender peoples' rights, and with activists representing a multiplicity of identities and issues. We document and expose abuses based on sexual orientation and gender identity worldwide, including torture, killing and executions, arrests under unjust laws, unequal treatment, censorship, medical abuses, discrimination in health and jobs and housing, domestic violence, abuses against children, and denial of family rights and recognition. We advocate for laws and policies that will protect everyone’s dignity. We work for a world where all people can enjoy their rights fully.", "08 Mar"); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "I was proud to join my friends John Kasich and John Kerry for our first WorldWarZeroOrg townhall in Columbus. The only way we can terminate pollution is Republicans and Democrats working together. There isn’t Republican air or Democratic air. It’s time to unite & protect health.",  "08 Mar", true); ?>
                <?php draw_post("Miguel Pinto", "https://scontent.fopo3-1.fna.fbcdn.net/v/t1.0-9/48166344_2157217511010561_4089047094743007232_n.jpg?_nc_cat=101&_nc_sid=85a577&_nc_ohc=AHnWh0H3aVIAX_qoUZP&_nc_ht=scontent.fopo3-1.fna&oh=4122b6f9da68aa46c5ceef7af8e700e7&oe=5E93EF88", 
                    "Pra você se acabar e mexer o bumbum
Pra você se acabar, mulher, mexer o bumbum
Pra você se acabar e mexer o bumbum

Turutututum, vai jogando o bumbum
Turutututum, vai jogando o bumbum
Turutututum, vai jogando o bumbum


Turutututum vai jogando o bumbum
Turutututum vai jogando o bumbum
Turutututum vai jogando o bumbum

Vai jogando, vai sarrando, vai mexendo o bumbum
Vai jogando, vai sarrando, vai mexendo o bumbum
Vai jogando, vai sarrando, vai mexendo o bumbum", "07 Mar"); ?>
            </div>                
        </section>

        <?php draw_bottom_bar(1, $signed_in) ?>
    </div>
<?php } ?>


<?php function draw_post_form() { ?>
    <div class="card shadow-sm mb-4">
        <div class="card-title bg-light p-2 m-0">
            <a href="profile.php" class="sidebar-profile">
                <img class="profile-picture-small"
                    src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560"
                    alt="profilePic" />
                <h3 class="post-username">Arnold Schwarzenegger</h3>
            </a>
        </div>

        <form id="post-form">
            <textarea id="post-input" class="form-control" placeholder="Write your post here..." rows="4"></textarea>     

            <div id="post-settings" class="hide-settings">
                <div class="form-group">     
                    <label for="post-rules">Rules</label>
                    <input id="post-rules" name="rules" type="text" placeholder="Best rules get v-bucks" class="form-control w-auto">
                </div>

                <div class="border-bottom my-3"></div>

                <div class="form-group form-check">          
                    <input id="private-post" name="private-post" type="checkbox" class="form-check-input">
                    <label class="form-check-label" for="private-post">Private post</label>
                </div>
            </div>

            <button id="post-settings-toggler" type="button" class="btn rounded-circle border"><i class="fas fa-angle-down"></i></button>
            <input id="post-btn" type="submit" class="ml-auto btn btn-primary" value="Post">
        </form>
    </div>              
<?php } ?>

<?php function draw_announcement($content) { ?>
    <div class="text-center alert alert-warning alert-dismissible fade show" role="alert">
        <h3 class="alert-heading">Announcement</h3>
        <p class="text-prewrap my-3"><?=$content?></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>                
    </div>
<?php } ?>