<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_tugas', function (Blueprint $table) {
            $table->id('laporan_tugas_id');
            $table->foreignId('surat_id')->constrained('surat_tugas', 'surat_id')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('judul_laporan');
            $table->text('isi_laporan');
            $table->text('temuan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->json('bukti_pendukung')->nullable(); // array of file paths
            $table->enum('status_laporan', ['Draft', 'Submitted', 'Reviewed', 'Approved'])->default('Draft');
            $table->datetime('tanggal_submit')->nullable();
            $table->timestamps();

            $table->index(['status_laporan', 'tanggal_submit']);
            $table->index(['surat_id', 'pegawai_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_tugas');
    }
};
