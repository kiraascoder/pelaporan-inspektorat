<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KepalaInspektoratController;
use App\Http\Controllers\KetuaBidangController;
use App\Http\Controllers\LaporanPengaduanController;
use App\Http\Controllers\LaporanTugasController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\SekretarisController;
use App\Http\Controllers\WargaController;
use Barryvdh\DomPDF\Facade\Pdf;
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
    // Route::get('reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('laporan-pegawai', [AdminController::class, 'review'])->name('admin.reports');
    Route::delete('users/{user}/delete', [AdminController::class, 'userDestroy'])
        ->name('admin.users.destroy');
    Route::patch('users/{user}/toggle', [AdminController::class, 'userToggle'])
        ->name('admin.users.toggle');
    Route::post('buat-user', [AdminController::class, 'buatUser'])->name('admin.users.store');
    Route::put('edit-user/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('users/{user}/detail', [AdminController::class, 'detailUser'])->name('admin.detail.user');
});


Route::prefix('ketua-investigasi')->group(function () {
    Route::get('dashboard', [KetuaBidangController::class, 'dashboard'])->name('ketua_bidang.dashboard');
    Route::get('laporan-masuk', [KetuaBidangController::class, 'laporan'])->name('ketua_bidang.laporan');
    Route::get('laporan/{laporan}', [KetuaBidangController::class, 'show'])->name('ketua_bidang.laporan.show');
    Route::get('tim', [KetuaBidangController::class, 'tim'])->name('ketua_bidang.tim');
    Route::post('store-tim', [KetuaBidangController::class, 'storeTim'])->name('ketua_bidang.store-tim');
    Route::get('tim/{tim_id}', [KetuaBidangController::class, 'showTimInvestigasi'])->name('ketua_bidang.tim.show');
    Route::get('surat', [KetuaBidangController::class, 'suratTugas'])->name('ketua_bidang.surat');
    Route::get('review', [KetuaBidangController::class, 'review'])->name('ketua_bidang.review');
    Route::put('/laporan/{laporan}/status', [KetuaBidangController::class, 'updateStatusLaporan'])->name('ketua.laporan.updateStatus');
    Route::get('/surat-tugas/{pengajuanSurat}/detail', [KetuaBidangController::class, 'showSurat'])->name('ketua-bidang.surat.show');
    Route::get(
        '/surat-tugas/{pengajuanSurat}/cetak-pdf',
        [KetuaBidangController::class, 'generatePdf']
    )->name('ketua-bidang-surat.cetak-pdf');
});

