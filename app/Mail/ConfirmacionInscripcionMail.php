<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionInscripcionMail extends Mailable
{
    use Queueable, SerializesModels;

    public Inscripcion $inscripcion;

    public function __construct(Inscripcion $inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    public function build()
    {
        return $this
            ->subject('Confirmación de inscripción - ' . $this->inscripcion->curso)
            ->view('emails.confirmacion-inscripcion');
    }
}