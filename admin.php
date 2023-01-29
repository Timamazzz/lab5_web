<?php
$title = 'Admin';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
include_once ('config.php');
$query = $connection->prepare('SELECT * from animes');
$query->execute();
?>

<section class="section">
    <?php
    session_status() === PHP_SESSION_ACTIVE || session_start();

    if(!isset($_SESSION['user_id'])){
        echo 'Только админам!';
        exit;
    }
    else{
        if(!$_SESSION['user_isAdmin']) {
            echo 'У вас нет прав для просмотра этой страницы';
            exit;
        };
    }

    ?>
    <div class="cards_list">
        <nav class="container nav">
            <a href="http://localhost/aniuwu/users.php">Пользователи</a>
        </nav>
        <nav class="container nav">
            <a href="http://localhost/aniuwu/animes.php">Аниме</a>
        </nav>
        <nav class="container nav">
            <a href="http://localhost/aniuwu/emails.php">Письма</a>
        </nav>
    </div>
</section>
