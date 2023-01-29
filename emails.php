<?php
$title = 'mails';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
include 'config.php';

$mails = $connection->prepare('SELECT * from mails');
$mails->execute();

if (isset($_POST['add']) || isset($_POST['redact'])) {
    require('sending.php');
    $mails = $connection->prepare('SELECT * from mails');
    $mails->execute();
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = $connection->prepare("DELETE FROM mails WHERE id=:id");
    $query->bindParam("id", $id, PDO::PARAM_INT);
    $query->execute();

    $mails = $connection->prepare('SELECT * from mails');
    $mails->execute();
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
        <h1 style="color: white">Письма</h1>
    </div>
    <table cellpadding="5px" cellspacing="5px">
        <tr>
            <th style="color: white">id</th>
            <th style="color: white">name</th>
            <th style="color: white">email</th>
            <th style="color: white">message</th>
        </tr>
        <?php
        while ($mail = $mails->fetch(PDO::FETCH_ASSOC)) {
            echo '
                    <tr>
                        <td style="color:white;">'.$mail['id'].'</td>
                        <td style="color:white;">'.$mail['name'].'</td>
                        <td style="color:white;">'.$mail['email'].'</td>
                        <td style="color:white;">'.$mail['message'].'</td>
                        <td><form method="post">
                                <input type="hidden" name="id" value="'.$mail['id'].'">
                                <input type="hidden" name="name" value="'.$mail['name'].'"/>
                                <input type="hidden" name="email" value="'.$mail['email'].'"/>
                                <input type="hidden" name="massage" value="'.$mail['message'].'"/>
                                <button type="submit" name="add" value="delete">Отправить еще раз</button>
                                <button type="submit" name="delete" value="delete">Удалить</button>
                            </form>
                        </td>
                    </tr>
                ';
        }
        ?>
    </table>
</section>
