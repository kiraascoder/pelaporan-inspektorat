<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id('surat_id');
            $table->string('nomor_surat')->unique();
            $table->foreignId('tim_id')->constrained('tim_investigasi', 'tim_id')->onDelete('cascade');
            $table->foreignId('laporan_id')->constrained('laporan_pengaduan', 'laporan_id')->onDelete('cascade');
            $table->foreignId('dibuat_oleh')->constrained('users', 'user_id')->onDelete('restrict');
            $table->string('perihal');
            $table->text('deskripsi_tugas');
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_selesai');
            $table->enum('status_surat', ['Draft', 'Diterbitkan', 'Dalam_Pelaksanaan', 'Selesai'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['status_surat', 'tanggal_mulai']);
            $table->index('nomor_surat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_tugas');
    }
};
