<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id('pengajuan_surat_id');

            $table->unsignedBigInteger('laporan_id');



            $table->foreign('laporan_id')
                ->references('laporan_id')->on('laporan_pengaduan')->cascadeOnDelete();

            $table->string('nomor_surat')->unique()->nullable();
            // ⬇️ Kolom baru: daftar nama yang akan ditugaskan (JSON array of strings)
            $table->json('nama_ditugaskan')->nullable();

            // Deskripsi umum (akan dipakai sebagai poin "Untuk")
            $table->text('deskripsi_umum')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};