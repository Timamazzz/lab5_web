<?php
$id = isset($_GET['id']) ? $_GET['id'] : null;
$Name = null;
$Description = null;
$Image = null;
$isRedact = ($id != null);
include_once ('config.php');
if(!$isRedact)
    $title = 'Add anime';
else {
    $title = 'Redact anime';
    $query = $connection->prepare("SELECT * FROM animes WHERE id=:id");
    $query->bindParam("id", $id, PDO::PARAM_STR);
    $query->execute();
    $anime = $query->fetch(PDO::FETCH_ASSOC);
    $Name = $anime['Name'];
    $Description = $anime['Description'];
    $Image = $anime['Image'];
}
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';

if (isset($_POST['redact'])) {
    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    if (strlen($Name) < 1 || strlen($Description) < 1 || strlen($Image) < 1)
    {
        if(strlen($Name) < 1) echo 'Имя слишком короткое';
        if(strlen($Description) < 1) echo 'Описание слишком короткое';
    }
    else
    {
        $image=$_FILES['Image']['name'];
        $imageArr=explode('.',$image); //first index is file name and second index file type
        $rand=rand(10000,99999);
        $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
        $uploadPath="src/localimg/".$newImageName;
        $isUploaded=move_uploaded_file($_FILES["Image"]["tmp_name"],$uploadPath);
        $Image = $uploadPath;

        $query = $connection->prepare("UPDATE animes SET Name=:Name, Description=:Description, Image=:Image WHERE id=:id");
        $query->bindParam("id", $id, PDO::PARAM_INT);
        $query->bindParam("Name", $Name, PDO::PARAM_STR);
        $query->bindParam("Description", $Description, PDO::PARAM_STR);
        $query->bindParam("Image", $Image, PDO::PARAM_STR);

        $query->execute();

        $animes = $connection->prepare('SELECT * from animes');
        $animes->execute();
        flash('anime_redact', 'Аниме успешно отредактировано', FLASH_SUCCESS);
        header('Location: http://localhost/aniuwu/animes.php ');
    }
}
if (isset($_POST['add'])) {
    $Name = $_POST['Name'];
    $Description = $_POST['Description'];

    if (strlen($Name) < 1 || strlen($Description) < 1)
    {
        if(strlen($Name) < 1) echo 'Имя слишком короткое';
        if(strlen($Description) < 1) echo 'Описание слишком короткое';
    }
    else
    {
       $image=$_FILES['Image']['name'];
        $imageArr=explode('.',$image); //first index is file name and second index file type
        $rand=rand(10000,99999);
        $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
        $uploadPath="src/localimg/".$newImageName;
        $isUploaded=move_uploaded_file($_FILES["Image"]["tmp_name"],$uploadPath);
        $Image = $uploadPath;

        $query = $connection->prepare("INSERT INTO animes(Name, Description, Image) Values(:Name, :Description, :Image)");
        $query->bindParam("Name", $Name, PDO::PARAM_STR);
        $query->bindParam("Description", $Description, PDO::PARAM_STR);
        $query->bindParam("Image", $Image, PDO::PARAM_STR);

        $query->execute();

        $animes = $connection->prepare('SELECT * from animes');
        $animes->execute();
        flash('anime_add', 'Аниме успешно созданно', FLASH_SUCCESS);
        header('Location: http://localhost/aniuwu/animes.php ');
    }
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
    align-content: center;
    padding-bottom: 5%">
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
    <table cellpadding="5px" cellspacing="5px">
        <tr>
            <?php if($isRedact)
              echo '<th style="color: white">id</th>';
            ?>
            <th style="color: white">Name</th>
            <th style="color: white">Description</th>
            <th style="color: white">Image</th>
        </tr>
        <form method="post" enctype="multipart/form-data">
            <tr>
                <?php if($isRedact) echo '<td><label style="color: white">'.$id.'</label></td>'; ?>
                <td><input type="text" name="Name" value="<?php echo $Name ?>" /></td>
                <td><textarea name="Description" ><?php echo $Description ?></textarea></td>
                <td><input type="file" name="Image" value="<?php echo $Image ?>" /></td>
                <td><?php echo ($isRedact)? '<button type="submit" name="redact" value="redact" >Редактировать</button>': '<button type="submit" name="add" value="add" >Добавить</button>'?></td>
            </tr>
        </form>
    </table>
</section>

