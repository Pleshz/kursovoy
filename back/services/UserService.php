<?php
require_once __DIR__ . '/../config/Connection.php';
class UserService
{
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function userExists($login, $email)
    {
        $query = "SELECT id FROM users WHERE login = ? OR email = ?";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "ss", $login, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    public function registerUser($login, $email, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'client';

        $query = "INSERT INTO users (login, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $login, $email, $passwordHash, $role);
        return mysqli_stmt_execute($stmt);
    }
}
?>