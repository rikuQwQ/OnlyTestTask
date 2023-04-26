<?php
session_start();
//проверяем авторизировался ли пользователь
if (!isset($_SESSION['name'])) //если нет, то перенеправлеям его на главную страницу
{
    header('Location: index.html');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/forms-style.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Редактирование пользователя</h2>
        <form action="" class="form" method="post">
            <?php 
                require('php/db.php'); //подключаемся к БД
                session_start();
                $user = $_SESSION['name']; //получаем логин текущего пользователя
                $sql = "SELECT * from user WHERE name = '$user'";
                $result = $mysqli -> query($sql); //выполняем запрос к БД

                if($result){
                    if(mysqli_num_rows($result) > 0){ //проверяем возвращает ли нам запрос какие-то данные
                        while($row = $result ->fetch_array()){  //в переменную $row мы получаем результат запроса
                            ?>
                            <input type="text" placeholder="Имя" name="name" value="<?php echo $row['name'] ?>">
                            <input type="text" placeholder="Имя" name="number" value="<?php echo $row['number'] ?>">
                            <input type="text" placeholder="Электронная почта" name="email" value="<?php echo $row['email'] ?>">
                            <input type="text" placeholder="Пароль" name="password" value="<?php echo $row['password'] ?>">
                            <?php
                        }
                    }
                }  
            ?>
            <button type="submit" name="submitBtn">Сохранить</button>
            <button type="submit" name="exitBtn">Выйти</button>
        </form>
    </div>
    
    <?php 
    if(isset($_POST['submitBtn'])) { //если пользователь нажимает на кнопку Сохранить
        require('php/db.php'); 
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $sql = "UPDATE user SET name = ?, number = ?, email = ?, password = ? WHERE name = '$user'"; //пишем запрос к БД на обновление
        $stmt = $mysqli->prepare($sql); //подготавливаем запрос чтобы подставить значения 
        $stmt -> bind_param("ssss", $name, $number, $email, $password); //подставляем значения
        $stmt -> execute();
        $_SESSION['name'] = $name; //в случае если пользователь сменил логин, присваеваем новый и сессии
        $stmt -> store_result();
        header("Refresh:0"); //перезагружаем страницу для отображения обновленной информации
    
        if(($stmt -> num_rows)>0){
            echo "Данные успешно обновлены";
            exit();
        }
        else{
            echo $mysqli->error;
        };

    }
    if(isset($_POST['exitBtn'])){ //если пользователь нажимает на кнопку Выйти
        session_unset(); //убираем все заданные параметры сессии
        header('Location: index.html'); 
    }
    ?>
</body>
</html>