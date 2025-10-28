<?php
require_once __DIR__ . '/../../back/services/auth.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAccess();

if (!isset($_GET['car_id'])) {
    header("Location: cars.php");
    exit;
}

$car_id = intval($_GET['car_id']);

$query = "
    SELECT 
        cars.id, cars.brand, cars.model, cars.price_per_hour, cars.status,
        parking_zones.id AS parking_id, parking_zones.address AS parking_address
    FROM cars
    LEFT JOIN parking_zones ON cars.parking_id = parking_zones.id
    WHERE cars.id = ?
";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $car_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$car = mysqli_fetch_assoc($result);

if (!$car) {
    die("Ошибка: автомобиль не найден.");
}

$zones = mysqli_query($db, "SELECT id, address FROM parking_zones");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $car_id = intval($_POST['car_id']);
    $start_zone_id = intval($_POST['start_zone_id']);
    $end_zone_id = intval($_POST['end_zone_id']);
    $hours = floatval($_POST['hours']);
    $total_price = $hours * $car['price_per_hour'];

    $insert = "
        INSERT INTO orders (user_id, car_id, start_zone_id, end_zone_id, start_time, status, total_price)
        VALUES (?, ?, ?, ?, NOW(), 'active', ?)
    ";
    $stmt = mysqli_prepare($db, $insert);
    mysqli_stmt_bind_param($stmt, "iiidd", $user_id, $car_id, $start_zone_id, $end_zone_id, $total_price);
    mysqli_stmt_execute($stmt);

    mysqli_query($db, "UPDATE cars SET status = 'unavailable' WHERE id = $car_id");

    header("Location: orders.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление аренды</title>
    <link rel="stylesheet" href="../assets/css/order_create.css">
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
        <a href="orders.php">Заказы</a>
        <a href="profile.php">Профиль</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="content">
    <h2>Оформление аренды</h2>

    <div class="order-summary">
        <h3><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h3>
        <p><strong>Цена за час:</strong> <?= $car['price_per_hour'] ?> ₽</p>
        <p><strong>Текущая парковка:</strong> <?= htmlspecialchars($car['parking_address'] ?? 'Не указана') ?></p>
    </div>

    <form method="POST" class="order-form">
        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
        <input type="hidden" name="start_zone_id" value="<?= $car['parking_id'] ?>">

        <label for="end_zone_id">Выберите парковку для возврата:</label>
        <select name="end_zone_id" id="end_zone_id" required>
            <option value="">— Выберите парковку —</option>
            <?php while ($zone = mysqli_fetch_assoc($zones)): ?>
                <option value="<?= $zone['id'] ?>"><?= htmlspecialchars($zone['address']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="hours">Количество часов аренды:</label>
        <input type="number" step="0.5" min="1" name="hours" id="hours" required>

        <button type="submit">Подтвердить аренду</button>
        <a href="cars.php" class="cancel-btn">Отмена</a>
    </form>
</main>
</body>
</html>
