<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\CrearCarrera;
use App\Livewire\Admin\CrearAlumno;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::view('/','admin.dashboard')->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/carreras/index', CrearCarrera::class)->name('carreras.index');
    Route::get('/alumnos/index', CrearAlumno::class)->name('alumnos.index');
    Route::get('/empresas/index')->name('empresas.index');

});

Route::prefix('empresas')->name('empresas.')->group(function(){
    Route::get('/dashboard')->name('dashboard');
});

Route::prefix('alumnos')->name('alumnos.')->group(function(){
    Route::get('/dashboard')->name('dashboard');
});