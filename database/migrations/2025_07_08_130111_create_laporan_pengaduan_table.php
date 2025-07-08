<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_pengaduan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('judul_laporan');
            $table->text('isi_laporan');
            $table->string('kategori');
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi', 'Urgent'])->default('Sedang');
            $table->enum('status', ['Pending', 'Diterima', 'Dalam_Investigasi', 'Selesai', 'Ditolak'])->default('Pending');
            $table->string('lokasi_kejadian');
            $table->datetime('tanggal_kejadian');
            $table->json('bukti_dokumen')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->timestamps();

            $table->index(['status', 'prioritas']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_pengaduan');
    }
};
