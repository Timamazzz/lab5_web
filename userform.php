<?php
$id = isset($_GET['id']) ? $_GET['id'] : null;
$username = null;
$email = null;
$isAdmin = null;
$isRedact = ($id != null);

include_once 'config.php';

if(!$isRedact)
    $title = 'Add user';
else {
    $title = 'Redact user';
    $query = $connection->prepare("SELECT * FROM users WHERE id=:id");
    $query->bindParam("id", $id, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $username = $user['username'];
    $email = $user['email'];
    $isAdmin = $user['isAdmin'];
}
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';

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
            flash('user_add', 'Регистрация прошла успешно!', FLASH_SUCCESS);
            header('Location: http://localhost/aniuwu/users.php ');
        } else {
            echo '<p class="error">Неверные данные!</p>';
        }
    }
}
if (isset($_POST['redact'])) {
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

        flash('user_redact', 'Пользователь успешно отредактирован', FLASH_SUCCESS);
        header('Location: http://localhost/aniuwu/users.php ');
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
            <th style="color: white">username</th>
            <th style="color: white"><?php echo ($isRedact)? 'new password' : 'password'?></th>
            <th style="color: white">email</th>
            <th style="color: white">is admin</th>
        </tr>
        <form method="post"">
            <tr>
                <?php if($isRedact) echo '<td><label style="color: white">'.$id.'</label></td>'; ?>
                <td><input type="text" name="username" value="<?php echo $username ?>" /></td>
                <td><input type="password" name="password"/></td>
                <td><input type="email" name="email" value="<?php echo $email ?>" /></td>
                <td><input type="checkbox" name="isAdmin" <?php echo $isAdmin? 'checked' : '' ?>  /></td>
                <td><?php echo ($isRedact)? '<button type="submit" name="redact" value="redact" >Редактировать</button>': '<button type="submit" name="add" value="add" >Добавить</button>'?></td>
            </tr>
        </form>
    </table>
</section>

