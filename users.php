<?php
$title = 'Animes';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
include_once 'config.php';

$users = $connection->prepare('SELECT * from users');
$users->execute();

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = $connection->prepare("DELETE FROM users WHERE id=:id");
    $query->bindParam("id", $id, PDO::PARAM_INT);
    $query->execute();

    $users = $connection->prepare('SELECT * from users');
    $users->execute();
    flash('user_delete', 'Пользователь успешно удален', FLASH_SUCCESS);
}

?>

<section style="
    height: 100%;
    display: flex;
    justify-content: center;
    padding-top: 5%;
    background-color: #333;
    width: 80%;
    margin: 0 auto;
    border-radius: 20px;
    flex-direction: column;
    flex-wrap: wrap;
    align-content: center">
    <?php flash(); ?>
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
        <h1 style="color: white">Пользователи</h1>
        <a href="http://localhost/aniuwu/userform.php" style="color: forestgreen; margin-left: 15px">Добавить</a>
    </div>
    <table cellpadding="5px" cellspacing="5px">
        <tr>
            <th style="color: white">id</th>
            <th style="color: white">Username</th>
            <th style="color: white">Email</th>
            <th style="color: white">Is Admin</th>
        </tr>
        <?php
        while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
            $isAdmin = $user['isAdmin'] ? 'checked' : '';
            echo '
                    <tr>
                        <td style="color:white;">'.$user['id'].'</td>
                        <td style="color:white;">'.$user['username'].'</td>
                        <td style="color:white;">'.$user['email'].'</td>
                        <td style="color:white;"><input type="checkbox" name="isAdmin" '.$isAdmin.' disabled /></td>
                        <td><a href="http://localhost/aniuwu/userform.php?id='.$user['id'].'" style="color: forestgreen">Редактировать</a></td>
                        <td><form method="post">
                                <input type="hidden" name="id" value="'.$user['id'].'">
                                <button type="submit" name="delete" value="delete">Удалить</button>
                            </form>
                        </td>
                    </tr>
                ';
        }
        ?>
    </table>
</section>
