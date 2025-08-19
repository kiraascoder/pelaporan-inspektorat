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
            $table->unsignedBigInteger('user_id');
            $table->string('no_pengaduan')->nullable();
            $table->date('tanggal_pengaduan')->nullable();
            $table->string('pelapor_nama');
            $table->string('pelapor_pekerjaan')->nullable();
            $table->string('pelapor_alamat')->nullable();
            $table->string('pelapor_telp')->nullable();
            $table->string('terlapor_nama')->nullable();
            $table->string('terlapor_pekerjaan')->nullable();
            $table->string('terlapor_alamat')->nullable();
            $table->string('terlapor_telp')->nullable();
            $table->text('permasalahan');
            $table->json('bukti_pendukung')->nullable();
            $table->text('harapan')->nullable();
            $table->enum('status', ['Pending', 'Diterima', 'Dalam_Investigasi', 'Selesai', 'Ditolak'])->default('Pending');
            $table->text('keterangan_admin')->nullable();
            $table->timestamps();
            $table->index('status', 'laporan_pengaduan_status_index');
            $table->foreign('user_id')
                ->references('user_id')->on('users')
                ->onDelete('cascade');
            $table->index('user_id', 'laporan_pengaduan_user_index');
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
