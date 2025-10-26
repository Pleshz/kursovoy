<?php
session_start();

function checkAccess($requiredRole = null)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../front/pages/login.php");
        exit;
    }

    $timeout = 1200;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        header("Location: ../../front/pages/login.php?timeout=1");
        exit;
    }

    $_SESSION['last_activity'] = time();
    
    if ($requiredRole !== null && $_SESSION['role'] !== $requiredRole) {
        header("Location: ../../front/pages/dashboard.php");
        exit;
    }
}