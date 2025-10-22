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
    <title>Регистрация — RentCarSys</title>
</head>
<body>
    <h2>Регистрация</h2>

    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Логин:</label><br>
        <input type="text" name="login" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Пароль:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Подтвердите пароль:</label><br>
        <input type="password" name="confirm" required><br><br>

        <button type="submit">Зарегистрироваться</button>
    </form>

    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</body>
</html>
