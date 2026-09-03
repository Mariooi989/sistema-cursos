<?php
require_once "../config/database.php";

$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$email = trim($_POST['email'] ?? '');
$edad = $_POST['edad'] ?? '';
$curso_id = $_POST['curso_id'] ?? '';

if (!$nombre || !$apellido || !$email || !$edad || !$curso_id) {
    die("Faltan datos obligatorios");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

if ($edad < 1) {
    die("Edad inválida");
}

try {
    $sql = "INSERT INTO inscripciones 
            (nombre, apellido, email, edad, curso_id, metodo_pago, estado_pago)
            VALUES (?, ?, ?, ?, ?, 'pendiente', 'pendiente')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombre,
        $apellido,
        $email,
        $edad,
        $curso_id
    ]);

    $inscripcion_id = $pdo->lastInsertId();

    header("Location: ../pago.php?inscripcion_id=" . $inscripcion_id);
    exit;

} catch (PDOException $e) {
    die("Error al guardar la inscripción: " . $e->getMessage());
}