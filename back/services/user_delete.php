<?php
require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/Connection.php';
checkAdminAccess();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../../front/admin/users.php?error=invalid_id");
    exit;
}

$userId = intval($_GET['id']);

$checkQuery = "SELECT id FROM users WHERE id = $userId";
$checkResult = mysqli_query($db, $checkQuery);

if (mysqli_num_rows($checkResult) === 0) {
    header("Location: ../../front/admin/users.php?error=not_found");
    exit;
}

$deleteOrdersQuery = "DELETE FROM orders WHERE user_id = $userId";
mysqli_query($db, $deleteOrdersQuery);

$deleteUserQuery = "DELETE FROM users WHERE id = $userId";

if (mysqli_query($db, $deleteUserQuery)) {
    header("Location: ../../front/admin/users.php?deleted=1");
    exit;
} else {
    header("Location: ../../front/admin/users.php?error=delete_failed");
    exit;
}
?>
