<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/local.php')) {
    require_once __DIR__ . '/local.php';
}

try {
    $mongoUri = getenv('MONGODB_URI');

    $mongoClient = new MongoDB\Client($mongoUri);
    $mongoDB = $mongoClient->academia_innova;
    $consultasCollection = $mongoDB->consultas;

} catch (Exception $e) {
    die("Error de conexión a MongoDB: " . $e->getMessage());
}