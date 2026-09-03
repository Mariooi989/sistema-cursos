<?php
require_once "../config/database.php";

$inscripcion_id = $_POST['inscripcion_id'] ?? null;
$metodo_pago = $_POST['metodo_pago'] ?? null;
$numero_tarjeta = trim($_POST['numero_tarjeta'] ?? '');

if (!$inscripcion_id || !$metodo_pago) {
    die("Datos incompletos");
}

$estado_pago = "pendiente";

if ($metodo_pago === "tarjeta") {
    if (strlen($numero_tarjeta) >= 12) {
        $estado_pago = "aprobado";
    } else {
        $estado_pago = "rechazado";
    }
} elseif ($metodo_pago === "transferencia") {
    $estado_pago = "pendiente";
} elseif ($metodo_pago === "efectivo") {
    $estado_pago = "pendiente";
}

try {
    $sql = "UPDATE inscripciones 
            SET metodo_pago = ?, estado_pago = ?
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $metodo_pago,
        $estado_pago,
        $inscripcion_id
    ]);

    header("Location: ../confirmacion.php?id=" . $inscripcion_id);
    exit;

} catch (PDOException $e) {
    die("Error al confirmar el pago: " . $e->getMessage());
}