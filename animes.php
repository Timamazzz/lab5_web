<?php
$title = 'Animes';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';

include 'config.php';

$animes = $connection->prepare('SELECT * from animes');
$animes->execute();

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = $connection->prepare("DELETE FROM animes WHERE id=:id");
    $query->bindParam("id", $id, PDO::PARAM_INT);
    $query->execute();

    $animes = $connection->prepare('SELECT * from animes');
    $animes->execute();
}
?>

<section style="
    height: 100%;
    display: flex;
    justify-content: center;
    background-color: #333;
    width: 80%;
    margin: 0 auto;
    border-radius: 20px;
    flex-direction: column;
    flex-wrap: wrap;
    align-content: center;">
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
    <div style="display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;">
        <h1 style="color: white">Аниме</h1>
        <a href="http://localhost/aniuwu/animeform.php" style="color: forestgreen; margin-left: 15px">Добавить</a>
    </div>
    <table cellpadding="5px" cellspacing="5px">
        <tr>
            <th style="color: white">id</th>
            <th style="color: white">Name</th>
            <th style="color: white">Description</th>
            <th style="color: white">Image</th>
        </tr>
        <?php
            while ($anime = $animes->fetch(PDO::FETCH_ASSOC)) {
                echo '
                    <tr>
                        <td style="color:white;">'.$anime['id'].'</td>
                        <td style="color:white;">'.$anime['Name'].'</td>
                        <td style="color:white;">'.$anime['Description'].'</td>
                        <td style="color:white;">'.$anime['Image'].'</td>
                        <td><a href="http://localhost/aniuwu/animeform.php?id='.$anime['id'].'" style="color: forestgreen">Редактировать</a></td>
                        <td><form method="post">
                                <input type="hidden" name="id" value="'.$anime['id'].'">
                                <button type="submit" name="delete" value="delete">Удалить</button>
                            </form>
                        </td>
                    </tr>
                ';
            }
        ?>
    </table>
</section>
