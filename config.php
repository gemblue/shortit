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
    'db_file' => __DIR__ . '/' . ( $_ENV['DB_PATH'] ?? ($_SERVER['DB_PATH'] ?? 'data/shortit.db') ),
];
