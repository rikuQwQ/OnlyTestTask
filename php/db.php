<?php
$server = "localhost"; //название сервера
$userName = "root"; //имя пользователя
$password = "root"; //пароль
$db = "onlyTestTask"; //название базды данных

$mysqli = new mysqli($server, $userName, $password, $db); //создаем подлючение к БД
if(!$mysqli){
    echo "Ошибка подключения к базе данных";
}
?>