<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;




Route::middleware('authenticated')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.submit');
});

Route::prefix('warga')->group(function () {
    Route::get('dashboard', [WargaController::class, 'dashboard'])->name('warga.dashboard');
    Route::get('laporan', [WargaController::class, 'laporanView'])->name('warga.laporan');
    Route::get('profile', [WargaController::class, 'profileView'])->name('warga.profile');
    Route::post('laporan-store', [WargaController::class, 'store'])->name('warga.laporan.store');
    Route::get('laporan/{laporan}', [WargaController::class, 'show'])->name('warga.laporan.show');
});


Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Home Page Route
Route::get('/', function () {
    return view('home');
});
