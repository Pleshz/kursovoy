<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../front/pages/login.php");
    exit;
}

function checkAdminAccess() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../front/pages/login.php");
        exit;
    }
}
?>
