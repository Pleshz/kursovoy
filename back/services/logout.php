<?php
require_once __DIR__ . '/AuthService.php';
$auth = new AuthService();
$auth->logout();
header("Location: ../../front/pages/login.php");
exit;
?>
