<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($db, $query);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление пользователями</title>
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
    <h2>Пользователи</h2>

    <div class="actions">
        <a href="user_create.php" class="btn-primary">Добавить пользователя</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['login']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <a href="user_edit.php?id=<?= $user['id'] ?>" class="btn-small">✏️</a>
                    <a href="../../back/services/user_delete.php?id=<?= $user['id'] ?>" 
                       class="btn-small delete-btn"
                       onclick="return confirm('Удалить пользователя <?= htmlspecialchars($user['login']) ?>?');">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
