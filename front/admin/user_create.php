<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'client';

    if ($login && $password && $full_name && $email) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "
            INSERT INTO users (login, password, email, role, created_at, updated_at)
            VALUES (
                '" . mysqli_real_escape_string($db, $login) . "',
                '$hashedPassword',
                '" . mysqli_real_escape_string($db, $email) . "',
                '" . mysqli_real_escape_string($db, $role) . "',
                NOW(), NOW()
            )
        ";

        if (mysqli_query($db, $query)) {
            header("Location: users.php?created=1");
            exit;
        } else {
            $message = "Ошибка при добавлении пользователя.";
        }
    } else {
        $message = "Пожалуйста, заполните все поля.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить пользователя</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<header class="admin-header">
    <div class="logo">
        <img src="../assets/img/logo.png" alt="logo">
        <h1>Панель администратора</h1>
    </div>
    <nav class="nav">
        <a href="dashboard.php">Главная</a>
        <a href="cars.php">Автомобили</a>
        <a href="parking_zones.php">Парковки</a>
        <a href="users.php" class="active">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="admin-content">
    <h2>Добавить пользователя</h2>

    <?php if ($message): ?>
        <div class="message error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <label>Логин</label>
        <input type="text" name="login" required>

        <label>Пароль</label>
        <input type="password" name="password" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Роль</label>
        <select name="role" required>
            <option value="client">Пользователь</option>
            <option value="admin">Администратор</option>
        </select>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Добавить</button>
            <a href="users.php" class="btn-secondary">Отмена</a>
        </div>
    </form>
</main>
</body>
</html>
