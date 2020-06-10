<?php function draw_top_bar($pageTitle, $show_hamburger = false) { ?> 

    <header id="top-bar" class="sticky-top">
        <?php if ($show_hamburger) { ?>
            <button id="hamburger-btn" class="btn"><i class="fas fa-bars fa-1x"></i></button>
        <?php } ?>
        <h2 id="page-title"><?=$pageTitle?></h2>
    </header>    

    <?php if (!$show_hamburger) return; ?>
    <nav id="collapsable-sidebar">
        <div id="collapsable-sidebar-menu" class="d-flex flex-column justify-content-between list-group-flush">
            <div>
                <div class="sidebar-profile">
                    <?php if ($pageTitle != 'Admin Page') { ?> 
                    <a href="profile.php" class="m-2 text-truncate">
                        <img class="profile-picture-small" src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560">
                        <span >Arnold Schwarzenegger</span>
                    </a>
                    <?php } else { ?>
                        <a href="profile.php" class="m-2">
                        <img class="profile-picture-small" src="https://images.forbes.com/media/2009/01/06/0106_govtjobs_02.jpg">
                        <span>Peter Shuckerheard</span>
                    </a>
                    <?php } ?>
                    <button id="hamburger-btn-close" class="btn"><i class="fas fa-times"></i></button>
                </div>

                <a class="list-group-item list-group-item-action" href="profile.php#"><i class="fa fa-user fa-lg fa-fw"></i><span>&nbsp; Profile</span></a>
                <a class="list-group-item list-group-item-action border-bottom" href="friend_requests.php"><i class="fa fa-user-plus fa-lg fa-fw"></i><span>&nbsp; Friend Requests</span></a>
                <?php if ($pageTitle == 'Admin Page') { ?> <a class="list-group-item list-group-item-action border-bottom" href="admin.php"><i class="fa fa-toolbox fa-lg fa-fw"></i><span>&nbsp; Admin Page</span></a><?php } ?>
            </div>
            <div>
                <a class="list-group-item list-group-item-action border-top" href="about.php"><i class="fa fa-question-circle fa-lg fa-fw"></i><span>&nbsp; About</span></a>
                <a class="list-group-item list-group-item-action" href="login.php"><i class="fa fa-sign-out-alt fa-lg fa-fw"></i><span>&nbsp; Sign out</span></a>
            </div>
        </div>
    </nav>
<?php } ?>

