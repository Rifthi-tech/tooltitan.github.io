<?php
// Define the root path
define('ROOT_PATH', dirname(__DIR__));

// Simple autoloader for classes
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include database connection
require_once ROOT_PATH . '/config/database.php';

// Include session management
require_once ROOT_PATH . '/includes/session.php';
?>