<?php
require_once "../../back/config/Connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'client';

    $query = "INSERT INTO users (login, email, password, role) VALUES ('$login', '$email', '$password', '$role')";
    $result = mysqli_query($db, $query);

    if ($result) {
        header("Location: login.php");
        exit;
    } else {
        echo "Ошибка регистрации: " . mysqli_error($db);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Регистрация</h2>
    <form method="POST">
        <label>Логин:</label><br>
        <input type="text" name="login" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Пароль:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>