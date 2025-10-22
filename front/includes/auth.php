<?php
function checkAccess($requiredRole = null)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.php");
        exit;
    }

    if ($requiredRole !== null && $_SESSION['role'] !== $requiredRole) {
        header("Location: ../pages/dashboard.php");
        exit;
    }
}
?>
