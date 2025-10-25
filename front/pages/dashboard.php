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
            <p>Вы вошли как: <strong><?= $role === 'admin' ? 'Администратор' : 'Клиент' ?></strong></p>
            <a href="cars.php" class="main-btn">Посмотреть автомобили</a>
        </section>

        <section class="info-cards">
            <div class="card">
                <h3>Ваши заказы</h3>
                <p>Здесь будут отображаться активные и завершённые заказы.</p>
                <a href="orders.php" class="link">Перейти</a>
            </div>

            <div class="card">
                <h3>Профиль</h3>
                <p>Редактируйте данные своей учетной записи.</p>
                <a href="profile.php" class="link">Перейти</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>© <?= date('Y') ?> RentCar. Все права защищены.</p>
    </footer>
</body>
</html>
