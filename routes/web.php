<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscripcionController;

Route::get('/', function () {
    return redirect('/sistema-cursos.html');
});

Route::post('/confirmar-pago', [InscripcionController::class, 'confirmarPago']);