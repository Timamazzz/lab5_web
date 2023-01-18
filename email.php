<?php
$title = 'email';
require 'src/components/header/header.php';
require 'src/components/footer/footer.php';
?>

<section class="section" style="padding-bottom: 5%">
    <?php
    session_status() === PHP_SESSION_ACTIVE || session_start();
    if(!isset($_SESSION['user_id'])){
        echo 'Авторизуйтесь для возможности отправки письма';
        exit;
    }
    ?>
    <div>
        <h1 style="color: white">Заполните</h1>
        <?php
        // определите переменные и задайте пустые значения
        $name = $email = $massage ="";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = test_input($_POST["name"]);
            $email = test_input($_POST["email"]);
            $massage = test_input($_POST["massage"]);
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            return htmlspecialchars($data);
        }
        ?>

        <?php

        if (isset($_POST['button'])){
            $err = array();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                //Логин
                if (empty($_POST["name"])) {
                    $err['name'] = "Имя обязательно";
                } else {
                    $name = test_input($_POST["name"]);
                    if (!preg_match("/^[a-яA-Я ]*$/",$name)) {
                        $err['name'] = "Разрешены только буквы и пробелы";
                    }
                }

                //Почта
                if (empty($_POST["email"])) {
                    $err['email'] = "Email обязательно";
                } else {
                    $email = test_input($_POST["email"]);

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $err['email'] = "Неверный формат электронной почты";
                    }
                }

                //Сообщение
                if (strlen($_POST["massage"]) <= 10) {
                    $err['massage'] = "Cообщение не менее 10 символов";
                } else {
                    $massage = test_input($_POST["massage"]);
                }

                if(!count($err)){
                    require 'sending.php';
                }
            }
        }
        ?>

        <form id="post" name="Form" method="POST" action="">

            <div>
                <label for="formName"></label><input id="formName" style="border-radius: 10px" type="text" name="name" value="<?=(isset($_POST['name']) ? $_POST['name'] : '')?>" placeholder="Ваше имя" <?php if (isset($err['name'])) echo 'style="background-color:#ff5959"'; ?>/>
                <span>
                    <?php
                    if (isset($err['name'])) echo '<p style="color:
                    red;">'.$err['name'].'</p>';
                    ?>
                </span>
            </div>

            <div>
                <label for="formEmail"></label><input id="formEmail" style="border-radius: 10px" name="email" value="<?=(isset($_POST['email']) ? $_POST['email'] : '')?>" placeholder="Ваш email" <?php if (isset($err['email'])) echo 'style="background-color:#ff5959"'; ?> />
                <span>
                    <?php
                    if (isset($err['email'])) echo '<p style="color:
                    red;">'.$err['email'].'</p>';
                    ?>
                </span>
            </div>

            <div>
                <label for="formText"></label><input id="formText" style="border-radius: 10px" type="text" name="massage" value="<?=(isset($_POST['massage']) ? $_POST['massage'] : '')?>" placeholder="Ваше сообщение" <?php if (isset($err['massage'])) echo 'style="background-color:#ff5959"'; ?>>
                <span>
                    <?php
                    if (isset($err['massage'])) echo '<p style="color:
                    red;">'.$err['massage'].'</p>';
                    ?>
                </span>
            </div>

            <div>
                <input action='sending.php' type="submit" class="" name="button" id="send" value="Отправить"/>
            </div>

        </form>
    </div>
</section>
