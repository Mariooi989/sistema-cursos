<?php
/**
 * Configuración común para todos los endpoints de la API del colector.
 * La APK Android habla con estos endpoints en JSON.
 */

// ── CORS / Headers ─────────────────────────────────────────
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ── API KEY ─────────────────────────────────────────────────
// Cambiá esta clave por una propia antes de publicar el sistema.
// La APK debe enviarla en el header "X-API-KEY".
define('API_KEY', 'academia-innova-colector-2026');

function requerir_api_key(): void
{
    $headers = getallheaders();
    $recibida = $headers['X-Api-Key'] ?? $headers['X-API-KEY'] ?? $_GET['api_key'] ?? '';

    if ($recibida !== API_KEY) {
        json_response(["error" => "API key inválida o ausente"], 401);
    }
}

// ── Helpers de respuesta ───────────────────────────────────
function json_response($data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function leer_json_body(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

// ── Conexiones a las bases ─────────────────────────────────
require_once __DIR__ . '/../config/database.php'; // -> $pdo (MySQL)
require_once __DIR__ . '/../config/mongodb.php';  // -> $mongoDB, $consultasCollection
