<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $coordinate_x = trim($_POST['coordinate_x'] ?? '');
    $coordinate_y = trim($_POST['coordinate_y'] ?? '');
    $total_spaces = trim($_POST['total_spaces'] ?? '');

    if ($address === '') {
        $errors[] = 'Адрес обязателен.';
    }

    if ($coordinate_x === '' || !is_numeric($coordinate_x)) {
        $errors[] = 'Координата X (долгота) должна быть числом.';
    }

    if ($coordinate_y === '' || !is_numeric($coordinate_y)) {
        $errors[] = 'Координата Y (широта) должна быть числом.';
    }

    if ($total_spaces === '' || !ctype_digit($total_spaces) || intval($total_spaces) <= 0) {
        $errors[] = 'Количество мест должно быть положительным целым числом.';
    }

    if (empty($errors)) {
        $coordinate_x = floatval($coordinate_x);
        $coordinate_y = floatval($coordinate_y);
        $total_spaces = intval($total_spaces);

        $stmt = $db->prepare("
            INSERT INTO parking_zones (address, coordinate_x, coordinate_y, total_spaces, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        if ($stmt === false) {
            $message = 'Ошибка подготовки запроса: ' . htmlspecialchars($db->error);
        } else {
            $stmt->bind_param('sddi', $address, $coordinate_x, $coordinate_y, $total_spaces);
            if ($stmt->execute()) {
                header("Location: parking_zones.php?created=1");
                exit;
            } else {
                $message = 'Ошибка при добавлении парковки: ' . htmlspecialchars($stmt->error);
            }
        }
    } else {
        $message = implode('<br>', array_map('htmlspecialchars', $errors));
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить парковку</title>
    <link rel="stylesheet" href="assets/admin.css">
    <style>
        /* Небольшие дополнения к admin.css для формы */
        .admin-form { max-width: 640px; margin-top: 20px; }
        .admin-form label { display:block; margin-top:10px; font-weight:600; }
        .admin-form input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; }
        .admin-form .row { display:flex; gap:12px; }
        .admin-form .row .col { flex:1; }
        .message { background:#fff4e6; border:1px solid #ffd1a8; padding:10px; border-radius:8px; color:#7a4b00; margin-bottom:12px; }
        .actions { margin-top:14px; display:flex; gap:10px; align-items:center; }
        .btn-primary { background:#2563eb; color:#fff; padding:10px 14px; border-radius:6px; text-decoration:none; border:none; cursor:pointer; }
        .btn-secondary { background:#e5e7eb; padding:10px 14px; border-radius:6px; text-decoration:none; color:#111; }
    </style>
</head>
<body>
<header class="admin-header">
    <div class="logo">
        <img src="../assets/img/logo.png" alt="logo" style="height:36px;">
        <h1>Панель администратора</h1>
    </div>
    <nav class="nav">
        <a href="dashboard.php">Дашборд</a>
        <a href="cars.php">Автомобили</a>
        <a href="parking_zones.php" class="active">Парковки</a>
        <a href="users.php">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выход</a>
    </nav>
</header>

<main class="admin-content">
    <h2>Добавить парковку</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form" novalidate>
        <label for="address">Адрес</label>
        <input id="address" name="address" type="text" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" placeholder="Например: Москва, ул. Пушкина, 10" required>

        <div class="row">
            <div class="col">
                <label for="coordinate_y">Координата Y (широта)</label>
                <input id="coordinate_y" name="coordinate_y" type="text" value="<?= htmlspecialchars($_POST['coordinate_y'] ?? '') ?>" placeholder="Например: 55.7558" required>
            </div>
            <div class="col">
                <label for="coordinate_x">Координата X (долгота)</label>
                <input id="coordinate_x" name="coordinate_x" type="text" value="<?= htmlspecialchars($_POST['coordinate_x'] ?? '') ?>" placeholder="Например: 37.6173" required>
            </div>
        </div>

        <label for="total_spaces">Количество мест</label>
        <input id="total_spaces" name="total_spaces" type="number" min="1" value="<?= htmlspecialchars($_POST['total_spaces'] ?? '') ?>" placeholder="Например: 15" required>

        <div class="actions">
            <button type="submit" class="btn-primary">Добавить парковку</button>
            <a href="parking_zones.php" class="btn-secondary">Отмена</a>
        </div>
    </form>
</main>
</body>
</html>
