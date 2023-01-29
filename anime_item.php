<?php
$title = 'anime';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';

include_once ('config.php');
$id = isset($_GET['id']) ? $_GET['id'] : null;

$query = $connection->prepare("SELECT * FROM animes WHERE id=:id");
$query->bindParam("id", $id, PDO::PARAM_STR);
$query->execute();
$anime = $query->fetch(PDO::FETCH_ASSOC);
?>

<section class="section">
    <div class="container">
        <div class="container__item">
            <div class="block__img">
                <img src=<?php echo $anime['Image'] ?> alt="">
            </div>
            <div class="block__text-anime">
                <h1 class="anime__title"><?php echo $anime['Name'] ?></h1>
                <p class="anime__description"><?php echo $anime['Description'] ?></p>
            </div>
        </div>


    </div>
</section>
