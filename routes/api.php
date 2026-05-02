<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ComentarioController;

Route::apiResource('cursos', CursoController::class);
Route::apiResource('comentarios', ComentarioController::class);