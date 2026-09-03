<?php
require_once "../config/database.php";
require_once "../config/mongodb.php";
include "../includes/header.php";

// ── Leer preguntas para armar el resumen ─────────────────
$preguntasColl = $mongoDB->preguntas_se;
$preguntas = iterator_to_array(
    $preguntasColl->find([], ['sort' => ['orden' => 1]])
);

// ── Respuestas del alumno ─────────────────────────────────
$respuestas = [];
foreach ($preguntas as $p) {
    $nombre = $p['nombre'];
    if ($p['tipo'] === 'checkbox') {
        $respuestas[$nombre] = $_POST[$nombre] ?? [];
    } else {
        $respuestas[$nombre] = $_POST[$nombre] ?? '';
    }
}

// Validar que haya al menos algo
$tieneRespuesta = false;
foreach ($respuestas as $r) {
    if (!empty($r)) { $tieneRespuesta = true; break; }
}

if (!$tieneRespuesta) {
    die("<p style='text-align:center;padding:40px'>Faltan datos. <a href='../recomendador.php'>Volvé al cuestionario</a></p>");
}

// ── Motor de inferencia — lee reglas de MongoDB ───────────
$reglasColl     = $mongoDB->reglas_se;
$todasLasReglas = $reglasColl->find();

$resultados = [];

foreach ($todasLasReglas as $regla) {
    $puntaje    = 0;
    $maxPuntaje = 0;
    $coincidencias = [];

    $condiciones = (array)($regla['condiciones'] ?? []);

    foreach ($condiciones as $campo => $valorEsperado) {
        $respuestaAlumno = $respuestas[$campo] ?? null;
        if ($respuestaAlumno === null) continue;

        // Campo de tipo array (checkbox)
        if (is_array($valorEsperado) || $valorEsperado instanceof MongoDB\Model\BSONArray) {
            $valoresEsperados = (array) $valorEsperado;
            $maxPuntaje += count($valoresEsperados) * 2;
            foreach ($valoresEsperados as $ve) {
                if (in_array($ve, (array)$respuestaAlumno)) {
                    $puntaje += 2;
                    $coincidencias[] = $campo;
                }
            }
        } else {
            // Campo simple (radio)
            $maxPuntaje += 3;
            if ($valorEsperado === $respuestaAlumno) {
                $puntaje += 3;
                $coincidencias[] = $campo;
            }
        }
    }

    if ($puntaje > 0 && $maxPuntaje > 0) {
        $resultados[] = [
            'regla'         => $regla,
            'puntaje'       => $puntaje,
            'porcentaje'    => round(($puntaje / $maxPuntaje) * 100),
            'coincidencias' => array_unique($coincidencias),
        ];
    }
}

usort($resultados, fn($a, $b) => $b['puntaje'] - $a['puntaje']);

// ── Buscar cursos en MySQL ────────────────────────────────
$cursosRecomendados = [];
foreach ($resultados as $res) {
    $cursoId = (int)($res['regla']['curso_id'] ?? 0);
    if ($cursoId && !isset($cursosRecomendados[$cursoId])) {
        $stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
        $stmt->execute([$cursoId]);
        $curso = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($curso) {
            $cursosRecomendados[$cursoId] = [
                'curso'         => $curso,
                'porcentaje'    => $res['porcentaje'],
                'coincidencias' => $res['coincidencias'],
                'justificacion' => $res['regla']['justificacion'] ?? '',
            ];
        }
    }
}

// ── Armar etiquetas dinámicas para el resumen ─────────────
$etiquetasRespuestas = [];
foreach ($preguntas as $p) {
    $nombre  = $p['nombre'];
    $mapOpciones = [];
    foreach ($p['opciones'] as $op) {
        $mapOpciones[$op['valor']] = $op['etiqueta'];
    }
    $val = $respuestas[$nombre] ?? null;
    if (empty($val)) continue;

    if (is_array($val)) {
        $textos = array_map(fn($v) => $mapOpciones[$v] ?? $v, $val);
        $etiquetasRespuestas[] = implode(', ', $textos);
    } else {
        $etiquetasRespuestas[] = $mapOpciones[$val] ?? $val;
    }
}
?>

