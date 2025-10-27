<?php
require_once __DIR__ . '/../../back/services/auth.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAccess();

$userId = $_SESSION['user_id'];

$userQuery = mysqli_query($db, "SELECT * FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($userQuery);

$ordersQuery = mysqli_query($db, "
    SELECT o.*, c.brand, c.model, p1.address AS start_parking, p2.address AS end_parking
    FROM orders o
    JOIN cars c ON o.car_id = c.id
    LEFT JOIN parking_zones p1 ON o.start_parking_id = p1.id
    LEFT JOIN parking_zones p2 ON o.end_parking_id = p2.id
    WHERE o.user_id = $userId
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
<header class="header">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="logo">
            <h1>RentCar</h1>
        </div>
        <nav class="nav">
            <a href="dashboard.php">Главная</a>
            <a href="cars.php">Автомобили</a>
            <a href="profile.php" class="active">Профиль</a>
            <a href="../../back/services/logout.php" class="logout">Выйти</a>
        </nav>
    </header>

<main class="content">
    <section class="profile-section">
        <h2>Мои данные</h2>
        <form action="../../back/services/update_profile.php" method="POST" class="profile-form">
            <label>Имя</label>
            <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Пароль (оставьте пустым, если не хотите менять)</label>
            <input type="password" name="password" placeholder="Новый пароль">

            <button type="submit" class="save-btn">Сохранить изменения</button>
        </form>
    </section>

    <section class="orders-section">
        <h2>Мои заказы</h2>
        <div class="orders-container">
            <?php while ($order = mysqli_fetch_assoc($ordersQuery)): ?>
                <div class="order-card <?= htmlspecialchars($order['status']) ?>">
                    <h3><?= htmlspecialchars($order['brand'] . ' ' . $order['model']) ?></h3>
                    <p><strong>Статус:</strong> <?= htmlspecialchars($order['status']) ?></p>
                    <p><strong>Начальная парковка:</strong> <?= htmlspecialchars($order['start_parking'] ?? '-') ?></p>
                    <p><strong>Конечная парковка:</strong> <?= htmlspecialchars($order['end_parking'] ?? '-') ?></p>
                    <p><strong>Начало:</strong> <?= htmlspecialchars($order['start_time']) ?></p>
                    <p><strong>Окончание:</strong> <?= htmlspecialchars($order['end_time'] ?? '-') ?></p>
                    <p><strong>Стоимость:</strong> <?= htmlspecialchars($order['total_price']) ?> ₽</p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>
</body>
</html>
