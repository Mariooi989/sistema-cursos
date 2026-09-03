<?php
require_once "../config/mongodb.php";

$curso_id = $_POST['curso_id'] ?? null;
$curso_titulo = trim($_POST['curso_titulo'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if (!$curso_id || !$curso_titulo || !$nombre || !$email || !$mensaje) {
    die("Faltan datos obligatorios");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

try {
    $consultasCollection->insertOne([
        "curso_id" => (int) $curso_id,
        "curso_titulo" => $curso_titulo,
        "nombre" => $nombre,
        "email" => $email,
        "mensaje" => $mensaje,
        "estado" => "pendiente",
        "fecha" => new MongoDB\BSON\UTCDateTime()
    ]);

    header("Location: ../cursos.php?consulta=ok");
    exit;

} catch (Exception $e) {
    die("Error al guardar la consulta: " . $e->getMessage());
}