<!-- HERO RESULTADO -->
<div class="se-resultado-hero">
    <div class="se-resultado-hero-contenido">
        <div class="se-hero-icono resultado-icono">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <h1 class="se-titulo">Resultado del análisis</h1>
        <p class="se-subtitulo">
            El sistema experto encontró
            <strong><?= count($cursosRecomendados) ?> curso(s) recomendado(s)</strong> para vos.
        </p>
    </div>
</div>

<div class="se-contenedor">

    <!-- RESUMEN DEL PERFIL -->
    <div class="se-perfil-resumen">
        <h3>Tu perfil analizado</h3>
        <div class="se-perfil-chips">
            <?php foreach ($etiquetasRespuestas as $texto): ?>
                <span class="se-chip"><?= htmlspecialchars($texto) ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($cursosRecomendados)): ?>
        <div class="se-sin-resultado">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                 fill="none" stroke="#003f8f" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <h2>Sin coincidencias exactas</h2>
            <p>No encontramos un curso que coincida con tu perfil. Explorá toda la oferta académica.</p>
            <a href="../cursos.php" class="btn-principal">Ver todos los cursos</a>
        </div>
    <?php else: ?>

        <div class="se-resultados-lista">
            <?php $rank = 1; foreach ($cursosRecomendados as $rec): ?>
                <div class="se-resultado-card <?= $rank === 1 ? 'se-resultado-top' : '' ?>">

                    <?php if ($rank === 1): ?>
                        <div class="se-badge-top">⭐ Mejor coincidencia</div>
                    <?php endif; ?>

                    <div class="se-coincidencia-header">
                        <span class="se-porcentaje-label">Coincidencia con tu perfil</span>
                        <span class="se-porcentaje-numero"><?= $rec['porcentaje'] ?>%</span>
                    </div>
                    <div class="se-barra-wrap">
                        <div class="se-barra" style="width: <?= $rec['porcentaje'] ?>%"></div>
                    </div>

                    <div class="se-resultado-cuerpo">
                        <?php if (!empty($rec['curso']['imagen'])): ?>
                            <div class="se-resultado-img">
                                <img src="../img/<?= htmlspecialchars($rec['curso']['imagen']) ?>"
                                     alt="<?= htmlspecialchars($rec['curso']['titulo']) ?>">
                            </div>
                        <?php endif; ?>

                        <div class="se-resultado-info">
                            <span class="badge-curso">Disponible</span>
                            <h2><?= htmlspecialchars($rec['curso']['titulo']) ?></h2>
                            <p class="se-resultado-desc"><?= htmlspecialchars($rec['curso']['descripcion']) ?></p>

                            <?php if (!empty($rec['justificacion'])): ?>
                                <div class="se-justificacion">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span><?= htmlspecialchars($rec['justificacion']) ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="se-por-que">
                                <strong>¿Por qué te lo recomendamos?</strong>
                                <ul>
                                    <?php foreach ($rec['coincidencias'] as $c): ?>
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                 viewBox="0 0 24 24" fill="none" stroke="#22c55e"
                                                 stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Tu <?= htmlspecialchars($c) ?> coincide con este curso
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="se-resultado-precio">
                                $<?= number_format($rec['curso']['precio'], 2) ?>
                            </div>

                            <div class="se-resultado-acciones">
                                <a href="../inscripcion.php?curso_id=<?= $rec['curso']['id'] ?>"
                                   class="btn-principal">Inscribirme</a>
                                <a href="../consulta_curso.php?curso_id=<?= $rec['curso']['id'] ?>"
                                   class="btn-consulta-se">Consultar</a>
                            </div>
                        </div>
                    </div>

                </div>
            <?php $rank++; endforeach; ?>
        </div>

        <div class="se-volver">
            <a href="../recomendador.php" class="btn-se-volver">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Volver al cuestionario
            </a>
            <a href="../cursos.php" class="btn-se-todos">Ver todos los cursos</a>
        </div>

    <?php endif; ?>
</div>

<?php include "../includes/footer.php"; ?>