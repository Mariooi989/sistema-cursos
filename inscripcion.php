<?php
require_once "config/database.php";
include "includes/header.php";

$curso_id = $_GET['curso_id'] ?? null;

if (!$curso_id) {
    die("Curso no especificado");
}

$sql = "SELECT * FROM cursos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$curso_id]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    die("Curso no encontrado");
}
?>

<section class="inscripcion-hero">
    <div class="inscripcion-hero-contenido">
        <span class="etiqueta-inscripcion">Formulario de inscripción</span>
        <h1>Inscripción al curso</h1>
        <p>Completá tus datos para iniciar el proceso de inscripción online.</p>
    </div>
</section>

<div class="pasos pasos-modernos">
    <span class="paso activo">1 Datos</span>
    <span class="paso">2 Pago</span>
    <span class="paso">3 Confirmación</span>
</div>

<section class="inscripcion-layout">

    <div class="resumen-curso-inscripcion resumen-sin-imagen">
    <div class="resumen-info">
        <span class="badge-curso">Curso seleccionado</span>

        <h2><?= htmlspecialchars($curso['titulo']) ?></h2>

        <p>
            <?= htmlspecialchars($curso['descripcion']) ?>
        </p>
    </div>
</div>

    <form action="actions/guardar_inscripcion.php" method="POST" class="formulario formulario-inscripcion">
        <input type="hidden" name="curso_id" value="<?= $curso['id'] ?>">

        <h2>Datos del alumno</h2>
        <p class="texto-formulario">Ingresá la información solicitada para continuar al pago.</p>

        <div class="grupo-campo">
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="grupo-campo">
            <label>Apellido</label>
            <input type="text" name="apellido" required>
        </div>

        <div class="grupo-campo">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="grupo-campo">
            <label>Edad</label>
            <input type="number" name="edad" min="16" required>
        </div>

        <button type="submit" class="btn-continuar">
            Continuar al pago
        </button>
    </form>

</section>

<div class="volver-contenedor">
    <a href="cursos.php" class="btn-volver">← Volver a cursos</a>
</div>

<?php include "includes/footer.php"; ?>