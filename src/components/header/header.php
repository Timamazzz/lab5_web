<?php header("Content-type: text/html;charset=utf-8");
include('config.php');
$query = $connection->prepare('SELECT mytext from header where id = 1');
$query->execute();
$sendMessage = $query->fetch(PDO::FETCH_ASSOC)['mytext'];

$query = $connection->prepare('SELECT mytext from header where id = 2');
$query->execute();
$checkSize = $query->fetch(PDO::FETCH_ASSOC)['mytext'];

$query = $connection->prepare('SELECT mytext from header where id = 3');
$query->execute();
$login = $query->fetch(PDO::FETCH_ASSOC)['mytext'];

session_status() === PHP_SESSION_ACTIVE ? null : session_start();

if (isset($_POST['exit'])) {
    session_destroy();
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            <?php echo $title; ?>
        </title>
        <meta name="theme-color" content="#fff">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="shortcut icon" href="src/img/favicons/favicon.ico" type="image/x-icon">
        <link rel="icon" sizes="16x16" href="src/img/favicons/favicon-16x16.png" type="image/png">
        <link rel="icon" sizes="32x32" href="src/img/favicons/favicon-32x32.png" type="image/png">
        <link rel="apple-touch-icon-precomposed" href="src/img/favicons/apple-touch-icon-precomposed.png">
        <link rel="apple-touch-icon" href="src/img/favicons/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="57x57" href="src/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="src/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="src/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="src/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="src/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="src/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="src/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="src/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="167x167" href="src/img/favicons/apple-touch-icon-167x167.png">
        <link rel="apple-touch-icon" sizes="180x180" href="src/img/favicons/apple-touch-icon-180x180.png">
        <link rel="apple-touch-icon" sizes="1024x1024" href="src/img/favicons/apple-touch-icon-1024x1024.png">
        <link rel="stylesheet" href="src/styles/main.css">
    </head>

    <body>
        <header class="header">
            <div class="container flex">
                <a class='logo container' href="http://localhost/aniuwu/index.php">
                    <img class='logoImage' src="src/img/1619261518_21-phonoteka_org-p-anime-kartinki-bez-fona-29.png" alt="">
                </a>
                <nav class="container nav">
                    <a href="http://localhost/aniuwu/email.php"><?php echo $sendMessage ?></a>
                </nav>
                <nav class="container nav">
                    <a href="http://localhost/aniuwu/size.php"><?php echo $checkSize ?></a>
                </nav>
                    <?php
                    if(!isset($_SESSION['user_id'])){
                        echo '<nav class="container nav"> <a href="login.php">Вход</a> </nav>';
                    }
                    else {
                        if(isset($_SESSION['user_isAdmin'])){
                            if($_SESSION['user_isAdmin'])
                                echo '<nav class="container nav"> <a href="admin.php">Админка</a> </nav>';
                        };
                        echo    '<form method="post">
                                    <button type="submit" name="exit" value="exit"" >Выйти</button> 
                                </form>';}
                    ?>
            </div>
        </header>
