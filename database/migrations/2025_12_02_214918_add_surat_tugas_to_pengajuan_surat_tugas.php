<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surat_tugas', function (Blueprint $table) {            
            $table->string('surat_tugas_path')->nullable()->after('deskripsi_umum');
            $table->timestamp('surat_tugas_uploaded_at')->nullable()->after('surat_tugas_path');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat_tugas', function (Blueprint $table) {
            $table->dropColumn(['surat_tugas_path', 'surat_tugas_uploaded_at']);
        });
    }
};
