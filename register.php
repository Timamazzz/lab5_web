<?php

$title = 'register';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';

session_status() === PHP_SESSION_ACTIVE ? null : session_start();
include('config.php');
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isAdmin = false;
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
        } else {
            echo '<p class="error">Неверные данные!</p>';
        }
    }
}
?>

<section class="section" style="padding-bottom: 50px">
    <form method="post" action="" name="signup-form" style="flex-direction: column; display: flex; justify-content: center; align-items: center">
        <div class="form-element" style="display: flex; flex-direction: column; justify-content: center;align-items: center">
            <label>Username</label>
            <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
        </div>
        <div class="form-element" style="display: flex; flex-direction: column; justify-content: center;align-items: center">
            <label>Email</label>
            <input type="email" name="email" required />
        </div>
        <div class="form-element" style="display: flex; flex-direction: column; justify-content: center;align-items: center">
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <button type="submit" name="register" value="register">Зарегестрироваться</button>
        <nav class="container nav" style="margin-top: 20px">
            <a href="login.php">Авторизация</a>
        </nav>
    </form>

</section>




