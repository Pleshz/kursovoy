<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$userId = intval($_GET['id']);
$message = '';

$query = "SELECT * FROM users WHERE id = $userId";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: users.php?error=not_found");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'client';
    $password = trim($_POST['password']);

    if ($login && $email) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = "
                UPDATE users 
                SET 
                    login = '" . mysqli_real_escape_string($db, $login) . "',
                    email = '" . mysqli_real_escape_string($db, $email) . "',
                    role = '" . mysqli_real_escape_string($db, $role) . "',
                    password = '$hashedPassword',
                    updated_at = NOW()
                WHERE id = $userId
            ";
        } else {
            $updateQuery = "
                UPDATE users 
                SET 
                    login = '" . mysqli_real_escape_string($db, $login) . "',
                    email = '" . mysqli_real_escape_string($db, $email) . "',
                    role = '" . mysqli_real_escape_string($db, $role) . "',
                    updated_at = NOW()
                WHERE id = $userId
            ";
        }

        if (mysqli_query($db, $updateQuery)) {
            header("Location: users.php?updated=1");
            exit;
        } else {
            $message = "Ошибка при обновлении пользователя.";
        }
    } else {
        $message = "Пожалуйста, заполните все обязательные поля.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать пользователя</title>
    <link rel="stylesheet" href="assets/admin.css">
    <style>
        .admin-content {padding-left: 40px}
    </style>
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
    <h2>Редактировать пользователя</h2>

    <form method="POST" class="admin-form">
        <label>Логин</label>
        <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Новый пароль (если хотите изменить)</label>
        <input type="password" name="password" placeholder="Оставьте пустым, чтобы не менять">

        <label>Роль</label>
        <select name="role" required>
            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Пользователь</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
        </select>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Сохранить изменения</button>
            <a href="users.php" class="btn-secondary">Отмена</a>
        </div>
    </form>
</main>
</body>
</html>
