<?php function draw_admin() { ?> 
    <div class="row">    
        <?php draw_side_bar(7); ?>

        <section id="admin-page" class="p-0 col-sm-10 page-section">
            <?php draw_top_bar("Admin Page"); ?>

            <div class="centered-content">
                <div class="card shadow-sm mb-4">
                    <div class="card-title bg-light p-2 m-0">
                        <a href="profile.php" class="sidebar-profile">
                            <img class="profile-picture-small" src="https://images.forbes.com/media/2009/01/06/0106_govtjobs_02.jpg">
                            <span>Peter Shuckerheard</span>
                        </a>
                    </div>

                    <form id="post-form">
                        <textarea id="announcement-input" class="form-control" placeholder="Make an announcement" rows="4"></textarea>        
                        <span class="m-2">Duration:</span>                   
                        <div id="post-settings"  class="form-row align-items-center px-2">
                            <div class="col-md-1 col-3">
                                <input type="number" class="form-control" placeholder="1">
                            </div>
                            <div class="col-md-2 col-6">
                                <select class="form-control">
                                    <option>Hours</option>
                                    <option>Days</option>
                                    <option>Weeks</option>
                                    <option>Months</option>
                                </select>
                            </div>
                            <input type="submit" class="ml-auto m-2 btn btn-primary" value="Post">
                        </div>
                    </form>
                </div>             

                <div class="mt-5">
                    <h3>Users</h3>
                    <form class=" mb-3">
                        <div class="input-group mt-3 mb-2">
                            <div class="input-group-prepend"><i class="fas fa-search input-group-text font-weight-bold"></i></div>        
                            <input type="text" class="form-control" placeholder="Search for users username or id" aria-label="Search">        
                        </div>
                        <input id="search-button" type="submit" class="btn btn-primary px-2 py-1 " value="Search">
                    </form>

                    <ul class="list-group mt-3 mb-3">
                        <li class="list-group-item list-group-item-dark d-flex align-items-center justify-content-between ">
                            <span class="number font-weight-bold">#</span>
                            <span class="name text-truncate ml-4 font-weight-bold">Profile</span>
                            <span class="ml-auto mr-2 font-weight-bold">Action</i></button>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="number">1</span>
                            <img class="profile-picture-small ml-4" src="https://scontent.fopo3-1.fna.fbcdn.net/v/t1.0-9/48166344_2157217511010561_4089047094743007232_n.jpg?_nc_cat=101&_nc_sid=85a577&_nc_ohc=AHnWh0H3aVIAX_qoUZP&_nc_ht=scontent.fopo3-1.fna&oh=4122b6f9da68aa46c5ceef7af8e700e7&oe=5E93EF88">
                            <span class="name text-truncate">Miguel Pinto</span>
                            <button class="btn btn-danger btn-sm ml-auto mr-2"  title="Delete">Ban</i></button>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="number">2</span>
                            <img class="profile-picture-small ml-4" src="https://ae01.alicdn.com/kf/HTB19PzaX0zvK1RkSnfoq6zMwVXaY.jpg">
                            <span class="name text-truncate">Daniel Brandão</span>
                            <button class="btn btn-danger btn-sm ml-auto mr-2" title="Delete">Ban</i></button>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="number">3</span>
                            <img class="profile-picture-small ml-4" src="https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/s960x960/54435063_2186294784790588_3646571120003383296_o.jpg?_nc_cat=106&_nc_sid=8024bb&_nc_ohc=bBe6eWJ06fMAX9vRVLg&_nc_ht=scontent.flis7-1.fna&_nc_tp=7&oh=1005f1f599b8512044c87ce7510ca3e5&oe=5E8F2897">
                            <span class="name text-truncate">Pedro Moas</span>
                            <button class="btn btn-danger btn-sm ml-auto mr-2" title="Delete">Ban</i></button>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="number">4</span>
                            <img class="profile-picture-small ml-4" src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560">
                            <span class="name text-truncate">Arnold Schwarzenegger</span>
                            <button class="btn btn-danger btn-sm ml-auto mr-2" title="Delete">Ban</i></button>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="number">5</span>
                            <img class="profile-picture-small ml-4" src="https://miro.medium.com/max/2800/1*w96ZU0M9GOAGU_hVXvdH8g.jpeg">
                            <span class="name text-truncate">William Randy</span>
                            <button class="btn btn-danger btn-sm ml-auto mr-2" title="Delete">Ban</i></button>
                        </li>
                    </ul>

                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled"><a class="page-link">Previous</a></li>
                            <li class="page-item active"><a class="page-link">1 <span class="sr-only">(current)</span></a></li>
                            <li class="page-item"><a class="page-link">2</a></li>
                            <li class="page-item"><a class="page-link">3</a></li>
                            <li class="page-item"><a class="page-link">4</a></li>
                            <li class="page-item"><a class="page-link">5</a></li>
                            <li class="page-item"><a class="page-link">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>

        <?php draw_bottom_bar(-1) ?>  
    </div>
<?php } ?>