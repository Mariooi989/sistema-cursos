<?php
header("Content-Type: text/plain");

$host = getenv('DB_HOST');

echo "Valor de DB_HOST leído por PHP: [" . $host . "]\n";
echo "Longitud del string: " . strlen($host) . "\n";
echo "Bytes en hexadecimal:\n";
for ($i = 0; $i < strlen($host); $i++) {
    echo sprintf("%02X ", ord($host[$i]));
}
echo "\n\n";

$ip = gethostbyname($host);

if ($ip === $host) {
    echo "No se pudo resolver el host a una IP.\n";
} else {
    echo "Resuelto correctamente a la IP: " . $ip . "\n";
}