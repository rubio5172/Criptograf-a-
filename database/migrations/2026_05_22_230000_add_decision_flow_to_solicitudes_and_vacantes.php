<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->boolean('confirmada_por_alumno')->default(false)->after('estatus');
            $table->timestamp('fecha_limite_respuesta')->nullable()->after('confirmada_por_alumno');
            $table->timestamp('respondido_en')->nullable()->after('fecha_limite_respuesta');
        });

        Schema::table('vacantes', function (Blueprint $table) {
            $table->boolean('cerrada_manualmente')->default(false)->after('activa');
        });
    }

    public function down(): void
    {
        Schema::table('vacantes', function (Blueprint $table) {
            $table->dropColumn('cerrada_manualmente');
        });

        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn([
                'confirmada_por_alumno',
                'fecha_limite_respuesta',
                'respondido_en',
            ]);
        });
    }
};
