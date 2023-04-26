<?php
session_start();
require('db.php');
$password = $_POST['password'];
$login = $_POST['login'];

if(empty($login) || empty($password)){
    echo "Заполните все поля";
    exit();
}

$sql = "SELECT * FROM user WHERE email=? OR number=?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param("ss", $login, $login);

if(!$stmt){
    echo "Ошибка запроса";
    exit();
}

$stmt -> execute();
$result = $stmt -> get_result();

if(!$result){
    echo "Ошибка запроса"; 
    exit();  
}
else{
    $row = $result->fetch_array();
    if($row['password'] == $password){
        $_SESSION['name'] = $row['name'];
        if(isset($_POST['g-recaptcha-response'])){

            $secretKey = "6LcjR7wlAAAAAGBULcARZ2MQ5wjde59J9JQwk0af";
            $ip = $_SERVER['REMOTE_ADDR'];
            $response = $_POST['g-recaptcha-response'];
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$ip";
            $fire = file_get_contents($url);
            $data = json_decode($fire);

            if($data->success==true){
                header("Location: ../profile.php");
            }
            else{
                echo "Введите капчу";
            }
        }
        else{
            echo "Ошибка капчи";
            exit();
        }
    }
    else if(!$row){
        echo "Нет такого логина";
    }
    else{
        echo "Неверный пароль";
    }
}

$mysqli->close();

?>