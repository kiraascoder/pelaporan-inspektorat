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
        Schema::create('tim_investigasi', function (Blueprint $table) {
            $table->id('tim_id');
            $table->foreignId('laporan_id')->constrained('laporan_pengaduan', 'laporan_id')->onDelete('cascade');
            $table->foreignId('ketua_tim_id')->constrained('users', 'user_id')->onDelete('restrict');
            $table->text('deskripsi_tim')->nullable();
            $table->enum('status_tim', ['Dibentuk', 'Aktif', 'Selesai'])->default('Dibentuk');           
            $table->timestamps();
            $table->index(['status_tim', 'ketua_tim_id'], 'tim_investigasi_status_tim_ketua_tim_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tim_investigasi');
    }
};
