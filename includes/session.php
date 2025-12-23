<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Session {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['role']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            // Check if we're in a subdirectory
            $loginPath = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false || 
                         strpos($_SERVER['PHP_SELF'], '/supplier/') !== false || 
                         strpos($_SERVER['PHP_SELF'], '/customer/') !== false) ? '../login.php' : 'login.php';
            header('Location: ' . $loginPath);
            exit();
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if ($_SESSION['role'] !== $role) {
            header('Location: ../index.php');
            exit();
        }
    }

    public static function hasRole($role) {
        return self::isLoggedIn() && $_SESSION['role'] === $role;
    }

    public static function getUser() {
        if (self::isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    public static function login($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    }

    public static function logout() {
        session_destroy();
        // Always redirect to login.php in the root directory
        header('Location: /tooltitan/tooltitan.github.io/login.php');
        exit();
    }
}
?>