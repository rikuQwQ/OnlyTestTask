<?php
session_start();
require('db.php');
$password = $_POST['password'];
$login = $_POST['login'];

if(empty($login) || empty($password)){ //проверяем пустые ли поля
    echo "Заполните все поля";
    exit();
}

$sql = "SELECT * FROM user WHERE email=? OR number=?"; //создаем запрос на выборку из БД, который вернет нам строку если введенные пользователем телефон или email совпадают с имеющимися в БД
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param("ss", $login, $login);

if(!$stmt){ //выдаем ошибку если что-то не так пошло с запросом
    echo "Ошибка запроса";
    exit();
}

$stmt -> execute();//выполняем запрос
$result = $stmt -> get_result();//получаем результат запроса в переменную

if(!$result){ //выдаем ошибку если что-то не так пошло с запросом
    echo "Ошибка запроса"; 
    exit();  
}
else{
    $row = $result->fetch_array(); //получаем строку из результата
    if($row['password'] == $password){ //проверяем совпадает ли введенный пользовтелем пароль с тем, что мы получили из БД
        $_SESSION['name'] = $row['name']; //чтобы обозначить что пользователь авторизирован, передаем параметру 'name' у $_POST логин пользователя, что мы получили из БД
        if(isset($_POST['g-recaptcha-response'])){ //проверяем есть ли на стороне клиента капча

            $secretKey = "6LcjR7wlAAAAAGBULcARZ2MQ5wjde59J9JQwk0af"; 
            $ip = $_SERVER['REMOTE_ADDR']; //получаем ip пользователя
            $response = $_POST['g-recaptcha-response']; 
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$ip"; //проверяем наличие ключа g-recaptcha-response в массиве POST;
            $file = file_get_contents($url); //получаем файл в строку
            $data = json_decode($file); //json файл из файла в строку

            if($data->success==true){ //проверяем прошел ли пользовтель капчу
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