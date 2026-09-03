<?php

if (file_exists(__DIR__ . '/local.php')) {
    require_once __DIR__ . '/local.php';
}

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$caPath = __DIR__ . '/ca.pem';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::MYSQL_ATTR_SSL_CA => $caPath,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión a MySQL: " . $e->getMessage());
}