<?php function draw_about_page() { ?> 
    <div class="row">    
        <?php draw_side_bar(5); ?>

        <section id="main-page" class="p-0 col-sm-10 page-section">
            <?php draw_top_bar("About", true); ?>
            <div class="jumbotron bg-white centered-content">
                <h1 class="display-4">
                    <svg class="website-logo" alt="urule logo" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100" width="100" height="100"><defs><path d="M35.94 9.78L63 9.78L63 88.65L35.94 88.65L35.94 9.78Z" id="a4f5PnNC0p"></path><path d="M27.95 75.12C27.95 82.58 21.88 88.65 14.42 88.65C6.95 88.65 0.89 82.58 0.89 75.12C0.89 67.65 6.95 61.58 14.42 61.58C21.88 61.58 27.95 67.65 27.95 75.12Z" id="b11O5Fvrzg"></path><path d="M0.89 38.44L27.95 38.44L27.95 75.12L0.89 75.12L0.89 38.44Z" id="f2WBdDjWhM"></path><path d="M14.42 75.12L27.95 75.12L27.95 88.65L14.42 88.65L14.42 75.12Z" id="e5bEKEUP"></path><path d="M99.11 23.31C99.11 30.78 93.05 36.84 85.58 36.84C78.12 36.84 72.05 30.78 72.05 23.31C72.05 15.84 78.12 9.78 85.58 9.78C93.05 9.78 99.11 15.84 99.11 23.31Z" id="h8VDjp5Bq5"></path><path d="M85.58 9.78L99.11 9.78L99.11 36.84L85.58 36.84L85.58 9.78Z" id="d6mzyx0Cvz"></path><path d="M72.05 23.31L99.11 23.31L99.11 36.84L72.05 36.84L72.05 23.31Z" id="aX1UosL04"></path></defs><g><g><g><use xlink:href="#a4f5PnNC0p" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#b11O5Fvrzg" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#f2WBdDjWhM" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#e5bEKEUP" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#h8VDjp5Bq5" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#d6mzyx0Cvz" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#aX1UosL04" opacity="1" fill="#000000" fill-opacity="1"></use></g></g></g></svg>
                    <span class="website-name">URULE</span>
                </h1>
                <p class="lead">This is a social network designed to allow users to create new relationships, making it easier to share moments with friends, and connecting people in a fun way.
                <hr class="my-4">
                <p>You can share your thoughts and images with every other user, or privately message a long time friend. </p>
                <p>Brought to you by:</p>
                <ul>
                    <li>Alexandre Carqueja</li>
                    <li>Daniel Brandão</li>
                    <li>Henrique Santos</li>
                    <li>Pedro Moás</li>
                </ul>
            </div>
        </section>

        <?php draw_bottom_bar(3) ?>
    </div>
<?php } ?>