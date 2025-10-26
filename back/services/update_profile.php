<?php
require_once __DIR__ . '/../config/Connection.php';
session_start();

$userId = $_SESSION['user_id'];
$login = mysqli_real_escape_string($db, $_POST['login']);
$email = mysqli_real_escape_string($db, $_POST['email']);
$password = $_POST['password'];

if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET login='$login', email='$email', password='$hashed' WHERE id=$userId";
} else {
    $query = "UPDATE users SET login='$login', email='$email', WHERE id=$userId";
}

mysqli_query($db, $query);
header('Location: ../../front/pages/profile.php');
exit;
?>
