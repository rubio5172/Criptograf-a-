<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id')->constrained('carreras')->restrictOnDelete();
            $table->string('nombre', 255);
            $table->string('ap_pat', 255);
            $table->string('ap_mat', 255);
            $table->string('matricula', 20);
            $table->string('telefono', 15)->nullable();
            $table->unsignedTinyInteger('semestre')->nullable();
            $table->decimal('promedio', 4, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
