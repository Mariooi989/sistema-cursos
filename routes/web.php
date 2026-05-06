<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $path = public_path('sistema-cursos.html');

    if (!file_exists($path)) {
        abort(404);
    }

    return response(file_get_contents($path), 200)
        ->header('Content-Type', 'text/html');
});