<?php function draw_side_bar($selected, $signed_in = true) { ?> 
    <div id="sidebar" class="col-2 sticky-top bg-light border-right d-flex flex-column vh-100 sidebar-wrapper p-0">
        <div class="sidebar-heading">
        <a id="website-brand-sm" href="main.php">
            <svg class="website-logo" alt="urule logo" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100" width="100" height="100"><defs><path d="M35.94 9.78L63 9.78L63 88.65L35.94 88.65L35.94 9.78Z" id="a4f5PnNC0p"></path><path d="M27.95 75.12C27.95 82.58 21.88 88.65 14.42 88.65C6.95 88.65 0.89 82.58 0.89 75.12C0.89 67.65 6.95 61.58 14.42 61.58C21.88 61.58 27.95 67.65 27.95 75.12Z" id="b11O5Fvrzg"></path><path d="M0.89 38.44L27.95 38.44L27.95 75.12L0.89 75.12L0.89 38.44Z" id="f2WBdDjWhM"></path><path d="M14.42 75.12L27.95 75.12L27.95 88.65L14.42 88.65L14.42 75.12Z" id="e5bEKEUP"></path><path d="M99.11 23.31C99.11 30.78 93.05 36.84 85.58 36.84C78.12 36.84 72.05 30.78 72.05 23.31C72.05 15.84 78.12 9.78 85.58 9.78C93.05 9.78 99.11 15.84 99.11 23.31Z" id="h8VDjp5Bq5"></path><path d="M85.58 9.78L99.11 9.78L99.11 36.84L85.58 36.84L85.58 9.78Z" id="d6mzyx0Cvz"></path><path d="M72.05 23.31L99.11 23.31L99.11 36.84L72.05 36.84L72.05 23.31Z" id="aX1UosL04"></path></defs><g><g><g><use xlink:href="#a4f5PnNC0p" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#b11O5Fvrzg" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#f2WBdDjWhM" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#e5bEKEUP" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#h8VDjp5Bq5" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#d6mzyx0Cvz" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#aX1UosL04" opacity="1" fill="#000000" fill-opacity="1"></use></g></g></g></svg>
            <h1 class="website-name">URULE</h1>
        </a>
        </div>
        <nav class="d-flex flex-column justify-content-between list-group list-group-flush flex-grow-1">
            <div>
                <a href="profile.php" class="sidebar-profile m-2">

                <?php if (!$signed_in) { ?>
                    <img class="profile-picture-small"
                        src="https://www.fgbmficonventions.com/assets/img/guest.jpg">
                    <span>Guest</span>
                <?php } else if ($selected != 7)  { ?> 
                    <img class="profile-picture-small"
                        src="https://www.gannett-cdn.com/media/2017/07/30/USATODAY/USATODAY/636370025640688824-Arnold001.JPG?width=2560">
                    <span>Arnold Schwarzenegger</span>
                </a>
                <?php } else { ?>
                    <img class="profile-picture-small"
                        src="https://images.forbes.com/media/2009/01/06/0106_govtjobs_02.jpg">
                    <span>Peter Shuckerheard</span>
                </a>
                <?php } ?>

                <a class="list-group-item list-group-item-action <?=$selected==0?"selected":""?>" href="main.php"><i class="fa fa-home fa-lg fa-fw"></i><span>&nbsp; Main Page</span></a>

                <?php if ($signed_in) { ?>
                    <a class="list-group-item list-group-item-action <?=$selected==1?"selected":""?>" href="profile.php#"><i class="fa fa-user fa-lg fa-fw"></i><span>&nbsp; Profile</span></a>
                    <a class="list-group-item list-group-item-action <?=$selected==2?"selected":""?>" href="messages.php"><i class="fa fa-envelope fa-lg fa-fw"></i><span>&nbsp; Messages</span></a>
                    <a class="list-group-item list-group-item-action <?=$selected==3?"selected":""?>" href="friend_requests.php"><i class="fa fa-user-plus fa-lg fa-fw"></i><span>&nbsp; Friend Requests</span></a>
                <?php } ?>

                <a class="list-group-item list-group-item-action <?=$selected==4?"selected":""?> border-bottom" href="search.php"><i class="fa fa-search fa-lg fa-fw"></i><span>&nbsp; Search</span></a>
                <?php if ($selected == 7) { ?> <a class="list-group-item list-group-item-action <?=$selected==7?"selected":""?> border-bottom" href="admin.php"><i class="fa fa-toolbox fa-lg fa-fw"></i><span>&nbsp; Admin Page</span></a> <?php } ?>
            </div>
            <div>
                <a class="list-group-item list-group-item-action <?=$selected==5?"selected":""?> border-top" href="about.php"><i class="fa fa-question-circle fa-lg fa-fw"></i><span>&nbsp; About</span></a>
                
                
                <?php if ($signed_in) { ?>
                    <a class="list-group-item list-group-item-action <?=$selected==6?"selected":""?>" href="login.php"><i class="fa fa-sign-out-alt fa-lg fa-fw"></i><span>&nbsp; Sign Out</span></a>
                <?php } else { ?>
                    <a class="list-group-item list-group-item-action <?=$selected==6?"selected":""?>" href="login.php"><i class="fa fa-sign-in-alt fa-lg fa-fw"></i><span>&nbsp; Sign In</span></a>
                <?php } ?>
            </div>
        </nav>
    </div>
<?php } ?>

<?php function draw_bottom_bar($selected, $signed_in = true) { ?> 
    <nav id="bottom-bar" class="navbar fixed-bottom bg-light border-top">
        <button type="button" onclick="window.location.href = 'search.php';" class="btn navbar-brand <?=$selected==0?"selected":""?>"><i class="fa fa-search"></i></button>
        <button type="button" onclick="window.location.href = 'main.php';" class="btn navbar-brand <?=$selected==1?"selected":""?>"><i class="fa fa-home"></i></button>
        <?php if ($signed_in) { ?>
            <button type="button" onclick="window.location.href = 'messages.php';" class="btn navbar-brand <?=$selected==2?"selected":""?>"><i class="fa fa-envelope"></i></button>
        <?php } else { ?>
            <button type="button" onclick="window.location.href = 'login.php';" class="btn navbar-brand <?=$selected==4?"selected":""?>"><i class="fa fa-sign-out-alt"></i></button>
        <?php } ?>
    </nav>    
    <!-- onclick e temporario p nao tar já a complicar com javaskree. obg pela compreensao -->
<?php } ?>
