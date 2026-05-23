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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->nullable()->after('email');
        });

        DB::table('users')->orderBy('id')->get()->each(function ($user) {
            $base = $user->email
                ? Str::before($user->email, '@')
                : 'user' . $user->id;

            $username = $base;
            $suffix = 1;

            while (DB::table('users')
                ->where('username', $username)
                ->where('id', '!=', $user->id)
                ->exists()) {
                $username = $base . $suffix;
                $suffix++;
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update(['username' => $username]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->nullable(false)->change();
            $table->unique('username');
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
        });

        DB::table('alumnos')->orderBy('id')->get()->each(function ($alumno) {
            $username = (string) $alumno->matricula;
            $email = 'alumno' . $alumno->id . '@serviciosocial.local';

            $userId = DB::table('users')->insertGetId([
                'name' => trim($alumno->nombre . ' ' . $alumno->ap_pat . ' ' . $alumno->ap_mat),
                'email' => $email,
                'username' => $username,
                'password' => bcrypt($username),
                'role' => 'alumno',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('alumnos')
                ->where('id', $alumno->id)
                ->update(['user_id' => $userId]);
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->unique('user_id');
        });

        DB::table('empresas')->orderBy('id')->get()->each(function ($empresa) {
            DB::table('users')
                ->where('id', $empresa->user_id)
                ->update([
                    'username' => strtoupper((string) $empresa->rfc),
                    'role' => 'empresa',
                    'updated_at' => now(),
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });

        $userIds = DB::table('alumnos')->pluck('user_id')->filter()->all();

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        if (!empty($userIds)) {
            DB::table('users')->whereIn('id', $userIds)->delete();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
