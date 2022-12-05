<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\AdmisiController;
use App\Http\Controllers\DisiaseController;
use App\Http\Controllers\RekamListController;

Route::get('/', fn () => redirect(route('login')));

// Authenticate Routes
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
    'confirm' => false,
    'home' => 'main',
]);


/* ------------------------------
    Admin Routes Group
-------------------------------*/
Route::group(['middleware' => 'auth'], function () {
    // Dashboard Admin Routing
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Pasien Routing
    Route::group(['prefix' => 'pasien'], function () {
        Route::get('/', [PasienController::class, 'index'])->name('pasien');
        Route::get('/create', [PasienController::class, 'create'])->name('pasien.create');
        Route::post('/create', [PasienController::class, 'createPasien'])->name('pasien.create');
        Route::get('/edit/{id}', [PasienController::class, 'edit'])->name('pasien.edit');
        Route::post('/edit/{id}', [PasienController::class, 'editPasien'])->name('pasien.edit');
        Route::delete('/delete/{id}', [PasienController::class, 'deletePasien'])->name('pasien.delete');
        // Admisi Routing
        Route::group(['prefix' => 'admisi'], function () {
            Route::get('/{pasien}', [AdmisiController::class, 'index'])->name('admisi');
            Route::get('/{pasien}/create', [AdmisiController::class, 'create'])->name('admisi.create');
            Route::post('/{pasien}/create', [AdmisiController::class, 'createAdmisi'])->name('admisi.create');
            Route::get('/{pasien}/edit/{id}', [AdmisiController::class, 'edit'])->name('admisi.edit');
            Route::post('/{pasien}/edit/{id}', [AdmisiController::class, 'editAdmisi'])->name('admisi.edit');
            Route::delete('/delete/{id}', [AdmisiController::class, 'deleteAdmisi'])->name('admisi.delete');
            Route::get('{pasien}/drawing', [AdmisiController::class, 'drawing'])->name('admisi.drawing');
        });

        Route::group(['prefix' => 'disiase'], function () {
            Route::get('/', [DisiaseController::class, 'index'])->name('disiase');
            Route::post('/create', [DisiaseController::class, 'createDisiase'])->name('disiase.create');
            Route::get('/edit/{id}', [DisiaseController::class, 'edit'])->name('disiase.edit');
            Route::post('/edit/{id}', [DisiaseController::class, 'editDisiase'])->name('disiase.edit');
            Route::delete('/delete/{id}', [DisiaseController::class, 'deleteDisiase'])->name('disiase.delete');
        });

        Route::group(['prefix' => 'rekam-medis'], function () {
            Route::get('/{search?}', [RekamListController::class, 'index'])->name('rekam-list');
        });
    });
});

Route::get('/test', fn () => view('test'));
