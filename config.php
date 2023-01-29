<?php
try {
    $connection = new PDO('mysql:host=localhost;dbname=weblab', 'root', '310913_zZz');
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
require_once ('Flash.php');
