<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$query = "SELECT * FROM parking_zones ORDER BY id DESC";
$result = mysqli_query($db, $query);
$parkingZones = [];
while ($row = mysqli_fetch_assoc($result)) {
    $parkingZones[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление парковками</title>
    <link rel="stylesheet" href="assets/admin.css">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
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
        <a href="parking_zones.php" class="active">Парковки</a>
        <a href="users.php">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="content">
    <div class="page-header">
        <h2>Парковочные зоны</h2>
        <a href="parking_create.php" class="btn-primary">Добавить парковку</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Адрес</th>
                <th>Координаты</th>
                <th>Всего мест</th>
                <th>Создано</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($parkingZones as $zone): ?>
            <tr>
                <td><?= $zone['id'] ?></td>
                <td><?= htmlspecialchars($zone['address']) ?></td>
                <td><?= $zone['coordinate_x'] ?>, <?= $zone['coordinate_y'] ?></td>
                <td><?= $zone['total_spaces'] ?></td>
                <td><?= $zone['created_at'] ?></td>
                <td class="actions">
                    <a href="parking_edit.php?id=<?= $zone['id'] ?>" class="btn-edit">✏️</a>
                    <a href="../../back/services/parking_delete.php?id=<?= $zone['id'] ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Удалить парковку?');">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