Route::prefix('pegawai')->group(function () {
    Route::get('dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
    Route::get('laporan', [PegawaiController::class, 'laporan'])->name('pegawai.laporan');
    Route::get('laporan/{laporan}', [PegawaiController::class, 'showLaporan'])->name('pegawai.laporan.show');
    Route::get('tim/{tim_id}', [PegawaiController::class, 'showTimInvestigasi'])->name('pegawai.tim.show');
    Route::patch('/tim/{tim_id}/status', [PegawaiController::class, 'updateStatusLaporan'])->name('ketuaTim.tim.laporan.updateStatus');
    Route::get('tim', [PegawaiController::class, 'tim'])->name('pegawai.tim');
    Route::get('laporan-tugas', [PegawaiController::class, 'laporanTugas'])->name('pegawai.laporan_tugas');
    Route::post('laporan-tugas/store', [LaporanTugasController::class, 'store'])->name('pegawai.laporan_tugas.store');    
    Route::get('report-tugas/{laporan}/detail', [LaporanTugasController::class, 'showLaporan'])->name('pegawai.report.show');
    Route::put('/laporan/{laporan}/status', [LaporanPengaduanController::class, 'updateStatus'])->name('pegawai.laporan.updateStatus');
    Route::put('{id}/update', [LaporanTugasController::class, 'update'])->name('laporan_tugas.update');
    Route::delete('{id}', [LaporanTugasController::class, 'destroy'])->name('laporan_tugas.destroy');

    Route::post('{id}/approve', [LaporanTugasController::class, 'setStatus'])
        ->name('laporan_tugas.approve');

    Route::get('{id}/download', [LaporanTugasController::class, 'download'])
        ->name('laporan_tugas.download');
});

Route::prefix('sekretaris')->group(function () {
    Route::get('dashboard', [SekretarisController::class, 'dashboard'])->name('sekretaris.dashboard');
    Route::get('laporan', [SekretarisController::class, 'laporan'])->name('sekretaris.laporan');
    Route::get('laporan/{laporan}', [SekretarisController::class, 'showLaporan'])->name('sekretaris.laporan.show');
    Route::get('tim', [SekretarisController::class, 'tim'])->name('sekretaris.tim');
    Route::get('tim/{tim_id}', [SekretarisController::class, 'showTimInvestigasi'])->name('sekretaris.tim.show');
    Route::get('laporan-tugas', [SekretarisController::class, 'laporanTugas'])->name('sekretaris.laporan_tugas');
    Route::get('report-tugas/{laporan}/', [LaporanTugasController::class, 'show'])->name('sekretaris.report.show');
    Route::get('/surat-tugas', [SekretarisController::class, 'suratTugas'])->name('sekretaris.surat_tugas');
    Route::get('/surat-tugas/{pengajuanSurat}/detail', [SekretarisController::class, 'show'])->name('sekretaris-surat.show');
    Route::delete('/surat-tugas/{pengajuanSurat}/destroy', [SekretarisController::class, 'destroy'])->name('sekretaris-surat.destroy');
    Route::put('/surat-tugas/{pengajuanSurat}/update-status', [SekretarisController::class, 'setNomorDanSelesai'])->name('sekretaris-surat.update-status');
    Route::get(
        'sekretaris/surat-tugas/{pengajuanSurat}/cetak-pdf',
        [SekretarisController::class, 'generatePdf']
    )->name('sekretaris-surat.cetak-pdf');    
});

Route::prefix('kepala-inspektorat')->group(function () {
    Route::get('dashboard', [KepalaInspektoratController::class, 'dashboard'])->name('kepala.dashboard');
    Route::get('laporan', [KepalaInspektoratController::class, 'laporan'])->name('kepala.laporan');
    Route::get('laporan/{laporan}', [KepalaInspektoratController::class, 'showLaporan'])->name('kepala.laporan.show');
    Route::get('tim', [KepalaInspektoratController::class, 'tim'])->name('kepala.tim');
    Route::get('tim/{tim_id}', [KepalaInspektoratController::class, 'showTimInvestigasi'])->name('kepala.tim.show');
    Route::get('laporan-tugas', [KepalaInspektoratController::class, 'laporanTugas'])->name('kepala.review');
    Route::get('report-tugas/{laporan}/', [LaporanTugasController::class, 'show'])->name('kepala.report.show');
    Route::get('/surat-tugas', [KepalaInspektoratController::class, 'suratTugas'])->name('kepala.surat_tugas');
    Route::post('/surat-tugas', [PengajuanSuratController::class, 'store'])->name('pengajuan-surat.store');
    Route::get('/surat-tugas/{pengajuanSurat}/detail', [KepalaInspektoratController::class, 'show'])->name('pengajuan-surat.show');
    Route::delete('/surat-tugas/{pengajuanSurat}/destroy', [PengajuanSuratController::class, 'destroy'])->name('pengajuan-surat.destroy');
    // Route::get('/surat-tugas/{suratTugas}/store', [KepalaInspektoratController::class, 'downloadPdf']);
});

Route::get('format-surat', [WargaController::class, 'downloadFormat'])->name('format-pengaduan.download');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Home Page Route
Route::get('/', function () {
    return view('home');
});

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadHTML('
        <html>
            <head><meta charset="UTF-8"></head>
            <body style="font-family: \'DejaVu Sans\', sans-serif; font-size: 12px;">
                <h1>Test PDF</h1>
                <p>Ini adalah percobaan PDF DomPDF dengan font DejaVu Sans.</p>
            </body>
        </html>
    ');

    return $pdf->download('test.pdf');
});
