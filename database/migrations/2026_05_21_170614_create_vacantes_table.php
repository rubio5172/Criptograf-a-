<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('empresa_nombre', 255);
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->text('requisitos');
            $table->string('horario', 100);
            $table->string('ubicacion', 255);
            $table->integer('cupo_maximo');
            $table->integer('limite_registros');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacantes');
    }
};
