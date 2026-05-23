<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Alumno\DashboardController as AlumnoDashboardController;
use App\Http\Controllers\Empresa\DashboardController as EmpresaDashboardController;
use App\Http\Controllers\Empresa\DocumentoSolicitudController;
use App\Livewire\Admin\CrearCarrera;
use App\Livewire\Admin\CrearAlumno;
use App\Livewire\Admin\CrearEmpresa;
use App\Livewire\Empresa\GestionarVacantes;
use App\Livewire\Alumno\CatalogoVacantes;
use App\Livewire\Alumno\DetalleVacantes;
use App\Livewire\Alumno\PostularVacante;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route(match (auth()->user()->role) {
            'admin' => 'admin.dashboard',
            'empresa' => 'empresa.dashboard',
            'alumno' => 'alumno.dashboard',
            default => 'login',
        })
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/carreras/index', CrearCarrera::class)->name('carreras.index');
    Route::get('/alumnos/index', CrearAlumno::class)->name('alumnos.index');
    Route::get('/empresas/index', CrearEmpresa::class)->name('empresas.index');

});

Route::prefix('empresa')->middleware(['auth', 'role:empresa'])->name('empresa.')->group(function(){
    Route::get('/', [EmpresaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [EmpresaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/vacantes/index', GestionarVacantes::class)->name('vacantes.index');
    Route::get('/solicitudes/{solicitud}/documentos/{documento}', [DocumentoSolicitudController::class, 'show'])
        ->name('solicitudes.documentos.show');
});

Route::prefix('alumno')->middleware(['auth', 'role:alumno'])->name('alumno.')->group(function(){
    Route::get('/dashboard', [AlumnoDashboardController::class, 'index'])->name('dashboard');
    Route::post('/solicitudes/{solicitud}/confirmar', [AlumnoDashboardController::class, 'confirmarVacante'])->name('solicitudes.confirmar');
    Route::get('/vacantes', CatalogoVacantes::class)->name('vacantes.index');
    Route::get('/vacantes/{id}', DetalleVacantes::class)->name('vacantes.detalle');
    Route::get('/vacantes/{id}/postular', PostularVacante::class)->name('vacantes.postular');
});
