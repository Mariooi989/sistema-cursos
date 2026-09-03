<?php
require_once "config/database.php";
include "includes/header.php";

$buscar = trim($_GET['buscar'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');

/* CONTAR CURSOS POR CATEGORÍA */
$sqlCategorias = "SELECT categoria, COUNT(*) AS total
                  FROM cursos
                  GROUP BY categoria
                  ORDER BY categoria ASC";

$stmtCategorias = $pdo->query($sqlCategorias);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

/* BUSCAR CURSOS */
if ($buscar && $categoria) {
    $sql = "SELECT * FROM cursos
            WHERE categoria = ?
            AND (titulo LIKE ? OR descripcion LIKE ?)
            ORDER BY id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoria, "%$buscar%", "%$buscar%"]);

} elseif ($buscar) {
    $sql = "SELECT * FROM cursos
            WHERE titulo LIKE ? OR descripcion LIKE ?
            ORDER BY id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$buscar%", "%$buscar%"]);

} elseif ($categoria) {
    $sql = "SELECT * FROM cursos
            WHERE categoria = ?
            ORDER BY id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoria]);

} else {
    $sql = "SELECT * FROM cursos ORDER BY id ASC";
    $stmt = $pdo->query($sql);
}

$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="cabecera-cursos cabecera-cursos-video">

    <div class="video-fondo-cursos">
        <iframe
            src="https://www.youtube.com/embed/bLiVhw3Jb-E?autoplay=1&mute=1&loop=1&playlist=bLiVhw3Jb-E&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&iv_load_policy=3&disablekb=1"
            title="Video institucional cursos"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>

    <div class="capa-cursos"></div>

    <div class="contenido-cursos-header">
        <h1>
            <?= $categoria ? htmlspecialchars($categoria) : 'Cursos disponibles' ?>
        </h1>

        <p>
            Elegí el curso que querés realizar y comenzá tu inscripción online
            en Academia Innova.
        </p>

        <form action="cursos.php" method="GET" class="buscador-cursos buscador-ajax">
    <?php if ($categoria): ?>
        <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    <?php endif; ?>

    <input
        type="text"
        name="buscar"
        class="input-busqueda-ajax"
        placeholder="Buscar curso..."
        value="<?= htmlspecialchars($buscar) ?>"
        autocomplete="off"
        data-categoria="<?= htmlspecialchars($categoria) ?>"
    >

    <button type="submit">Buscar</button>

    <div class="resultados-ajax"></div>
</form>
    </div>

</section>

<?php if ($buscar): ?>
    <p class="resultado-busqueda">
        Resultados para: <strong><?= htmlspecialchars($buscar) ?></strong>

        <?php if ($categoria): ?>
            <a href="cursos.php?categoria=<?= urlencode($categoria) ?>">Limpiar búsqueda</a>
        <?php else: ?>
            <a href="cursos.php">Limpiar búsqueda</a>
        <?php endif; ?>
    </p>
<?php endif; ?>

<div class="contenedor-oferta">

    <aside class="filtros-niveles">
        <h2>Oferta académica</h2>
        <div class="linea-naranja"></div>

        <h3>Niveles</h3>
        <div class="linea-azul"></div>

        <a href="cursos.php" class="<?= $categoria === '' ? 'activo' : '' ?>">
            Todos los cursos
        </a>

        <?php foreach ($categorias as $cat): ?>
            <a
                href="cursos.php?categoria=<?= urlencode($cat['categoria']) ?>"
                class="<?= $categoria === $cat['categoria'] ? 'activo' : '' ?>"
            >
                <?= htmlspecialchars($cat['categoria']) ?>
                (<?= $cat['total'] ?>)
            </a>
        <?php endforeach; ?>
    </aside>

    <main class="contenido-oferta">

        <?php if (count($cursos) > 0): ?>
            <div class="lista-cursos">
                <?php foreach ($cursos as $curso): ?>
                    <div class="curso-horizontal">

                        <div class="curso-imagen">
                            <?php if (!empty($curso['imagen'])): ?>
                                <img src="img/<?= htmlspecialchars($curso['imagen']) ?>" alt="<?= htmlspecialchars($curso['titulo']) ?>">
                            <?php else: ?>
                                <img src="img/curso-default.png" alt="Curso">
                            <?php endif; ?>
                        </div>

                        <div class="curso-info">
                            <span class="badge-curso">Disponible</span>

                            <h2><?= htmlspecialchars($curso['titulo']) ?></h2>

                            <p class="descripcion-curso">
                                <?= htmlspecialchars($curso['descripcion']) ?>
                            </p>

                            <div class="acciones-curso">
                                <a href="inscripcion.php?curso_id=<?= $curso['id'] ?>" class="btn-ver-mas">
                                    Inscribirme
                                </a>

                                <a href="consulta_curso.php?curso_id=<?= $curso['id'] ?>" class="btn-consulta">
                                    Consultar
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="sin-resultados">
                <h2>No se encontraron cursos</h2>
                <p>Probá buscar con otra palabra.</p>
                <a href="cursos.php" class="btn-principal">Ver todos los cursos</a>
            </div>
        <?php endif; ?>

    </main>

</div>

<?php include "includes/footer.php"; ?>