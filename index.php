<?php
$title = 'Main';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
include_once ('config.php');


$query = $connection->prepare('SELECT * from animes');
$query->execute();


?>

<section style="height: 100%;
    display: flex;
    justify-content: center;
    padding-top: 5%;
    background-color: #333;
    width: 80%;
    margin: 0 auto;
    border-radius: 20px;
    flex-direction: column;
    flex-wrap: wrap;
    align-items: center;">
    <?php flash('sending'); ?>
    <div class="cards_list">
        <div class="card__menu">
            <?php while ($anime = $query->fetch(PDO::FETCH_ASSOC)) {
                echo '
                    <a href="anime_item.php?id='.$anime['id'].'" class="card__item">
                        <img src="' . $anime['Image'] . '" class="img" alt="">
                        <div class="block_text">
                            <p class="name">' . $anime['Name'] . '</p>
                            <p class="description">' . $anime['Description'] .'</p>
                        </div>
                    </a>';
            }?>
        </div>
    </div>
</section>
