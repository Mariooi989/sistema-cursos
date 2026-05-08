<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación de inscripción</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;">
  <div style="max-width:600px; margin:auto; background:white; padding:25px; border-radius:10px;">
    <h2 style="color:#333;">Inscripción al curso</h2>

    <p>Hola {{ $inscripcion->nombre }} {{ $inscripcion->apellido }},</p>

    @if ($inscripcion->estado_pago === 'aprobado')
      <p>Tu pago fue aprobado y tu inscripción quedó confirmada.</p>
    @elseif ($inscripcion->estado_pago === 'pendiente')
      <p>Tu inscripción fue registrada, pero el pago todavía está pendiente de confirmación.</p>
    @else
      <p>Tu pago fue rechazado o no pudo procesarse correctamente.</p>
    @endif

    <hr>

    <p><strong>Curso:</strong> {{ $inscripcion->curso }}</p>
    <p><strong>Precio:</strong> {{ $inscripcion->precio }}</p>
    <p><strong>Fecha de inicio:</strong> {{ $inscripcion->fecha_inicio ?? 'A confirmar' }}</p>
    <p><strong>Modalidad:</strong> Online en vivo</p>
    <p><strong>Estado del pago:</strong> {{ ucfirst($inscripcion->estado_pago) }}</p>

    @if ($inscripcion->estado_pago === 'aprobado')
      <p>
        Te enviaremos el enlace de acceso y los datos para conectarte antes del inicio del curso.
      </p>
    @endif

    <p style="margin-top:25px;">
      Gracias por inscribirte en Cursos Online.
    </p>
  </div>
</body>
</html>