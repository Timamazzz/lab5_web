<?php
$title = 'Animes';
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
    <form method="post" action="" style="width: 30%; display: flex; align-items: center; justify-content: center; flex-direction: row">
        <div style="display: flex; flex-direction: column">
            <label style="color: white">name</label>
            <input type="text" name="name"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">email</label>
            <input type="email" name="email"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">message</label>
            <input type="text" name="massage"/>
        </div>
        <button type="submit" name="add" value="add" action="sending.php">Добавить</button>
    </form>

    <?php while ($mail = $mails->fetch(PDO::FETCH_ASSOC)) {
        echo '    
                <form method="post" action="" style="width: 30%; display: flex; align-items: center; justify-content: center; flex-direction: row">
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">id</label>
                        <label style="color: white">'.$mail['id'].'</label>
                        <input type="hidden" name="id" value="'.$mail['id'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">name</label>
                        <input type="text" name="name" value="'.$mail['name'].'"/>
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">email</label>
                        <input type="email" name="email" value="'.$mail['email'].'"/>
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">message</label>
                        <input type="text" name="massage" value="'.$mail['message'].'"/>
                    </div>
                    <button type="submit" name="redact" value="redact">Отправить еще раз</button>
                    <button type="submit" name="delete" value="delete">Удалить</button>
    </form>
               ';
    }?>

</section>
