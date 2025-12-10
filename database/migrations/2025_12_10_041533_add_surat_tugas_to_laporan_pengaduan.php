<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            // File surat tugas (PDF)
            $table->string('surat_tugas_file')->nullable()->after('bukti_pendukung');

            // Relasi opsional ke pengajuan_surat_tugas
            $table->unsignedBigInteger('surat_tugas_id')->nullable()->after('surat_tugas_file');

            $table->foreign('surat_tugas_id')
                ->references('pengajuan_surat_id')->on('pengajuan_surat_tugas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            $table->dropForeign(['surat_tugas_id']);
            $table->dropColumn(['surat_tugas_file', 'surat_tugas_id']);
        });
    }
};
