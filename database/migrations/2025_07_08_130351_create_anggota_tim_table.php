<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anggota_tim', function (Blueprint $table) {
            $table->id('anggota_id');
            $table->foreignId('tim_id')->constrained('tim_investigasi', 'tim_id')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('role_dalam_tim', ['Ketua', 'Anggota', 'Koordinator'])->default('Anggota');
            $table->datetime('tanggal_bergabung')->default(now());
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tim_id', 'pegawai_id']); // satu pegawai hanya bisa sekali dalam satu tim
            $table->index(['tim_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggota_tim');
    }
};
