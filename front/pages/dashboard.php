<?php
require_once __DIR__ . '/../../back/services/AuthService.php';
$auth = new AuthService();

if (!$auth->isAuthenticated()) {
    header("Location: login.php");
    exit;
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель управления</title>
</head>
<body>
    <h2>Добро пожаловать, <?= htmlspecialchars($user['login']) ?>!</h2>
    <p>Ваша роль: <strong><?= htmlspecialchars($user['role']) ?></strong></p>

    <p><a href="logout.php">Выйти</a></p>
</body>
</html>
