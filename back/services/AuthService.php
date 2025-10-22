<?php
require_once __DIR__ . '/../config/Connection.php';

class AuthService 
{
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($login, $password)
    {
        $query = "SELECT * FROM users WHERE login = ?";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "s", $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login'] = $user['login'];
            return true;
        }
        return false;
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser()
    {
        if ($this->isAuthenticated()) {
            return [
                'id' => $_SESSION['user_id'],
                'role' => $_SESSION['role'],
                'login' => $_SESSION['login']
            ];
        }
        return null;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}

?>