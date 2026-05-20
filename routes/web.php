<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\CrearCarrera;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/carreras/crear', CrearCarrera::class)->name('carreras.crear');

});

Route::prefix('empresas')->name('empresas.')->group(function(){
    Route::get('/dashboard')->name('dashboard');
});

Route::prefix('alumnos')->name('alumnos.')->group(function(){
    Route::get('/dashboard')->name('dashboard');
});