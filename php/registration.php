<?php

require('db.php');
$name = $_POST['name'];
$number = $_POST['number'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeatPassword = $_POST['repeatPassword'];

if(empty($name) || empty($number) || empty($email) || empty($password) || empty($repeatPassword)){
    echo "Заполните все поля";
    exit();
}
else{
    if($password != $repeatPassword){
        echo "Пароли не совпадают";
    }
    else{
        $sql = "SELECT name, number, email FROM user WHERE name=? OR number=? OR email=?";
        $stmt = $mysqli->prepare($sql);
        $stmt -> bind_param("sss", $name, $number, $email);
        $stmt -> execute();
        $stmt -> store_result();
        
        if(($stmt -> num_rows) > 0){
            echo "Что-то из следующих парамеров занято: имя, номер телефона или электронная почта";
            exit();
        }
        else{
            $sql = "INSERT INTO `user` (name, number, email, password) VALUES ('$name', '$number', '$email', '$password')";
            $mysqli -> query($sql);
            header("Location: ../authorization.html");
        }
    }
}
$mysqli->close();

?>