<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$query = "
    SELECT 
        cars.id,
        cars.brand,
        cars.model,
        cars.country,
        cars.price_per_hour,
        cars.type,
        cars.status,
        parking_zones.address AS parking_address
    FROM cars
    LEFT JOIN parking_zones ON cars.parking_id = parking_zones.id
    ORDER BY cars.id DESC
";
$result = mysqli_query($db, $query);
$cars = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление автомобилями</title>
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
        <a href="cars.php" class="active">Автомобили</a>
        <a href="parking_zones.php">Парковки</a>
        <a href="users.php">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="content">
    <div class="page-header">
        <h2>Автомобили</h2>
        <a href="car_create.php" class="btn-primary">Добавить автомобиль</a>
    </div>

    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Марка</th>
            <th>Модель</th>
            <th>Страна</th>
            <th>Цена (₽/ч)</th>
            <th>Тип</th>
            <th>Статус</th>
            <th>Парковка</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($cars) > 0): ?>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= $car['id'] ?></td>
                    <td><?= htmlspecialchars($car['brand']) ?></td>
                    <td><?= htmlspecialchars($car['model']) ?></td>
                    <td><?= htmlspecialchars($car['country']) ?></td>
                    <td><?= number_format($car['price_per_hour'], 2) ?></td>
                    <td><?= htmlspecialchars($car['type']) ?></td>
                    <td>
                        <span class="status <?= $car['status'] === 'available' ? 'available' : 'busy' ?>">
                            <?= htmlspecialchars($car['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($car['parking_address'] ?? '—') ?></td>
                    <td class="actions">
                        <a href="car_edit.php?id=<?= $car['id'] ?>" class="btn-edit">✏️</a>
                        <a href="../../back/services/car_delete.php?id=<?= $car['id'] ?>" 
                           onclick="return confirm('Удалить автомобиль?')" 
                           class="btn-delete">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="9" style="text-align:center;">Нет автомобилей в системе</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
