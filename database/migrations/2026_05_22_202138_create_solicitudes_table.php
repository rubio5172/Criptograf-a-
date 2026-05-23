<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('vacante_id')->constrained('vacantes')->onDelete('cascade');

            $table->enum('estatus', ['pendiente', 'aceptado', 'rechazado'])->default('pendiente');

            $table->string('cv', 255);
            $table->string('carta', 255);
            $table->string('historial', 255);

            $table->text('comentario_empresa')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
