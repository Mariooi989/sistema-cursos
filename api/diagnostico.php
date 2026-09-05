<?php
header("Content-Type: text/plain");

echo "Version de la extension mongodb (PHP): " . phpversion('mongodb') . "\n\n";

$instalados = json_decode(file_get_contents(__DIR__ . '/../vendor/composer/installed.json'), true);
$paquetes = $instalados['packages'] ?? $instalados;

foreach ($paquetes as $paquete) {
    if ($paquete['name'] === 'mongodb/mongodb') {
        echo "Version de la libreria mongodb/mongodb: " . $paquete['version'] . "\n\n";
    }
}

$carpetaBuilder = __DIR__ . '/../vendor/mongodb/mongodb/src/Builder';
echo "¿Existe la carpeta Builder?: " . (is_dir($carpetaBuilder) ? "SI" : "NO") . "\n\n";

if (is_dir($carpetaBuilder)) {
    echo "Archivos dentro de src/Builder:\n";
    foreach (scandir($carpetaBuilder) as $archivo) {
        echo " - " . $archivo . "\n";
    }
}