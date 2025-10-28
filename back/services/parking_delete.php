
<?php
require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/Connection.php';
checkAdminAccess();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../../front/admin/parking_zones.php");
    exit;
}

$parkingId = intval($_GET['id']);

$checkQuery = "SELECT id FROM parking_zones WHERE id = $parkingId";
$checkResult = mysqli_query($db, $checkQuery);

if (mysqli_num_rows($checkResult) === 0) {
    header("Location: ../../front/admin/parking_zones.php?error=not_found");
    exit;
}

$updateCarsQuery = "UPDATE cars SET parking_id = NULL WHERE parking_id = $parkingId";
mysqli_query($db, $updateCarsQuery);

$deleteQuery = "DELETE FROM parking_zones WHERE id = $parkingId";
if (mysqli_query($db, $deleteQuery)) {
    header("Location: ../../front/admin/parking_zones.php?deleted=1");
    exit;
} else {
    header("Location: ../../front/admin/parking_zones.php?error=delete_failed");
    exit;
}
?>
