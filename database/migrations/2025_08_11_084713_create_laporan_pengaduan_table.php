<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_pengaduan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('judul_laporan');
            $table->text('isi_laporan');
            $table->string('kategori');
            $table->enum('status', ['Pending', 'Diterima', 'Dalam_Investigasi', 'Selesai', 'Ditolak'])->default('Pending');
            $table->string('lokasi_kejadian');
            $table->dateTime('tanggal_kejadian');
            $table->json('bukti_dokumen')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->timestamps();

            $table->index(['status'], 'laporan_pengaduan_status_prioritas_index');
            $table->index(['user_id'], 'laporan_pengaduan_user_id_index');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pengaduan');
    }
};
