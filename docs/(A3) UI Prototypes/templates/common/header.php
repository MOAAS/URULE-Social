<?php function draw_header($css_files) { ?> 
    <!doctype html>
    <html lang="en">

        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <?php foreach ($css_files as $css_file) { ?>
                <link rel="stylesheet" type="text/css" href="<?="../css/" . $css_file?>">
            <?php } ?>

            <script src="../js/general.js" defer></script>

            <link rel="stylesheet" type="text/css" href="../css/style.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" crossorigin="anonymous">
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">

            

            <title>URULE | Main Page</title>
        </head>

        <body>
<?php } ?>