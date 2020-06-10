<?php function draw_profile_self() { ?>
    <div class="row">
        <?php draw_side_bar(1); ?>

        <div id="profile-page" class="p-0 col-sm-10 page-section centered-content">
            <button type="button" class="bg-primary round-button top-right-button" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fas fa-edit icon-white"></i></button>
            <div id="CoverPhotoContainer" class="border-bottom">
                <img src="https://live.staticflickr.com/1856/44415385681_7bdbc3f48d_b.jpg" alt="Cover Photo" id="CoverPhoto"/>
            </div>
            <img src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560" alt="Profile Picture" id="ProfilePicture"/>
            <div id="profile-stats" class="centered-content">
                <p id="ProfileLocation" class="profile-info"><i class="fa fa-map-marker-alt"></i> AT - Thal</p>
                <a id="NumberOfFriends" class="profile-info" href="friend_list.php"><i class="fa fa-user-friends"></i> 10364</a>
            </div>

            <div id="ProfileName" class="text-center mt-3">
                <p id="person-name" class="my-1">Arnold Schwarzenegger</p>
                <p class="mt-1"> 🎂 30/Jul</p>
            </div>
            <div id="profile-posts" class="centered-content">
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "I was proud to join my friends John Kasich and John Kerry for our first WorldWarZeroOrg townhall in Columbus. The only way we can terminate pollution is Republicans and Democrats working together. There isn’t Republican air or Democratic air. It’s time to unite & protect health.",  "08 Mar", true); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "Since there are questions: all sports will continue, and I am looking forward to seeing the athletes compete. I’ll be keeping everyone updated on proper precautions throughout the weekend.",  "04 Mar", true); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560","Kobe was an icon on the court, a legend for the way he gave back to his community, and an inspiration to the world. I consider myself lucky to have witnessed both his talent and his big heart. My thoughts are with the Bryant family on this unthinkable day.",  "26 Jan", true); ?>
            </div>
             <!-- Modal -->
             <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-5">
                        <div id="CoverPhotoContainer" class="border-bottom">
                            <img class="img-fluid" src="https://live.staticflickr.com/1856/44415385681_7bdbc3f48d_b.jpg" alt="Cover Photo" id="CoverPhoto"/>
                        </div>
                        <div class="text-center">
                            <img src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560" alt="Profile Picture" id="ProfilePicture"/>
                        </div>
                    </div>
                    <form>
                       <div class="form-group">
                         <label for="recipient-name" class="col-form-label">Username:</label>
                         <input type="text" class="form-control" id="recipient-name" value="Arnold Schwarzenegger">
                       </div>
                       <div class="form-group">
                         <label for="recipient-birthdate" class="col-form-label">Birthdate:</label>
                         <input type="text" class="form-control" id="recipient-birthdate" value="30/Jul">
                       </div>
                       <div class="form-group">
                         <label for="recipient-location" class="col-form-label">Location:</label>
                         <input type="text" class="form-control" id="recipient-location" value="AU - Thal">
                       </div>
                     </form>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-auto">Delete Account</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <?php draw_bottom_bar(-1); ?>

    </div>

<?php } ?>

<?php function draw_profile_anon() { ?>
    <div class="row">
        <?php draw_side_bar(-1); ?>

        <div id="profile-page" class="p-0 col-sm-10 page-section centered-content">
            <div id="CoverPhotoContainer" class="border-bottom">
                <img src="https://live.staticflickr.com/1856/44415385681_7bdbc3f48d_b.jpg" alt="Cover Photo" id="CoverPhoto"/>
            </div>
            <img src="https://i.pinimg.com/564x/7d/fe/75/7dfe75b95dbd539ca11bb83892e705be.jpg" alt="Profile Picture" id="ProfilePicture"/>
            <div id="profile-stats" class="centered-content">
                <p id="ProfileLocation" class="profile-info"><i class="fa fa-map-marker-alt"></i> AU - Thal</p>
                <a id="NumberOfFriends" class="profile-info" href="friend_list.php"><i class="fa fa-user-friends"></i> 10364</a>
            </div>

            <div id="ProfileName" class="text-center mt-3">
                <p id="person-name" class="my-1">Sylvester stallone</p>
                <p class="mt-1"> 🎂 30/Jul</p>
            </div>

            <div id="friendButtons" class="centered-content d-flex justify-content-around">
                <button type="button" class="btn btn-primary">Add Friend</button>                
            </div>

            <div id="profile-posts" class="centered-content">
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "I was proud to join my friends John Kasich and John Kerry for our first WorldWarZeroOrg townhall in Columbus. The only way we can terminate pollution is Republicans and Democrats working together. There isn’t Republican air or Democratic air. It’s time to unite & protect health.",  "08 Mar"); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "Since there are questions: all sports will continue, and I am looking forward to seeing the athletes compete. I’ll be keeping everyone updated on proper precautions throughout the weekend.",  "04 Mar"); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560","Kobe was an icon on the court, a legend for the way he gave back to his community, and an inspiration to the world. I consider myself lucky to have witnessed both his talent and his big heart. My thoughts are with the Bryant family on this unthinkable day.",  "26 Jan"); ?>
            </div>
        </div>

        <?php draw_bottom_bar(-1); ?>

    </div>

<?php } ?>

<?php function draw_profile_friend() { ?>
    <div class="row">
        <?php draw_side_bar(1); ?>

        <div id="profile-page" class="p-0 col-sm-10 page-section centered-content">
            <div id="CoverPhotoContainer" class="border-bottom">
                <img src="https://live.staticflickr.com/1856/44415385681_7bdbc3f48d_b.jpg" alt="Cover Photo" id="CoverPhoto"/>
            </div>
            <img src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560" alt="Profile Picture" id="ProfilePicture"/>
            <div id="profile-stats" class="centered-content">
                <p id="ProfileLocation" class="profile-info"><i class="fa fa-map-marker-alt"></i> AU - Thal</p>
                <a id="NumberOfFriends" class="profile-info" href="friend_list.php"><i class="fa fa-user-friends"></i> 10364</a>
            </div>

            <div id="ProfileName" class="text-center mt-3">
                <p id="person-name" class="my-1">Arnold Schwarzenegger</p>
                <p class="mt-1"> 🎂 30/Jul</p>
            </div>

            <div id="friendButtons" class="centered-content d-flex justify-content-around">
                <button type="button" class="btn btn-secondary">Remove Friend</button>
                <a href="messages.php"><button type="button" class="btn btn-primary">Send Message</button></a>
            </div>

            <div id="profile-posts" class="centered-content">
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "I was proud to join my friends John Kasich and John Kerry for our first WorldWarZeroOrg townhall in Columbus. The only way we can terminate pollution is Republicans and Democrats working together. There isn’t Republican air or Democratic air. It’s time to unite & protect health.",  "08 Mar"); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560", "Since there are questions: all sports will continue, and I am looking forward to seeing the athletes compete. I’ll be keeping everyone updated on proper precautions throughout the weekend.",  "04 Mar"); ?>
                <?php draw_post("Arnold Schwarzenegger", "https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560","Kobe was an icon on the court, a legend for the way he gave back to his community, and an inspiration to the world. I consider myself lucky to have witnessed both his talent and his big heart. My thoughts are with the Bryant family on this unthinkable day.",  "26 Jan"); ?>
            </div>
        </div>

        <?php draw_bottom_bar(-1); ?>

    </div>

<?php } ?>
