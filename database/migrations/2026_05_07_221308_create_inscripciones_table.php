<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();

            $table->string('curso');
            $table->string('precio')->nullable();
            $table->string('fecha_inicio')->nullable();

            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('telefono')->nullable();
            $table->unsignedTinyInteger('edad')->nullable();
            $table->string('dni')->nullable();

            $table->string('metodo_pago')->nullable();
            $table->string('estado_pago')->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};