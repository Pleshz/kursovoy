<?php
require_once __DIR__ . '/../../back/services/UserService.php';
require_once __DIR__ . '/../../back/services/AuthService.php';

$userService = new UserService();
$auth = new AuthService();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $message = "Пароли не совпадают.";
    } elseif ($userService->userExists($login, $email)) {
        $message = "Такой логин или email уже зарегистрирован.";
    } else {
        if ($userService->registerUser($login, $email, $password)) {
            $auth->login($login, $password);
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Ошибка при регистрации. Попробуйте позже.";
        }
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
    <div class="auth-card">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="logo">
        </div>
        <h2>Регистрация</h2>
        <p class="subtitle">Создайте аккаунт и начните аренду авто</p>

        <?php if (isset($message) && $message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Логин</label>
            <input type="text" name="login" placeholder="Ваш логин" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="example@mail.com" required>

            <label>Пароль</label>
            <input type="password" name="password" placeholder="Придумайте пароль" required>

            <label>Подтвердите пароль</label>
            <input type="password" name="confirm" placeholder="Повторите пароль" required>

            <button type="submit">Создать аккаунт</button>
        </form>

        <div class="bottom-text">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </div>
    </div>
</body>
</html>
