<?php
session_start();

function checkAccess($requiredRole = null)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../front/pages/login.php");
        exit;
    }

    if ($requiredRole !== null && $_SESSION['role'] !== $requiredRole) {
        header("Location: ../../front/pages/dashboard.php");
        exit;
    }
}