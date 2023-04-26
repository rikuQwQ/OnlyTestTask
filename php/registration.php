<?php

require('db.php');
$name = $_POST['name'];
$number = $_POST['number'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeatPassword = $_POST['repeatPassword'];

if(empty($name) || empty($number) || empty($email) || empty($password) || empty($repeatPassword)){ //проверяем пустые ли поля
    echo "Заполните все поля";
    exit();
}
else{
    if($password != $repeatPassword){ //проверяем совпадают ли пароли
        echo "Пароли не совпадают";
    }
    else{
        $sql = "SELECT name, number, email FROM user WHERE name=? OR number=? OR email=?"; //создаем запрос на выборку из БД, который возвратит нам строку если 
        $stmt = $mysqli->prepare($sql);                                                    //введенные пользователем данные уже есть в БД
        $stmt -> bind_param("sss", $name, $number, $email);
        $stmt -> execute();
        $stmt -> store_result();
        
        if(($stmt -> num_rows) > 0){
            echo "Что-то из следующих парамеров занято: имя, номер телефона или электронная почта";
            exit();
        }
        else{
            $sql = "INSERT INTO `user` (name, number, email, password) VALUES ('$name', '$number', '$email', '$password')"; //в случае успеха вставляем в БД данные нового пользователя
            $mysqli -> query($sql);
            header("Location: ../authorization.html"); //перенаправляем на страницу авторизации
        }
    }
}
$mysqli->close();

?>