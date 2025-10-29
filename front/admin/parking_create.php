<?php
require_once __DIR__ . '/../../back/services/auth_admin.php';
require_once __DIR__ . '/../../back/config/Connection.php';
checkAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);
    $coordinate_x = floatval($_POST['coordinate_x']);
    $coordinate_y = floatval($_POST['coordinate_y']);
    $total_spaces = intval($_POST['total_spaces']);

    if ($address && $coordinate_x && $coordinate_y && $total_spaces > 0) {
        $stmt = mysqli_prepare($db, "INSERT INTO parking_zones (address, coordinate_x, coordinate_y, total_spaces, created_at) VALUES (?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "sddi", $address, $coordinate_x, $coordinate_y, $total_spaces);
        mysqli_stmt_execute($stmt);

        header("Location: parking_zones.php?created=1");
        exit;
    } else {
        $error = "Пожалуйста, заполните все поля корректно.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить парковку</title>
    <link rel="stylesheet" href="assets/admin.css">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
        .admin-form { max-width: 640px; margin-top: 20px; }
        .admin-form label { display:block; margin-top:10px; font-weight:600; }
        .admin-form input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; }
        .admin-form .row { display:flex; gap:12px; }
        .admin-form .row .col { flex:1; }
        .message { background:#fff4e6; border:1px solid #ffd1a8; padding:10px; border-radius:8px; color:#7a4b00; margin-bottom:12px; }
        .actions { margin-top:14px; display:flex; gap:10px; align-items:center; }
        .btn-primary { background:#2563eb; color:#fff; padding:10px 14px; border-radius:6px; text-decoration:none; border:none; cursor:pointer; }
        .btn-secondary { background:#e5e7eb; padding:10px 14px; border-radius:6px; text-decoration:none; color:#111; }
        .admin-content { padding-left: 40px}
        input, button {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .suggestions {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 4px;
            position: absolute;
            width: 100%;
            z-index: 10;
        }
        .suggestions div {
            padding: 8px 10px;
            cursor: pointer;
        }
        .suggestions div:hover {
            background: #f3f4f6;
        }
    </style>
</head>
<body>
<header class="admin-header">
    <div class="logo">
        <img src="../assets/img/logo.png" alt="logo" style="height:36px;">
        <h1>Панель администратора</h1>
    </div>
    <nav class="nav">
        <a href="dashboard.php">Главная</a>
        <a href="cars.php">Автомобили</a>
        <a href="parking_zones.php" class="active">Парковки</a>
        <a href="users.php">Пользователи</a>
        <a href="orders.php">Заказы</a>
        <a href="../../back/services/logout.php" class="logout">Выход</a>
    </nav>
</header>

<main class="admin-content">
    <h2>Добавить парковку</h2>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" id="parkingForm" class="admin-form">
        <div class="form-group" style="position: relative;">
            <label for="address">Адрес</label>
            <input type="text" name="address" id="address" required autocomplete="off" placeholder="Введите адрес...">
            <div class="suggestions" id="suggestions"></div>
        </div>

        <div class="form-group">
            <label for="coordinate_x">Координата X (долгота)</label>
            <input type="text" name="coordinate_x" id="coordinate_x" readonly required>
        </div>

        <div class="form-group">
            <label for="coordinate_y">Координата Y (широта)</label>
            <input type="text" name="coordinate_y" id="coordinate_y" readonly required>
        </div>

        <div class="form-group">
            <label for="total_spaces">Всего мест</label>
            <input type="number" name="total_spaces" id="total_spaces" min="1" required>
        </div>

        <button type="submit" class="btn-primary">Добавить парковку</button>
    </form>
</main>
<script>
const addressInput = document.getElementById("address");
const suggestionsBox = document.getElementById("suggestions");

let debounceTimer;

addressInput.addEventListener("input", () => {
    const query = addressInput.value.trim();
    clearTimeout(debounceTimer);

    if (query.length < 3) {
        suggestionsBox.innerHTML = "";
        return;
    }

    debounceTimer = setTimeout(async () => {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=${encodeURIComponent(query)}&accept-language=ru`);
            const data = await res.json();

            suggestionsBox.innerHTML = "";
            data.forEach(item => {
                const div = document.createElement("div");
                div.textContent = item.display_name;
                div.onclick = () => {
                    addressInput.value = item.display_name;
                    document.getElementById("coordinate_x").value = item.lon;
                    document.getElementById("coordinate_y").value = item.lat;
                    suggestionsBox.innerHTML = "";
                };
                suggestionsBox.appendChild(div);
            });
        } catch (err) {
            console.error(err);
        }
    }, 400);
});
</script>
</body>
</html>
