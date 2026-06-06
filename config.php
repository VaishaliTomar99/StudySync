<?php
require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$host     = $_ENV['MYSQLHOST']     ?? getenv('MYSQLHOST')     ?? 'localhost';
$user     = $_ENV['MYSQLUSER']     ?? getenv('MYSQLUSER')     ?? 'root';
$password = $_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?? '';
$database = $_ENV['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?? '';
$port     = $_ENV['MYSQLPORT']     ?? getenv('MYSQLPORT')     ?? 3306;

$conn = new mysqli($host, $user, $password, $database, (int)$port);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
