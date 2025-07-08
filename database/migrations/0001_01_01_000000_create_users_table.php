<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nama_lengkap');
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('role', ['Admin', 'Pegawai', 'Warga', 'Ketua_Bidang_Investigasi']);
            $table->string('nip')->nullable(); // untuk pegawai
            $table->string('jabatan')->nullable(); // untuk pegawai
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index(['role', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
