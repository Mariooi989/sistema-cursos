<?php
require_once "config/database.php";
include "includes/header.php";

$curso_id = $_GET['curso_id'] ?? null;

if (!$curso_id) die("Curso no especificado");

$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) die("Curso no encontrado");
?>

<!-- HERO -->
<div class="consulta-hero">
    <div class="consulta-hero-contenido">
        <span class="consulta-badge">Consulta sobre el curso</span>
        <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
    </div>
</div>

<!-- LAYOUT -->
<div class="consulta-layout">

    <!-- INFO DEL CURSO -->
    <div class="consulta-info-card">
        <div class="consulta-info-header">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <h2>Detalles del curso</h2>
        </div>

        <div class="consulta-datos">
            <div class="consulta-dato">
                <span class="dato-label">Curso</span>
                <span class="dato-valor dato-destacado"><?= htmlspecialchars($curso['titulo']) ?></span>
            </div>
            <?php if (!empty($curso['categoria'])): ?>
            <div class="consulta-dato">
                <span class="dato-label">Categoría</span>
                <span class="dato-valor"><?= htmlspecialchars($curso['categoria']) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($curso['fecha_inicio'])): ?>
            <div class="consulta-dato">
                <span class="dato-label">Fecha de inicio</span>
                <span class="dato-valor"><?= htmlspecialchars($curso['fecha_inicio']) ?></span>
            </div>
            <?php endif; ?>
            <div class="consulta-dato">
                <span class="dato-label">Precio</span>
                <span class="dato-valor consulta-precio">$<?= number_format($curso['precio'], 2) ?></span>
            </div>
        </div>

        <?php if (!empty($curso['imagen'])): ?>
        <div class="consulta-img">
            <img src="img/<?= htmlspecialchars($curso['imagen']) ?>" alt="<?= htmlspecialchars($curso['titulo']) ?>">
        </div>
        <?php endif; ?>

        <a href="inscripcion.php?curso_id=<?= $curso['id'] ?>" class="btn-principal consulta-btn-inscribir">
            Inscribirme ahora
        </a>
    </div>

    <!-- FORMULARIO -->
    <div class="consulta-form-card">
        <div class="consulta-info-header">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <h2>Envianos tu consulta</h2>
        </div>
        <p class="consulta-form-subtitulo">Completá el formulario y te respondemos a la brevedad.</p>

        <form action="actions/guardar_consulta.php" method="POST" class="consulta-form">
            <input type="hidden" name="curso_id"    value="<?= $curso['id'] ?>">
            <input type="hidden" name="curso_titulo" value="<?= htmlspecialchars($curso['titulo']) ?>">

            <div class="consulta-campo">
                <label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Nombre completo
                </label>
                <input type="text" name="nombre" placeholder="Tu nombre y apellido" required>
            </div>

            <div class="consulta-campo">
                <label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    Email
                </label>
                <input type="email" name="email" placeholder="tu@email.com" required>
            </div>

            <div class="consulta-campo">
                <label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Mensaje
                </label>
                <textarea name="mensaje" rows="5" placeholder="Escribí tu consulta acá..." required></textarea>
            </div>

            <button type="submit" class="btn-se-guardar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Enviar consulta
            </button>
        </form>
    </div>

</div>

<div class="acciones-confirmacion">
    <a href="cursos.php" class="btn-se-volver">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver a cursos
    </a>
</div>

<?php include "includes/footer.php"; ?>