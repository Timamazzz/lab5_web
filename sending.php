<?php

    include_once('config.php');

    /* Здесь проверяется существование переменных */
    if (isset($_POST['name'])) {$name = $_POST['name'];}
    if (isset($_POST['email'])) {$email = $_POST['email'];}
    if (isset($_POST['massage'])) {$massage = $_POST['massage'];}

    $to = "timforworking@mail.ru";
    $from = "=?utf-8?B?".base64_encode("timforworking@mail.ru")."?=";
    $subject = "Темя сообщения";
    $mas = "
        <div style='
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 15px;
        align-content: space-between;'>
            <p>Имя заказчика: $name</p>
            <p>Email заказчика: $email</p>
            <p>Сообщение заказа: $massage</p>
        </div>
    ";

    $subject = "=?utf-8?B?".base64_encode($subject)."?=";
    $headers = "From: $from\r\nReply-to: $from\r\nContent-type:text/html; charset=urf-8\r\n";

    if(mail($to, $subject, $mas, $headers)){
        echo "<h3 style='color: white'>Сообщение отправлено</h3>";
        $query = $connection->prepare("INSERT INTO mails(name, email, message) Values(:name, :email, :message)");
        $query->bindParam("name", $name, PDO::PARAM_STR);
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $query->bindParam("message", $massage, PDO::PARAM_STR);

        $query->execute();
 	}
 	else {
        echo "<h3 style='color: white'>При отправке сообщения возникла ошибка</h3sty>";
    }


?>
