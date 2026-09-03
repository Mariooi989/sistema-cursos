<?php
header("Content-Type: text/plain");

echo "Version de la extension mongodb (PHP): " . phpversion('mongodb') . "\n\n";

$instalados = json_decode(file_get_contents(__DIR__ . '/../vendor/composer/installed.json'), true);
$paquetes = $instalados['packages'] ?? $instalados;

foreach ($paquetes as $paquete) {
    if ($paquete['name'] === 'mongodb/mongodb') {
        echo "Version de la libreria mongodb/mongodb: " . $paquete['version'] . "\n";
    }
}