<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$zonesQuery = "SELECT id, address FROM parking_zones ORDER BY address ASC";
$zonesResult = mysqli_query($db, $zonesQuery);
$zones = mysqli_fetch_all($zonesResult, MYSQLI_ASSOC);

$message = '';
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
            INSERT INTO cars (brand, model, country, price_per_hour, type, status, parking_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        mysqli_stmt_bind_param($stmt, 'sssdssi', $brand, $model, $country, $price, $type, $status, $parking_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: cars.php?success=1");
            exit;
        } else {
            $message = "Ошибка при добавлении автомобиля: " . mysqli_error($db);
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
    <title>Добавить автомобиль</title>
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
    <h2>Добавить автомобиль</h2>

    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <label>Марка *</label>
        <input type="text" name="brand" required>

        <label>Модель *</label>
        <input type="text" name="model" required>

        <label>Страна</label>
        <select name="country">]
            <option value="russia">Россия</option>
            <option value="germany">Германия</option>
            <option value="france">Франция</option>
            <option value="japan">Япония</option>
            <option value="korea">Корея</option>
            <option value="china">Китай</option>
        </select>

        <label>Тип</label>
        <select name="type">]
            <option value="sedan">Седан</option>
            <option value="crossover">Кроссовер</option>
            <option value="hatchback">Хэтчбек</option>
        </select>

        <label>Цена за час (₽) *</label>
        <input type="number" name="price_per_hour" min="1" step="100" required>

        <label>Статус</label>
        <select name="status">
            <option value="available">Доступен</option>
            <option value="busy">Занят</option>
        </select>

        <label>Парковка *</label>
        <select name="parking_id" required>
            <option value="">— Выберите парковку —</option>
            <?php foreach ($zones as $zone): ?>
                <option value="<?= $zone['id'] ?>">
                    <?= htmlspecialchars($zone['address']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="form-buttons">
            <button type="submit" class="btn-primary">Добавить</button>
            <a href="cars.php" class="btn-secondary">Отмена</a>
        </div>
    </form>
</main>
</body>
</html>
