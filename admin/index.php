<?php
/* * ** - Functions and Lang - *** */
require_once('../config/init.php');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Algolia API - Marcos Hernandez</title>
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="/js/main.js"></script>
    </head>
    <body>
        <div id="wrapper">
            <header>
                <?php require_once("includes/header.php") ?>
            </header>

            <div class="container-fluid">
                <div class="row d-flex align-items-start">
                    <?php require_once("includes/sidebar.php");  ?>
                    <main class="col-md-10 p-4 max_screen_size">
                        <?php $Router = new \Core\Router(); ?>
                    </main>

                </div>
            </div>  <!-- /.container-fluid -->

        </div>  <!-- /#wrapper -->

        <!-- Latest compiled and minified CSS -->
        <link href="https://getbootstrap.com/docs/4.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/v4-shims.css">
        <script src="<?= LIBRARY ?>/js/main.js"></script>
        <link rel="stylesheet" href="/styles/main.css<?= "?" . CACHE ?>">

    </body>
</html>
