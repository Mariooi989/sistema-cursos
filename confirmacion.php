<?php
require_once "config/database.php";
include "includes/header.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Inscripción no especificada");
}

$sql = "SELECT i.*, c.titulo, c.precio, c.fecha_inicio
        FROM inscripciones i
        INNER JOIN cursos c ON i.curso_id = c.id
        WHERE i.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$inscripcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$inscripcion) {
    die("Inscripción no encontrada");
}

$estado = $inscripcion['estado_pago'];
?>

<!-- HERO CONFIRMACIÓN -->
<div class="confirmacion-hero">
    <div class="confirmacion-hero-contenido">

        <div class="pasos pasos-hero">
            <span class="paso completo">1 Datos</span>
            <span class="paso completo">2 Pago</span>
            <span class="paso activo">3 Confirmación</span>
        </div>

        <?php if ($estado === 'aprobado'): ?>
            <div class="confirmacion-icono aprobado-icono">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <h1 class="confirmacion-titulo">¡Inscripción confirmada!</h1>
            <p class="confirmacion-subtitulo">Tu pago fue aprobado. Ya quedás matriculado en el curso.</p>
        <?php elseif ($estado === 'pendiente'): ?>
            <div class="confirmacion-icono pendiente-icono">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <h1 class="confirmacion-titulo">Inscripción en proceso</h1>
            <p class="confirmacion-subtitulo">Tu inscripción fue registrada. Acercate a la sede para finalizar la matriculación.</p>
        <?php else: ?>
            <div class="confirmacion-icono rechazado-icono">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
            <h1 class="confirmacion-titulo">Pago rechazado</h1>
            <p class="confirmacion-subtitulo">No se pudo procesar el pago. Intentá nuevamente o acercate a la sede.</p>
        <?php endif; ?>

    </div>
</div>

<!-- LAYOUT CARDS -->
<div class="confirmacion-layout">

    <div class="confirmacion-card">
        <div class="confirmacion-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <h2>Datos del alumno</h2>
        </div>
        <div class="confirmacion-datos">
            <div class="confirmacion-dato">
                <span class="dato-label">Nombre completo</span>
                <span class="dato-valor"><?= htmlspecialchars($inscripcion['nombre']) ?> <?= htmlspecialchars($inscripcion['apellido']) ?></span>
            </div>
            <div class="confirmacion-dato">
                <span class="dato-label">Email</span>
                <span class="dato-valor"><?= htmlspecialchars($inscripcion['email']) ?></span>
            </div>
        </div>
    </div>

    <div class="confirmacion-card">
        <div class="confirmacion-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            <h2>Resumen de inscripción</h2>
        </div>
        <div class="confirmacion-datos">
            <div class="confirmacion-dato">
                <span class="dato-label">Curso</span>
                <span class="dato-valor dato-destacado"><?= htmlspecialchars($inscripcion['titulo']) ?></span>
            </div>
            <div class="confirmacion-dato">
                <span class="dato-label">Fecha de inicio</span>
                <span class="dato-valor"><?= htmlspecialchars($inscripcion['fecha_inicio']) ?></span>
            </div>
            <div class="confirmacion-dato">
                <span class="dato-label">Método de pago</span>
                <span class="dato-valor"><?= ucfirst(htmlspecialchars($inscripcion['metodo_pago'])) ?></span>
            </div>
        </div>
        <div class="confirmacion-total">
            <span>Total abonado</span>
            <strong>$<?= number_format($inscripcion['precio'], 2) ?></strong>
        </div>
        <div class="confirmacion-estado-wrap">
            <span class="dato-label">Estado del pago</span>
            <?php if ($estado === 'aprobado'): ?>
                <span class="estado-badge estado-aprobado">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Aprobado
                </span>
            <?php elseif ($estado === 'pendiente'): ?>
                <span class="estado-badge estado-pendiente">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Pendiente
                </span>
            <?php else: ?>
                <span class="estado-badge estado-rechazado">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Rechazado
                </span>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- AVISO EXTRA -->
<?php if ($estado === 'pendiente'): ?>
<div class="confirmacion-aviso pendiente-aviso">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span>Recordá acercarte a nuestra sede para completar la matriculación y presentar el comprobante de pago.</span>
</div>
<?php elseif ($estado === 'rechazado'): ?>
<div class="confirmacion-aviso rechazado-aviso">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span>Podés reintentar el pago o comunicarte con nosotros para más información.</span>
</div>
<?php endif; ?>

<!-- BOTÓN -->
<div class="acciones-confirmacion">
    <a href="cursos.php" class="btn-principal btn-volver">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver a cursos
    </a>
</div>

<?php include "includes/footer.php"; ?>