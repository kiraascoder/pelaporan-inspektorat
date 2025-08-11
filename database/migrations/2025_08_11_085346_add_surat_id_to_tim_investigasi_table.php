<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tim_investigasi', function (Blueprint $table) {
            // tambah kolom nullable agar data lama aman
            $table->foreignId('surat_id')
                ->nullable()                
                ->constrained('surat_tugas', 'surat_id')
                ->nullOnDelete(); // jika surat dihapus, set NULL
        });        
    }

    public function down(): void
    {
        Schema::table('tim_investigasi', function (Blueprint $table) {
            $table->dropIndex('tim_investigasi_status_tim_surat_id_index');
            $table->dropConstrainedForeignId('surat_id');
        });
    }
};
