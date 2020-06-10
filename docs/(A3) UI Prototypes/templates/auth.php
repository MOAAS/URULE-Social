<?php function draw_auth_header() { ?>
    <div id="website-brand-xl" class="p-4 bg-primary" href="main.php">
        <svg id="website-logo" class="ml-auto" alt="urule logo" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100" width="100" height="100"><defs><path d="M35.94 9.78L63 9.78L63 88.65L35.94 88.65L35.94 9.78Z" id="a4f5PnNC0p"></path><path d="M27.95 75.12C27.95 82.58 21.88 88.65 14.42 88.65C6.95 88.65 0.89 82.58 0.89 75.12C0.89 67.65 6.95 61.58 14.42 61.58C21.88 61.58 27.95 67.65 27.95 75.12Z" id="b11O5Fvrzg"></path><path d="M0.89 38.44L27.95 38.44L27.95 75.12L0.89 75.12L0.89 38.44Z" id="f2WBdDjWhM"></path><path d="M14.42 75.12L27.95 75.12L27.95 88.65L14.42 88.65L14.42 75.12Z" id="e5bEKEUP"></path><path d="M99.11 23.31C99.11 30.78 93.05 36.84 85.58 36.84C78.12 36.84 72.05 30.78 72.05 23.31C72.05 15.84 78.12 9.78 85.58 9.78C93.05 9.78 99.11 15.84 99.11 23.31Z" id="h8VDjp5Bq5"></path><path d="M85.58 9.78L99.11 9.78L99.11 36.84L85.58 36.84L85.58 9.78Z" id="d6mzyx0Cvz"></path><path d="M72.05 23.31L99.11 23.31L99.11 36.84L72.05 36.84L72.05 23.31Z" id="aX1UosL04"></path></defs><g><g><g><use xlink:href="#a4f5PnNC0p" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#b11O5Fvrzg" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#f2WBdDjWhM" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#e5bEKEUP" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#h8VDjp5Bq5" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#d6mzyx0Cvz" opacity="1" fill="#000000" fill-opacity="1"></use></g><g><use xlink:href="#aX1UosL04" opacity="1" fill="#000000" fill-opacity="1"></use></g></g></g></svg>
        <h1 id="website-name" class="ml-2 mr-auto">URULE</h1>
    </div>
<?php } ?>

<?php function draw_form_input($label, $id, $type, $placeholder, $note = "") { ?>
    <div class="form-group">
        <label for="<?=$id?>"><?=$label?></label>
        <input id="<?=$id?>" type="<?=$type?>" name="<?=$id?>" class="form-control" placeholder="<?=$placeholder?>">
        <?php if ($note != "") { ?>
            <small class="form-text text-muted"><?=$note?></small>
        <?php } ?>        
    </div>
<?php } ?>

<?php function draw_google_icon($size) { ?>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="<?=$size?>px" height="<?=$size?>px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
<?php } ?>