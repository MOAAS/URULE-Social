<?php function draw_friend_list() { ?>
<div class="row">
        <?php draw_side_bar(-1) ?> 
        <div class="page-section col-12 col-sm-10 p-0">
            <?php draw_top_bar("Friend List"); ?>            
            <ul class="centered-content">
                <form id="add-group-form" class="list-group-item p-0">
                    <input id="add-group-input" type="text" placeholder="Create a group" class="form-control rounded-0 p-4" >
                    <input type="submit" id="add-group-btn" class="btn btn-primary" value="Add">
                </form>

                <?php draw_friend_group("Work Friends") ?>
                <?php draw_friend("Sylvester Stallone", "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQSujNhlHcMKcuq8habDpysptqvggnj3yBA0vQlBq5HQS6fMzo9") ?>
                <?php draw_friend_group("Family") ?>
                <?php draw_friend("Patrick Schwarzenegger", "https://mediamass.net/jdd/public/documents/celebrities/6951.jpg") ?>
                <?php draw_friend("Miguel Pinto", "https://scontent.fopo3-1.fna.fbcdn.net/v/t1.0-9/48166344_2157217511010561_4089047094743007232_n.jpg?_nc_cat=101&_nc_sid=85a577&_nc_ohc=AHnWh0H3aVIAX_qoUZP&_nc_ht=scontent.fopo3-1.fna&oh=4122b6f9da68aa46c5ceef7af8e700e7&oe=5E93EF88") ?>
                <?php draw_friend_group("The Boys") ?>
                <?php draw_friend("Daniel Brandão", "https://ae01.alicdn.com/kf/HTB19PzaX0zvK1RkSnfoq6zMwVXaY.jpg") ?>
                <?php draw_friend("Pedro Moás", "https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/s960x960/54435063_2186294784790588_3646571120003383296_o.jpg?_nc_cat=106&_nc_sid=8024bb&_nc_ohc=bBe6eWJ06fMAX9vRVLg&_nc_ht=scontent.flis7-1.fna&_nc_tp=7&oh=1005f1f599b8512044c87ce7510ca3e5&oe=5E8F2897") ?>
                <?php draw_friend("William Randy", "https://miro.medium.com/max/2800/1*w96ZU0M9GOAGU_hVXvdH8g.jpeg") ?>
            </ul>

        </div>
        
        <?php draw_bottom_bar(-1) ?>  
    </div>
<?php } ?>

<?php function draw_friend_group($name) { ?>
    <li class="list-group-item list-group-item-secondary d-flex align-items-center">
        <h4 class="m-0 d-inline"><?=$name?></h4>
        <button class="btn ml-auto"><i class="fas fa-user-plus text-dark"></i></button>
        <button class="btn mx-3"><i class="fas fa-edit text-dark"></i></button>
        <button class="btn"><i class="fa fa-times"></i></button>
    </li>
<?php } ?>

<?php function draw_friend($name, $image) { ?>
    <li class="list-group-item d-flex align-items-center">
        <img class="profile-picture-small" src="<?=$image?>">
        <span class="name text-truncate"><?=$name?></span>
        <button class="btn ml-auto"><i class="fa fa-minus"></i></button>
    </li>
<?php } ?>

