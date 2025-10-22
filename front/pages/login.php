<?php
require_once __DIR__ . '/../../back/services/AuthService.php';

$auth = new AuthService();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if ($auth->login($login, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Неверный логин или пароль.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация — RentCarSys</title>
</head>
<body>
    <h2>Авторизация</h2>
    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Логин:</label><br>
        <input type="text" name="login" required><br><br>

        <label>Пароль:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Войти</button>
    </form>

    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</body>
</html>