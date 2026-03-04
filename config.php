<?php
// Load environment from .env if available (requires composer install)
if (file_exists(__DIR__ . '/vendor/autoload.php')){
    require_once __DIR__ . '/vendor/autoload.php';
    if (class_exists('Dotenv\\Dotenv')){
        try {
            // use single backslash for namespace in code
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
        } catch (Exception $e) {
            // ignore
        }
    }
}

return [
    'admin_user' => $_ENV['ADMIN_USER'] ?? ($_SERVER['ADMIN_USER'] ?? 'admin'),
    'admin_pass' => $_ENV['ADMIN_PASS'] ?? ($_SERVER['ADMIN_PASS'] ?? 'password'),
    // mysql-only configuration
    'db_host' => $_ENV['DB_HOST'] ?? ($_SERVER['DB_HOST'] ?? '127.0.0.1'),
    'db_port' => $_ENV['DB_PORT'] ?? ($_SERVER['DB_PORT'] ?? 3306),
    'db_name' => $_ENV['DB_NAME'] ?? ($_SERVER['DB_NAME'] ?? 'shortit'),
    'db_user' => $_ENV['DB_USER'] ?? ($_SERVER['DB_USER'] ?? 'root'),
    'db_pass' => $_ENV['DB_PASS'] ?? ($_SERVER['DB_PASS'] ?? ''),
    // control whether PHP errors are shown on screen
    'show_errors' => filter_var($_ENV['SHOW_ERRORS'] ?? ($_SERVER['SHOW_ERRORS'] ?? false), FILTER_VALIDATE_BOOLEAN),
];
