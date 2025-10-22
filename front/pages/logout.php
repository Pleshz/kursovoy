<?php
require_once __DIR__ . '/../../back/services/AuthService.php';
$auth = new AuthService();
$auth->logout();
header("Location: login.php");
exit;
?>
