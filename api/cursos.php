<?php
/**
 * GET /api/cursos.php
 * Devuelve el catálogo completo de cursos para que la APK
 * lo descargue y lo guarde localmente (uso offline).
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
        "SELECT id, titulo, descripcion, imagen, categoria, precio, fecha_inicio
         FROM cursos
         ORDER BY id ASC"
    );
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    json_response([
        "ok" => true,
        "total" => count($cursos),
        "cursos" => $cursos
    ]);
} catch (PDOException $e) {
    json_response(["ok" => false, "error" => "Error al leer cursos: " . $e->getMessage()], 500);
}
