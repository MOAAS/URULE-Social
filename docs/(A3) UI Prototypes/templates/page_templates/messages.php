<?php function draw_conversation_preview($user, $message, $date, $photo, $selected = false) { ?>
    <li class="conversation-preview p-3 m-0 rounded-0 border-bottom border-black row align-items-center <?=$selected?"selected":""?>">
        <div class="col-2">
            <img class="profile-picture-small"
                src=<?=$photo?>
                alt="profilePic" />
        </div>        
        <section class="col-7 text-truncate">
            <h3 class="m-0 text-truncate font-weight-bold conversation-username"><?=htmlentities($user)?></h3>
            <p class="m-0 text-truncate"><?=htmlentities($message)?></p>
        </section>
        <time class="col-3 text-center"><?=htmlentities($date)?></time>    
    </li>
<?php } ?>

<?php function draw_message($sent, $content) { ?>    
    <li class="m-3 p-2 <?=$sent?"message-sent":"message-received"?>"><p class="m-0"><?=htmlentities($content)?></p></li>
<?php } ?>

<?php function draw_conversations() { ?> 
    <div class="row">
        <?php draw_side_bar(2) ?>

        <section id="conversations" class="page-section col-12 col-sm-10 col-lg-4 p-0 m-0 vh-100 d-flex flex-column overflow-auto">
            <?php draw_top_bar("messages"); ?>

            <ul class="p-0 flex-grow-1">
                <?php draw_conversation_preview("Patrick Schwarzenegger", "Thanks.", "10:43", "https://mediamass.net/jdd/public/documents/celebrities/6951.jpg", true); ?>
                <?php draw_conversation_preview("Sylvester Stallone", "Every time I’ve failed, people had me out for the count, but I always come back.", "8 Mar", "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQSujNhlHcMKcuq8habDpysptqvggnj3yBA0vQlBq5HQS6fMzo9"); ?>
                <?php draw_conversation_preview("William Randy", "HOLY CRAP I'M YOUR BIGGEST FAN PLEASE TEACH ME", "3 Feb", "https://miro.medium.com/max/2800/1*w96ZU0M9GOAGU_hVXvdH8g.jpeg"); ?>
            </ul>
        </section>

        <section id="message-history" class="page-section col-12 col-sm-10 col-lg-6 p-0 m-0 border border-top-0 border-black vh-100 d-flex flex-column">

            <header class="px-3 sticky-top border-bottom bg-light">
                <div class="d-flex align-items-center">
                    <button id="back-btn" class="btn"><i class="fas fa-arrow-left"></i></button>
                    <img class="profile-picture-xsmall"
                        src="https://mediamass.net/jdd/public/documents/celebrities/6951.jpg"
                        alt="profilePic" />
                    <h2 class="py-3 m-0">Patrick Schwarzenegger</h2>
                </div>
            </header>
            <ul class="p-0 m-0 flex-grow-1 overflow-auto">
                <?php draw_message(true, "Can you take LuLu for a walk?") ?>
                <?php draw_message(false, "Yeah, I'll take her around 3") ?>
                <?php draw_message(true, "Are you coming home for dinner?") ?>
                <?php draw_message(false, "Going to be late, I'll be home by 9") ?>
                <?php draw_message(false, "Hey, can you grab some milk on your way home?") ?>
                <?php draw_message(true, "Sure") ?>
                <?php draw_message(false, "Thanks.") ?>
            </ul>
            <div class="d-flex m-0 p-3 border-top align-items-center">
                <button class="btn btn-outline-primary rounded-circle"><i class="fa fa-plus"></i></button>
                <textarea class="flex-grow-1 mx-3 form-control" rows="1" placeholder="Send a message..." aria-label="Message"></textarea>
                <button class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
            </div>
        

        </section>

        <?php draw_bottom_bar(2) ?>
    </div>
<?php } ?>