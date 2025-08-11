<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id('surat_id');

            // utama
            $table->string('nomor_surat')->unique();
            $table->foreignId('tim_id')->nullable()
                ->constrained('tim_investigasi', 'tim_id')
                ->cascadeOnDelete();
            $table->foreignId('laporan_id')
                ->constrained('laporan_pengaduan', 'laporan_id')
                ->cascadeOnDelete();
            $table->foreignId('dibuat_oleh')
                ->constrained('users', 'user_id')
                ->restrictOnDelete();

            // konten pokok
            $table->string('perihal');
            $table->text('deskripsi_tugas')->nullable();
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->enum('status_surat', ['Draft', 'Diterbitkan', 'Dalam_Pelaksanaan', 'Selesai'])->default('Draft');
            $table->text('catatan')->nullable();

            
            $table->date('tanggal_surat');                 // "Pada Tanggal ..."
            $table->string('kota_terbit')->nullable();     // "Dikeluarkan di ..."
            $table->string('jabatan_ttd')->nullable();     // "INSPEKTUR DAERAH ..."
            $table->string('nama_ttd')->nullable();        // "Drs. MUSTARI KADIR, M.Si."
            $table->string('pangkat_ttd')->nullable();     // "Pembina Utama Muda"
            $table->string('nip_ttd')->nullable();         // "19680119 ..."
            $table->string('lokasi')->nullable();          // opsional: lokasi/kecamatan di isi "Untuk"

            // bagian yang berupa daftar
            $table->json('dasar')->nullable();   // array string
            $table->json('anggota')->nullable(); // object: { nama:[], jabatan:[] }
            $table->json('untuk')->nullable();   // array string
            $table->json('tembusan')->nullable(); // array string

            $table->timestamps();

            // index untuk pencarian
            $table->index(['status_surat', 'tanggal_mulai'], 'surat_tugas_status_surat_tanggal_mulai_index');
            $table->index(['nomor_surat'], 'surat_tugas_nomor_surat_index');
            $table->index(['tim_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};
