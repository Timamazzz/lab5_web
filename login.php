<?php

$title = 'Auth';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
$error = null;
    include('config.php');
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = $connection->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            $error = 'Неверные пароль или имя пользователя';
        } else {
            if (password_verify($password, $result['password'])) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_isAdmin'] = $result['isAdmin'];
                header('Location: http://localhost/aniuwu/index.php ');
            } else {
                $error = 'Неверные пароль или имя пользователя';
            }
        }
    }
?>

<section class="section" style="
    padding-bottom: 50px;
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
    align-content: center;">
    <p style="color: red"><?php echo $error ?></p>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center">
        <form method="post" action="" style="width: 20%; display: flex; align-items: center; justify-content: space-between; flex-direction: column">
            <div>
                <label style="color: white">Username</label>
                <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
            </div>
            <div>
                <label style="color: white">Password</label>
                <input type="password" name="password" required />
            </div>
            <button type="submit" name="login" value="login">Войти</button>
        </form>
        <nav class="container nav" style="margin-top: 20px">
            <a href="register.php">Регистрация</a>
        </nav>
    </div>
</section>
