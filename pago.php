<?php
require_once "config/database.php";
include "includes/header.php";

$inscripcion_id = $_GET['inscripcion_id'] ?? null;

if (!$inscripcion_id) {
    die("Inscripción no especificada");
}

$sql = "SELECT i.*, c.titulo, c.descripcion, c.precio, c.fecha_inicio
        FROM inscripciones i
        INNER JOIN cursos c ON i.curso_id = c.id
        WHERE i.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$inscripcion_id]);
$inscripcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$inscripcion) {
    die("Inscripción no encontrada");
}
?>

<section class="pago-hero">
    <div class="pago-hero-contenido">
        <span class="etiqueta-pago">Paso 2 de 3</span>
        <h1>Pago del curso</h1>
        <p>Seleccioná un método de pago para continuar con tu inscripción.</p>
    </div>
</section>

<div class="pasos pasos-modernos">
    <span class="paso completo">1 Datos</span>
    <span class="paso activo">2 Pago</span>
    <span class="paso">3 Confirmación</span>
</div>

<section class="pago-layout">

    <div class="resumen-pago">
        <span class="badge-curso">Resumen de inscripción</span>

        <h2><?= htmlspecialchars($inscripcion['titulo']) ?></h2>

        <div class="datos-resumen">
            <div>
                <small>Alumno</small>
                <strong>
                    <?= htmlspecialchars($inscripcion['nombre']) ?>
                    <?= htmlspecialchars($inscripcion['apellido']) ?>
                </strong>
            </div>

            <div>
                <small>Email</small>
                <strong><?= htmlspecialchars($inscripcion['email']) ?></strong>
            </div>

            <div>
                <small>Estado actual</small>
                <strong>Pendiente de pago</strong>
            </div>
        </div>

        <div class="total-pago">
            <span>Total a pagar</span>
            <strong>$<?= number_format($inscripcion['precio'], 2, ',', '.') ?></strong>
        </div>

        <p class="nota-pago">
            Si elegís transferencia o efectivo, la inscripción quedará pendiente hasta que sea confirmada por administración.
        </p>
    </div>

    <form action="actions/confirmar_pago.php" method="POST" class="formulario formulario-pago">
        <input type="hidden" name="inscripcion_id" value="<?= $inscripcion['id'] ?>">

        <h2>Método de pago</h2>
        <p class="texto-formulario">
            Elegí cómo querés abonar tu inscripción.
        </p>

        <div class="grupo-campo">
            <label>Seleccionar método</label>

            <select name="metodo_pago" required>
                <option value="">Seleccionar método</option>
                <option value="tarjeta">Tarjeta de crédito / débito</option>
                <option value="transferencia">Transferencia bancaria</option>
                <option value="efectivo">Efectivo en sede</option>
            </select>
        </div>

        <div class="grupo-campo grupo-tarjeta">
            <label>Número de tarjeta</label>
            <input 
                type="text" 
                name="numero_tarjeta" 
                placeholder="Ingresá el número de tarjeta"
                maxlength="20"
            >
        </div>

        <div class="info-metodo-pago">
    <strong>Información importante</strong>
    <p>
        Para pagos con tarjeta, el sistema validará el número ingresado.  
        Para transferencia o efectivo, el estado quedará pendiente.
    </p>
</div>

<div class="datos-transferencia">
    <strong>Datos para realizar la transferencia</strong>

    <div class="dato-transferencia">
        <span>Alias</span>
        <p>Mario.760.x</p>
    </div>

    <div class="dato-transferencia">
        <span>Titular de la cuenta</span>
        <p>Cardozo Mario Oscar</p>
    </div>

    <div class="dato-transferencia">
        <span>Banco / Cuenta destino</span>
        <p>Naranja X</p>
    </div>

    <small>
        Transferí a la cuenta indicada. Luego la inscripción quedará pendiente hasta que administración confirme el pago.
    </small>
</div>

<button type="submit" class="btn-confirmar-pago">
    Confirmar pago
</button>
    </form>

</section>

<div class="volver-contenedor">
    <a href="cursos.php" class="btn-volver">← Volver a cursos</a>
</div>

<?php include "includes/footer.php"; ?>