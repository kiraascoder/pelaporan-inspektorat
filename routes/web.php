<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KetuaBidangController;
use App\Http\Controllers\LaporanTugasController;
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

    // Laporan Warga Route

    Route::post('laporan-store', [WargaController::class, 'store'])->name('warga.laporan.store');
    Route::get('laporan/{laporan}', [WargaController::class, 'show'])->name('warga.laporan.show');
    Route::delete('laporan/{id}', [WargaController::class, 'deleteLaporan'])->name('warga.laporan.destroy');

    // Update Profile
    Route::put('update-profile', [WargaController::class, 'updateProfile'])->name('warga.profile.update');
});

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('tim', [AdminController::class, 'tim'])->name('admin.tim');
    Route::get('tim/{tim_id}', [KetuaBidangController::class, 'showTimInvestigasi'])->name('admin.tim.show');
    Route::get('surat-tugas', [AdminController::class, 'suratTugas'])->name('admin.surat_tugas');
    Route::get('reports', [AdminController::class, 'reports'])->name('admin.reports');
});


Route::prefix('ketua-investigasi')->group(function () {
    Route::get('dashboard', [KetuaBidangController::class, 'dashboard'])->name('ketua_bidang.dashboard');
    Route::get('laporan-masuk', [KetuaBidangController::class, 'laporan'])->name('ketua_bidang.laporan');
    Route::get('laporan/{laporan}', [KetuaBidangController::class, 'show'])->name('ketua_bidang.laporan.show');
    Route::get('tim', [KetuaBidangController::class, 'tim'])->name('ketua_bidang.tim');
    Route::post('store-tim', [KetuaBidangController::class, 'store'])->name('ketua_bidang.store-tim');
    Route::get('tim/{tim_id}', [KetuaBidangController::class, 'showTimInvestigasi'])->name('ketua_bidang.tim.show');
    Route::get('surat', [KetuaBidangController::class, 'surat'])->name('ketua_bidang.surat');
    Route::get('surat-store', [KetuaBidangController::class, 'storeSuratTugas'])->name('ketua_bidang.surat.store');
    Route::get('review', [KetuaBidangController::class, 'review'])->name('ketua_bidang.review');
    Route::get('/surat-tugas/{suratTugas}/download', [KetuaBidangController::class, 'downloadPdf'])
        ->name('surat_tugas.download');
});

Route::prefix('pegawai')->group(function () {
    Route::get('dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
    Route::get('laporan', [PegawaiController::class, 'laporanTersedia'])->name('pegawai.laporan');
    Route::get('laporan/{laporan}', [PegawaiController::class, 'showLaporan'])->name('pegawai.laporan.show');
    Route::get('tim/{tim_id}', [PegawaiController::class, 'showTimInvestigasi'])->name('pegawai.tim.show');
    Route::get('tim', [PegawaiController::class, 'tim'])->name('pegawai.tim');
    Route::get('laporan-tugas', [PegawaiController::class, 'laporanTugas'])->name('pegawai.laporan_tugas');
    Route::post('laporan-tugas/store', [LaporanTugasController::class, 'store'])->name('pegawai.laporan_tugas.store');
    Route::get('report-tugas/{id}/', [LaporanTugasController::class, 'show'])->name('pegawai.report.show');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Home Page Route
Route::get('/', function () {
    return view('home');
});
