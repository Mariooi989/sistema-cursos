<?php
/**
 * GET  /api/consultas.php   -> lista las últimas consultas (control/depuración)
 * POST /api/consultas.php   -> recibe una consulta generada offline en la APK
 *
 * Header requerido: X-API-KEY
 *
 * Body esperado (POST, JSON):
 * {
 *   "uuid": "identificador-unico-generado-en-el-celular",
 *   "curso_id": 3,
 *   "curso_titulo": "Redes y Cableado Estructurado",
 *   "nombre": "Juan Pérez",
 *   "email": "juan@correo.com",
 *   "mensaje": "Quisiera saber horarios",
 *   "dispositivo_id": "android-abc123"
 * }
 *
 * El campo "uuid" es generado en el celular (no en el servidor) y sirve
 * para evitar duplicados si la APK reintenta el envío por falta de red.
 */
require_once __DIR__ . '/config_api.php';
requerir_api_key();

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    try {
        $cursor = $consultasCollection->find([], [
            'sort' => ['fecha' => -1],
            'limit' => 100
        ]);

        $consultas = [];
        foreach ($cursor as $doc) {
            $consultas[] = [
                "id" => (string) $doc['_id'],
                "uuid" => $doc['uuid'] ?? null,
                "curso_id" => $doc['curso_id'] ?? null,
                "curso_titulo" => $doc['curso_titulo'] ?? null,
                "nombre" => $doc['nombre'] ?? null,
                "email" => $doc['email'] ?? null,
                "mensaje" => $doc['mensaje'] ?? null,
                "estado" => $doc['estado'] ?? null,
                "origen" => $doc['origen'] ?? 'web',
            ];
        }

        json_response(["ok" => true, "total" => count($consultas), "consultas" => $consultas]);
    } catch (Exception $e) {
        json_response(["ok" => false, "error" => $e->getMessage()], 500);
    }
}

if ($metodo === 'POST') {
    $body = leer_json_body();

    $uuid = trim($body['uuid'] ?? '');
    $curso_id = $body['curso_id'] ?? null;
    $curso_titulo = trim($body['curso_titulo'] ?? '');
    $nombre = trim($body['nombre'] ?? '');
    $email = trim($body['email'] ?? '');
    $mensaje = trim($body['mensaje'] ?? '');
    $dispositivo_id = trim($body['dispositivo_id'] ?? '');

    if (!$uuid || !$curso_id || !$curso_titulo || !$nombre || !$email || !$mensaje) {
        json_response(["ok" => false, "error" => "Faltan datos obligatorios"], 422);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_response(["ok" => false, "error" => "Email inválido"], 422);
    }

    try {
        // Idempotencia: si ya existe una consulta con este uuid, no duplicar.
        $existente = $consultasCollection->findOne(["uuid" => $uuid]);
        if ($existente) {
            json_response([
                "ok" => true,
                "duplicado" => true,
                "id" => (string) $existente['_id'],
                "mensaje" => "Consulta ya existente, no se duplicó"
            ]);
        }

        $resultado = $consultasCollection->insertOne([
            "uuid" => $uuid,
            "curso_id" => (int) $curso_id,
            "curso_titulo" => $curso_titulo,
            "nombre" => $nombre,
            "email" => $email,
            "mensaje" => $mensaje,
            "estado" => "pendiente",
            "origen" => "app",
            "dispositivo_id" => $dispositivo_id,
            "fecha" => new MongoDB\BSON\UTCDateTime()
        ]);

        json_response([
            "ok" => true,
            "duplicado" => false,
            "id" => (string) $resultado->getInsertedId()
        ], 201);

    } catch (Exception $e) {
        json_response(["ok" => false, "error" => "Error al guardar la consulta: " . $e->getMessage()], 500);
    }
}

json_response(["error" => "Método no permitido"], 405);
