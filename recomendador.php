<?php
require_once "config/mongodb.php";
include "includes/header.php";

// Preguntas desde MongoDB ordenadas
$preguntasColl = $mongoDB->preguntas_se;
$preguntas = iterator_to_array(
    $preguntasColl->find([], ['sort' => ['orden' => 1]])
);
?>

<div class="se-hero">
    <div class="se-hero-contenido">
        <div class="se-hero-icono">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <h1 class="se-titulo">¿Qué curso es para vos?</h1>
        <p class="se-subtitulo">Respondé algunas preguntas y nuestro sistema experto te recomendará el curso ideal según tu perfil.</p>
    </div>
</div>

<div class="se-contenedor">

    <?php if (empty($preguntas)): ?>
        <div class="se-aviso-vacio">
            <p>El sistema aún no tiene preguntas configuradas.</p>
        </div>
    <?php else: ?>

    <form action="actions/motor_inferencia.php" method="POST" class="se-form">
        <div class="se-pasos-form">

            <?php foreach ($preguntas as $i => $p): ?>
                <div class="se-bloque">
                    <div class="se-bloque-numero"><?= $i + 1 ?></div>
                    <div class="se-bloque-contenido">
                        <h2><?= htmlspecialchars($p['pregunta']) ?></h2>
                        <?php if ($p['tipo'] === 'checkbox'): ?>
                            <p>Podés elegir más de una.</p>
                        <?php endif; ?>

                        <div class="se-opciones <?= $p['tipo'] === 'checkbox' ? 'se-opciones-multi' : '' ?>">
                            <?php foreach ($p['opciones'] as $op): ?>
                                <label class="se-opcion">
                                    <input
                                        type="<?= $p['tipo'] ?>"
                                        name="<?= $p['tipo'] === 'checkbox'
                                            ? htmlspecialchars($p['nombre']) . '[]'
                                            : htmlspecialchars($p['nombre']) ?>"
                                        value="<?= htmlspecialchars($op['valor']) ?>"
                                        <?= $p['tipo'] === 'radio' ? 'required' : '' ?>
                                    >
                                    <span class="se-opcion-box">
                                        <?= htmlspecialchars($op['etiqueta']) ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="se-submit">
            <button type="submit" class="btn-se-analizar">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Analizar mi perfil y recomendar cursos
            </button>
        </div>
    </form>

    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>