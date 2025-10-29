<?php
require_once __DIR__ . '/../../back/services/auth.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAccess();

$login = $_SESSION['login'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="logo">
            <h1>RentCar</h1>
        </div>
        <nav class="nav">
            <a href="dashboard.php" class="active">Главная</a>
            <a href="cars.php">Автомобили</a>
            <a href="profile.php">Профиль</a>
            <a href="../../back/services/logout.php" class="logout">Выйти</a>
        </nav>
    </header>

    <main class="content">
        <section class="welcome">
            <h2>Здравствуйте, <?= htmlspecialchars($login) ?></h2>
            <p>Добро пожаловать в систему каршеринга RentCar.</p>
            <a href="cars.php" class="main-btn">Посмотреть автомобили</a>
        </section>
    </main>
</body>
</html>
