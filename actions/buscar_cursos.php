<?php
require_once "../config/database.php";

header("Content-Type: application/json; charset=utf-8");

$buscar = trim($_GET['buscar'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');

if ($buscar === '') {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($categoria !== '') {
    $sql = "SELECT id, titulo, descripcion, imagen, categoria
            FROM cursos
            WHERE categoria = ?
            AND (titulo LIKE ? OR descripcion LIKE ?)
            ORDER BY titulo ASC
            LIMIT 6";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoria, "%$buscar%", "%$buscar%"]);

} else {
    $sql = "SELECT id, titulo, descripcion, imagen, categoria
            FROM cursos
            WHERE titulo LIKE ? OR descripcion LIKE ?
            ORDER BY titulo ASC
            LIMIT 6";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$buscar%", "%$buscar%"]);
}

$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cursos, JSON_UNESCAPED_UNICODE);