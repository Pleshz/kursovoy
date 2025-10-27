<?php
require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/Connection.php';
checkAdminAccess();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../../front/admin/cars.php");
    exit;
}

$carId = intval($_GET['id']);

$checkQuery = "SELECT id FROM cars WHERE id = $carId";
$checkResult = mysqli_query($db, $checkQuery);

if (mysqli_num_rows($checkResult) === 0) {
    header("Location: ../../front/admin/cars.php?error=not_found");
    exit;
}

$deleteQuery = "DELETE FROM cars WHERE id = $carId";
if (mysqli_query($db, $deleteQuery)) {
    header("Location: ../../front/admin/cars.php?deleted=1");
    exit;
} else {
    header("Location: ../../front/admin/cars.php?error=delete_failed");
    exit;
}
?>
