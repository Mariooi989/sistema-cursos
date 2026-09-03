<?php
/**
 * GET  /api/inscripciones.php  -> lista las últimas inscripciones (control/depuración)
 * POST /api/inscripciones.php  -> recibe una inscripción generada offline en la APK
 *
 * Header requerido: X-API-KEY
 *
 * Body esperado (POST, JSON):
 * {
 *   "uuid": "identificador-unico-generado-en-el-celular",
 *   "nombre": "Juan",
 *   "apellido": "Pérez",
 *   "email": "juan@correo.com",
 *   "edad": 22,
 *   "curso_id": 3,
 *   "dispositivo_id": "android-abc123"
 * }
 *
 * Requiere que la tabla "inscripciones" tenga las columnas uuid, origen y
 * fecha_creacion. Ver sql/alter_inscripciones_api.sql
 */
require_once __DIR__ . '/config_api.php';
requerir_api_key();

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    try {
        $stmt = $pdo->query(
            "SELECT id, uuid, nombre, apellido, email, edad, curso_id,
                    metodo_pago, estado_pago, origen, fecha_creacion
             FROM inscripciones
             ORDER BY id DESC
             LIMIT 100"
        );
        $inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        json_response(["ok" => true, "total" => count($inscripciones), "inscripciones" => $inscripciones]);
    } catch (PDOException $e) {
        json_response(["ok" => false, "error" => $e->getMessage()], 500);
    }
}

if ($metodo === 'POST') {
    $body = leer_json_body();

    $uuid = trim($body['uuid'] ?? '');
    $nombre = trim($body['nombre'] ?? '');
    $apellido = trim($body['apellido'] ?? '');
    $email = trim($body['email'] ?? '');
    $edad = $body['edad'] ?? null;
    $curso_id = $body['curso_id'] ?? null;
    $dispositivo_id = trim($body['dispositivo_id'] ?? '');

    if (!$uuid || !$nombre || !$apellido || !$email || !$edad || !$curso_id) {
        json_response(["ok" => false, "error" => "Faltan datos obligatorios"], 422);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_response(["ok" => false, "error" => "Email inválido"], 422);
    }

    if ($edad < 1) {
        json_response(["ok" => false, "error" => "Edad inválida"], 422);
    }

    try {
        // Idempotencia: si ya existe una inscripción con este uuid, no duplicar.
        $stmtCheck = $pdo->prepare("SELECT id FROM inscripciones WHERE uuid = ?");
        $stmtCheck->execute([$uuid]);
        $existente = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existente) {
            json_response([
                "ok" => true,
                "duplicado" => true,
                "id" => (int) $existente['id'],
                "mensaje" => "Inscripción ya existente, no se duplicó"
            ]);
        }

        $sql = "INSERT INTO inscripciones
                (uuid, nombre, apellido, email, edad, curso_id, metodo_pago, estado_pago, origen, fecha_creacion)
                VALUES (?, ?, ?, ?, ?, ?, 'pendiente', 'pendiente', 'app', NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$uuid, $nombre, $apellido, $email, $edad, $curso_id]);

        json_response([
            "ok" => true,
            "duplicado" => false,
            "id" => (int) $pdo->lastInsertId()
        ], 201);

    } catch (PDOException $e) {
        json_response(["ok" => false, "error" => "Error al guardar la inscripción: " . $e->getMessage()], 500);
    }
}

json_response(["error" => "Método no permitido"], 405);
