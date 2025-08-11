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
        Schema::create('anggota_tim', function (Blueprint $table) {
            $table->id('anggota_id');
            $table->foreignId('tim_id')->constrained('tim_investigasi', 'tim_id')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('role_dalam_tim', ['Ketua', 'Anggota', 'Koordinator'])->default('Anggota');
            $table->dateTime('tanggal_bergabung')->default('2025-07-08 13:10:27');
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->unique(['tim_id', 'pegawai_id'], 'anggota_tim_tim_id_pegawai_id_unique');
            $table->index(['tim_id', 'is_active'], 'anggota_tim_tim_id_is_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_tim');
    }
};
