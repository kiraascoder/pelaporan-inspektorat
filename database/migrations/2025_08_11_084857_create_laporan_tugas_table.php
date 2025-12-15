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
        Schema::create('laporan_tugas', function (Blueprint $table) {
            $table->id('laporan_tugas_id');

            $table->foreignId('laporan_pengaduan_id')
                ->constrained('laporan_pengaduan', 'laporan_id')
                ->onDelete('cascade');

            $table->foreignId('pegawai_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');

            $table->string('judul_laporan');
            $table->text('isi_laporan');
            $table->text('temuan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->json('bukti_pendukung')->nullable();

            $table->enum('status_laporan', ['Draft', 'Submitted', 'Approved', 'Rejected'])
                ->default('Draft');

            $table->dateTime('tanggal_submit')->nullable();
            $table->timestamps();

            $table->index(
                ['laporan_pengaduan_id', 'status_laporan'],
                'laporan_tugas_pengaduan_status_index'
            );

            $table->index(
                ['pegawai_id'],
                'laporan_tugas_pegawai_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_tugas');
    }
};
