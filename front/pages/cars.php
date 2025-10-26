<?php
require_once __DIR__ . '/../../back/services/auth.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAccess();

$brand = $_GET['brand'] ?? '';
$type = $_GET['type'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$query = "
    SELECT 
        cars.*, 
        parking_zones.address AS parking_address,
        parking_zones.coordinate_x AS coordinate_x,
        parking_zones.coordinate_y AS coordinate_y
    FROM cars
    LEFT JOIN parking_zones ON cars.parking_id = parking_zones.id
    WHERE 1=1
";

if ($brand) $query .= " AND cars.brand LIKE '%" . mysqli_real_escape_string($db, $brand) . "%'";
if ($type) $query .= " AND cars.type LIKE '%" . mysqli_real_escape_string($db, $type) . "%'";
if ($minPrice) $query .= " AND cars.price_per_hour >= " . intval($minPrice);
if ($maxPrice) $query .= " AND cars.price_per_hour <= " . intval($maxPrice);

$result = mysqli_query($db, $query);
$cars = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cars[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Автомобили</title>
    <link rel="stylesheet" href="../assets/css/cars.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="logo">
            <h1>RentCarSys</h1>
        </div>
        <nav class="nav">
            <a href="dashboard.php">Главная</a>
            <a href="cars.php" class="active">Автомобили</a>
            <a href="profile.php">Профиль</a>
            <a href="../../back/services/logout.php" class="logout">Выйти</a>
        </nav>
    </header>

    <main class="content">
        <h2>Доступные автомобили</h2>

        <form method="GET" class="filter-form">
            <input type="text" name="brand" placeholder="Марка" value="<?= htmlspecialchars($brand) ?>">
            <input type="text" name="type" placeholder="Тип" value="<?= htmlspecialchars($type) ?>">
            <input type="number" name="min_price" placeholder="Мин. цена" value="<?= htmlspecialchars($minPrice) ?>">
            <input type="number" name="max_price" placeholder="Макс. цена" value="<?= htmlspecialchars($maxPrice) ?>">
            <button type="submit">Применить</button>
        </form>

        <div class="cars-container">
            <?php foreach ($cars as $car): ?>
                <div class="car-card" 
                     data-lat="<?= $car['coordinate_y'] ?>" 
                     data-lng="<?= $car['coordinate_x'] ?>"
                     data-name="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>"
                     data-parking="<?= htmlspecialchars($car['parking_address'] ?? 'Не указано') ?>">
                    <div class="car-header">
                        <h3><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h3>
                        <span class="price"><?= $car['price_per_hour'] ?> ₽/ч</span>
                    </div>
                    <p><strong>Тип:</strong> <?= htmlspecialchars($car['type']) ?></p>
                    <p><strong>Парковка:</strong> <?= htmlspecialchars($car['parking_address'] ?? 'Не указано') ?></p>
                    <p><strong>Статус:</strong> <?= htmlspecialchars($car['status']) ?></p>

                    <?php if ($car['status'] === 'available'): ?>
                        <a href="order_create.php?car_id=<?= $car['id'] ?>" class="rent-btn">Арендовать</a>
                    <?php else: ?>
                        <button class="disabled-btn" disabled>Недоступен</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Карта доступных автомобилей</h3>
        <div id="map"></div>
    </main>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="../assets/js/cars.js"></script>
</body>
</html>