<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;
use App\Mail\ConfirmacionInscripcionMail;
use Illuminate\Support\Facades\Mail;

class InscripcionController extends Controller
{
    public function confirmarPago(Request $request)
    {
        $datos = $request->validate([
            'curso' => ['required', 'string', 'max:255'],
            'precio' => ['nullable', 'string', 'max:50'],
            'fecha_inicio' => ['nullable', 'string', 'max:50'],

            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'edad' => ['nullable', 'integer', 'min:12', 'max:100'],
            'dni' => ['nullable', 'string', 'max:30'],

            'metodo_pago' => ['required', 'string', 'max:50'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $datos['estado_pago'] = $this->resolverEstadoPago(
    $datos['metodo_pago'],
    $request->input('numero_tarjeta')
);

        $inscripcion = Inscripcion::create($datos);

        Mail::to($inscripcion->email)->send(new ConfirmacionInscripcionMail($inscripcion));

        return redirect('/confirmacion.html?' . http_build_query([
            'id' => $inscripcion->id,
            'curso' => $inscripcion->curso,
            'nombre' => $inscripcion->nombre,
            'apellido' => $inscripcion->apellido,
            'email' => $inscripcion->email,
            'estado' => $inscripcion->estado_pago,
            'fecha_inicio' => $inscripcion->fecha_inicio,
        ]));
    }

    private function resolverEstadoPago(string $metodoPago, ?string $numeroTarjeta = null): string
{
    if ($metodoPago === 'transferencia') {
        return 'pendiente';
    }

    if ($metodoPago === 'mercado-pago') {
        return 'aprobado';
    }

    if ($metodoPago === 'tarjeta') {
        $numeroTarjeta = preg_replace('/\s+/', '', $numeroTarjeta ?? '');

        if ($numeroTarjeta === '' || str_starts_with($numeroTarjeta, '0')) {
            return 'rechazado';
        }

        return 'aprobado';
    }

    return 'rechazado';
}
}