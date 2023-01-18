<?php
$title = 'Animes';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
include 'config.php';

$users = $connection->prepare('SELECT * from users');
$users->execute();

if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isAdmin = isset($_POST['isAdmin']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();
    if ($query->rowCount() > 0) {
        echo '<p class="error">Этот адрес уже зарегистрирован!</  p>';
    }
    if ($query->rowCount() == 0) {
        $query = $connection->prepare("INSERT INTO users(username,password,email,isAdmin) VALUES (:username,:password_hash,:email,:isAdmin)");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $query->bindParam("isAdmin", $isAdmin, PDO::PARAM_BOOL);
        $result = $query->execute();
        if ($result) {
            echo '<p class="success">Регистрация прошла успешно!</p>';
            $users = $connection->prepare('SELECT * from users');
            $users->execute();
        } else {
            echo '<p class="error">Неверные данные!</p>';
        }
    }
}



if (isset($_POST['redact'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isAdmin = isset($_POST['isAdmin']);

    if ((strlen($password) < 6 &&  strlen($password == 0) )|| strlen($username) < 4 || strlen($email) < 6)
    {
        if(strlen($password) < 6 && strlen($password) != 0) echo 'Пароль слишком короткий';
        if(strlen($username) < 4) echo 'Логин слишком короткий';
        if(strlen($email) < 6) echo 'Email слишком короткий';
    }
    else
    {
        if(strlen($password) == 0){
            $query = $connection->prepare("UPDATE users SET username=:username, email=:email, isAdmin=:isAdmin WHERE id=:id");
        }
        else{
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $query = $connection->prepare("UPDATE users SET username=:username, email=:email, isAdmin=:isAdmin, password=:password_hash WHERE id=:id");
            $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
        }
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->bindParam("id", $id, PDO::PARAM_INT);
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $query->bindParam("isAdmin", $isAdmin, PDO::PARAM_BOOL);
        $query->execute();

        $users = $connection->prepare('SELECT * from users');
        $users->execute();
    }
}
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = $connection->prepare("DELETE FROM users WHERE id=:id");
    $query->bindParam("id", $id, PDO::PARAM_INT);
    $query->execute();

    $users = $connection->prepare('SELECT * from users');
    $users->execute();
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
            <label style="color: white">Username</label>
            <input type="text" name="username"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">New password</label>
            <input type="password" name="password"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">Email</label>
            <input type="email" name="email"/>
        </div>
        <div style="display: flex; flex-direction: column">
            <label style="color: white">Is Admin</label>
            <input type="checkbox" name="isAdmin"/>
        </div>
        <button type="submit" name="add" value="add" >Добавить</button>
    </form>
    <?php while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
        $isAdmin = $user['isAdmin'] ? 'checked' : '';
        echo '    
                <form method="post" action="" style="width: 30%; display: flex; align-items: center; justify-content: center; flex-direction: row">
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">id</label>
                        <label style="color: white">'.$user['id'].'</label>
                        <input type="hidden" name="id" value="'.$user['id'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">Username</label>
                        <input type="text" name="username" value="'.$user['username'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">New password</label>
                        <input type="password" name="password"/>
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">Email</label>
                        <input type="email" name="email" value="'.$user['email'].'" />
                    </div>
                    <div style="display: flex; flex-direction: column">
                        <label style="color: white">Is Admin</label>
                        <input type="checkbox" name="isAdmin" '.$isAdmin.' />
                    </div>
                    <button type="submit" name="redact" value="redact" >Редактировать</button>
                    <button type="submit" name="delete" value="delete">Удалить</button>
                </form>
               ';
    }?>
</section>
