<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReconocimientoController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::prefix('reconocimiento')->name('reconocimiento.')->group(function () {
        Route::controller(ReconocimientoController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });
    });
});

require __DIR__.'/settings.php';
