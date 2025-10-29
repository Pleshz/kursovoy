<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

if (isset($_GET['action'], $_GET['id']) && is_numeric($_GET['id'])) {
    $orderId = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $query = "UPDATE orders SET status = 'approved', updated_at = NOW() WHERE id = $orderId AND status = 'pending'";
        mysqli_query($db, $query);
    } elseif ($action === 'reject') {
        $deleteQuery = "DELETE FROM orders WHERE id = $orderId";
        mysqli_query($db, $deleteQuery);
    }

    header("Location: orders.php");
    exit;
}

$query = "
    SELECT 
        orders.*,
        users.login AS user_login,
        cars.brand,
        cars.model,
        start_p.address AS start_address,
        end_p.address AS end_address
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    LEFT JOIN cars ON orders.car_id = cars.id
    LEFT JOIN parking_zones AS start_p ON orders.start_parking_id = start_p.id
    LEFT JOIN parking_zones AS end_p ON orders.end_parking_id = end_p.id
    ORDER BY orders.created_at DESC
";
$result = mysqli_query($db, $query);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление заказами</title>
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
        <a href="users.php">Пользователи</a>
        <a href="orders.php" class="active">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="content">
    <div class="page-header">
        <h2>Все заказы</h2>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Автомобиль</th>
                <th>Старт</th>
                <th>Финиш</th>
                <th>Стоимость</th>
                <th>Статус</th>
                <th>Создан</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($orders) === 0): ?>
            <tr><td colspan="9" style="text-align:center;">Заказы отсутствуют</td></tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['user_login'] ?? 'Неизвестно') ?></td>
                    <td><?= htmlspecialchars($order['brand'] . ' ' . $order['model']) ?></td>
                    <td><?= htmlspecialchars($order['start_address'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($order['end_address'] ?? '-') ?></td>
                    <td><?= $order['total_price'] ?> ₽</td>
                    <td>
                        <?php
                        $statusColors = [
                            'pending' => '#ffcc00',
                            'approved' => '#4caf50',
                        ];
                        $color = $statusColors[$order['status']] ?? '#ccc';
                        ?>
                        <span style="padding: 4px 8px; border-radius: 6px; background: <?= $color ?>; color: white;">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    </td>
                    <td><?= $order['created_at'] ?></td>
                    <td>
                        <?php if ($order['status'] === 'pending'): ?>
                            <a href="?action=approve&id=<?= $order['id'] ?>" class="btn-small">✅ Подтвердить</a>
                            <a href="?action=reject&id=<?= $order['id'] ?>" class="btn-small delete-btn" onclick="return confirm('Отклонить и удалить заказ?');">❌ Отклонить</a>
                        <?php else: ?>
                            <em>Подтверждён</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
