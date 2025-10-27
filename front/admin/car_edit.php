<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: cars.php");
    exit;
}

$carId = intval($_GET['id']);
$message = '';

$carQuery = "SELECT * FROM cars WHERE id = $carId";
$carResult = mysqli_query($db, $carQuery);
$car = mysqli_fetch_assoc($carResult);

if (!$car) {
    header("Location: cars.php");
    exit;
}

$zonesQuery = "SELECT id, address FROM parking_zones ORDER BY address ASC";
$zonesResult = mysqli_query($db, $zonesQuery);
$zones = mysqli_fetch_all($zonesResult, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $country = trim($_POST['country']);
    $price = floatval($_POST['price_per_hour']);
    $type = trim($_POST['type']);
    $status = $_POST['status'] ?? 'available';
    $parking_id = intval($_POST['parking_id']);

    if ($brand && $model && $price > 0 && $parking_id > 0) {
        $stmt = mysqli_prepare($db, "
            UPDATE cars 
            SET brand = ?, model = ?, country = ?, price_per_hour = ?, type = ?, status = ?, parking_id = ?, updated_at = NOW()
            WHERE id = ?
        ");
        mysqli_stmt_bind_param($stmt, 'sssds sii', $brand, $model, $country, $price, $type, $status, $parking_id, $carId);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: cars.php?updated=1");
            exit;
        } else {
            $message = "Ошибка при обновлении автомобиля: " . mysqli_error($db);
        }
    } else {
        $message = "Пожалуйста, заполните все обязательные поля.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать автомобиль</title>
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
        <a href="cars.php" class="active">Машины</a>
        <a href="parking_zones.php">Парковки</a>
        <a href="users.php">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выйти</a>
    </nav>
</header>

<main class="admin-content">
    <h2>Редактировать автомобиль</h2>

    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <label>Марка *</label>
        <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>

        <label>Модель *</label>
        <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>

        <label>Страна</label>
        <select name="country">]
            <option value="russia" <?= $car['country'] === 'russia' ? 'selected' : '' ?>>Россия</option>
            <option value="germany" <?= $car['country'] === 'germany' ? 'selected' : '' ?>>Германия</option>
            <option value="france" <?= $car['country'] === 'france' ? 'selected' : '' ?>>Франция</option>
            <option value="japan" <?= $car['country'] === 'japan' ? 'selected' : '' ?>>Япония</option>
            <option value="korea" <?= $car['country'] === 'korea' ? 'selected' : '' ?>>Корея</option>
            <option value="china" <?= $car['country'] === 'china' ? 'selected' : '' ?>>Китай</option>
        </select>

        <label>Тип</label>
        <select name="type">]
            <option value="sedan" <?= $car['type'] === 'sedan' ? 'selected' : '' ?>>Седан</option>
            <option value="crossover" <?= $car['type'] === 'crossover' ? 'selected' : '' ?>>Кроссовер</option>
            <option value="hatchback" <?= $car['type'] === 'hatchback' ? 'selected' : '' ?>>Хэтчбек</option>
        </select>

        <label>Цена за час (₽) *</label>
        <input type="number" name="price_per_hour" min="1" step="0.01" value="<?= htmlspecialchars($car['price_per_hour']) ?>" required>

        <label>Статус</label>
        <select name="status">
            <option value="available" <?= $car['status'] === 'available' ? 'selected' : '' ?>>Доступен</option>
            <option value="busy" <?= $car['status'] === 'busy' ? 'selected' : '' ?>>Занят</option>
        </select>

        <label>Парковка *</label>
        <select name="parking_id" required>
            <option value="">— Выберите парковку —</option>
            <?php foreach ($zones as $zone): ?>
                <option value="<?= $zone['id'] ?>" <?= $zone['id'] == $car['parking_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($zone['address']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="form-buttons">
            <button type="submit" class="btn-primary">Сохранить</button>
            <a href="cars.php" class="btn-secondary">Отмена</a>
        </div>
    </form>
</main>
</body>
</html>
