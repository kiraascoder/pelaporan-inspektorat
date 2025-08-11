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
       Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id('surat_id');
            $table->string('nomor_surat')->unique();            
            $table->foreignId('laporan_id')->constrained('laporan_pengaduan', 'laporan_id')->onDelete('cascade');
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id')->onDelete('restrict');
            $table->string('perihal');
            $table->text('deskripsi_tugas');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->enum('status_surat', ['Draft','Diterbitkan','Dalam_Pelaksanaan','Selesai'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['status_surat','tanggal_mulai'], 'surat_tugas_status_surat_tanggal_mulai_index');
            $table->index(['nomor_surat'], 'surat_tugas_nomor_surat_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};
