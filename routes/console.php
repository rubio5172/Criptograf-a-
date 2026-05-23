<?php

use Illuminate\Foundation\Inspiring;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('solicitudes:expirar-aceptaciones', function () {
    $total = Solicitud::expirarAceptacionesVencidas();

    $this->info("Solicitudes vencidas procesadas: {$total}");
})->purpose('Cancela aceptaciones que no fueron confirmadas por el alumno a tiempo');

Schedule::command('solicitudes:expirar-aceptaciones')->everyMinute();
