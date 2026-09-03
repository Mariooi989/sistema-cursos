<?php
/**
 * GET /api/alumnos.php
 * Devuelve los alumnos/clientes ya registrados (a partir de inscripciones
 * existentes) para que la APK los tenga disponibles offline como referencia
 * al cargar nuevas consultas o inscripciones en el campo.
 *
 * Header requerido: X-API-KEY
 */
require_once __DIR__ . '/config_api.php';
requerir_api_key();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(["error" => "Método no permitido"], 405);
}

try {
    $stmt = $pdo->query(
        "SELECT MIN(id) AS id, nombre, apellido, email, MAX(edad) AS edad
         FROM inscripciones
         GROUP BY nombre, apellido, email
         ORDER BY apellido ASC, nombre ASC"
    );
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    json_response([
        "ok" => true,
        "total" => count($alumnos),
        "alumnos" => $alumnos
    ]);
} catch (PDOException $e) {
    json_response(["ok" => false, "error" => "Error al leer alumnos: " . $e->getMessage()], 500);
}
