<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$carsCount = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM cars"))['cnt'];
$usersCount = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM users"))['cnt'];
$ordersCount = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM orders"))['cnt'];
$parkingCount = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM parking_zones"))['cnt'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<header class="admin-header">
    <div class="logo">
        <img src="../assets/img/logo.png" alt="logo">
        <h1>RentCar</h1>
    </div>
    <nav class="nav">
        <a href="dashboard.php" class="active">Главная</a>
        <a href="cars.php">Автомобили</a>
        <a href="parking.php">Парковки</a>
        <a href="users.php">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="content">
    <h2>Панель администратора</h2>
    <div class="stats-container">
        <div class="stat-card">
            <h3>Автомобили</h3>
            <p><?= $carsCount ?></p>
        </div>
        <div class="stat-card">
            <h3>Пользователи</h3>
            <p><?= $usersCount ?></p>
        </div>
        <div class="stat-card">
            <h3>Заказы</h3>
            <p><?= $ordersCount ?></p>
        </div>
        <div class="stat-card">
            <h3>Парковки</h3>
            <p><?= $parkingCount ?></p>
        </div>
    </div>
</main>
</body>
</html>