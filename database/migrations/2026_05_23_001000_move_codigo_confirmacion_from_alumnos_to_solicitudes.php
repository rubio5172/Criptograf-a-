<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('codigo_confirmacion', 12)->nullable()->after('estatus');
        });

        DB::table('solicitudes')->orderBy('id')->get()->each(function ($solicitud) {
            DB::table('solicitudes')
                ->where('id', $solicitud->id)
                ->update([
                    'codigo_confirmacion' => strtoupper(Str::random(8)),
                ]);
        });

        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('codigo_confirmacion', 12)->nullable(false)->change();
            $table->unique('codigo_confirmacion');
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropUnique(['codigo_confirmacion']);
            $table->dropColumn('codigo_confirmacion');
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('codigo_confirmacion', 12)->nullable();
        });

        DB::table('alumnos')->orderBy('id')->get()->each(function ($alumno) {
            DB::table('alumnos')
                ->where('id', $alumno->id)
                ->update([
                    'codigo_confirmacion' => strtoupper(Str::random(8)),
                ]);
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('codigo_confirmacion', 12)->nullable(false)->change();
            $table->unique('codigo_confirmacion');
        });

        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropUnique(['codigo_confirmacion']);
            $table->dropColumn('codigo_confirmacion');
        });
    }
};
