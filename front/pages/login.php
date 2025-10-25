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
    <title>Авторизация</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-card">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="logo">
        </div>
        <h2>Вход в систему</h2>
        <p class="subtitle">Добро пожаловать в лучшую систему каршеринга</p>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Логин</label>
            <input type="text" name="login" placeholder="Введите логин" required>

            <label>Пароль</label>
            <input type="password" name="password" placeholder="Введите пароль" required>

            <button type="submit">Войти</button>
        </form>

        <div class="bottom-text">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>
