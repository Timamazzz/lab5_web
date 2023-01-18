<?php
$title = 'Animes';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';

include 'config.php';

$animes = $connection->prepare('SELECT * from animes');
$animes->execute();

if (isset($_POST['add'])) {
    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Image = $_POST['Image'];

    if (strlen($Name) < 1 || strlen($Description) < 1 || strlen($Image) < 1)
    {
        if(strlen($Name) < 1) echo 'Имя слишком короткое';
        if(strlen($Description) < 1) echo 'Описание слишком короткое';
    }
    else
    {
        $query = $connection->prepare("INSERT INTO animes(Name, Description, Image) Values(:Name, :Description, :Image)");
        $query->bindParam("Name", $Name, PDO::PARAM_STR);
        $query->bindParam("Description", $Description, PDO::PARAM_STR);
        $query->bindParam("Image", $Image, PDO::PARAM_STR);

        $query->execute();

        $animes = $connection->prepare('SELECT * from animes');
        $animes->execute();
    }
}

if (isset($_POST['redact'])) {
    $id = $_POST['id'];
    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Image = $_POST['Image'];

    if (strlen($Name) < 1 || strlen($Description) < 1 || strlen($Image) < 1)
    {
        if(strlen($Name) < 1) echo 'Имя слишком короткое';
        if(strlen($Description) < 1) echo 'Описание слишком короткое';
    }
    else
    {
        $query = $connection->prepare("UPDATE animes SET Name=:Name, Description=:Description, Image=:Image WHERE id=:id");
        $query->bindParam("id", $id, PDO::PARAM_INT);
        $query->bindParam("Name", $Name, PDO::PARAM_STR);
        $query->bindParam("Description", $Description, PDO::PARAM_STR);
        $query->bindParam("Image", $Image, PDO::PARAM_STR);

        $query->execute();

        $animes = $connection->prepare('SELECT * from animes');
        $animes->execute();
    }
}

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
            <label style="color: white">Name</label>
            <input type="text" name="Name"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">Description</label>
            <input type="text" name="Description"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">Image</label>
            <input type="text" name="Image"/>
        </div>
        <button type="submit" name="add" value="add" >Добавить</button>
    </form>
    <?php while ($anime = $animes->fetch(PDO::FETCH_ASSOC)) {
        echo '    
                <form method="post" action="" style="width: 30%; display: flex; align-items: center; justify-content: center; flex-direction: row">
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">id</label>
                        <label style="color: white">'.$anime['id'].'</label>
                        <input type="hidden" name="id" value="'.$anime['id'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">Name</label>
                        <input type="text" name="Name" value="'.$anime['Name'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">Description</label>
                        <input type="text" name="Description" value="'.$anime['Description'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">Image</label>
                        <input type="text" name="Image" value="'.$anime['Image'].'" />
                    </div>
                    <button type="submit" name="redact" value="redact" >Редактировать</button>
                    <button type="submit" name="delete" value="delete">Удалить</button>
                </form>
               ';
    }?></section>
