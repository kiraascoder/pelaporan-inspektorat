<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KetuaBidangController;
use App\Http\Controllers\PegawaiController;
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

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('tim', [AdminController::class, 'tim'])->name('admin.tim');
    Route::get('surat-tugas', [AdminController::class, 'suratTugas'])->name('admin.surat_tugas');
    Route::get('reports', [AdminController::class, 'reports'])->name('admin.reports');
});


Route::prefix('ketua-investigasi')->group(function () {
    Route::get('dashboard', [KetuaBidangController::class, 'dashboard'])->name('ketua_bidang.dashboard');
});

Route::prefix('pegawai')->group(function () {
    Route::get('dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Home Page Route
Route::get('/', function () {
    return view('home');
});
