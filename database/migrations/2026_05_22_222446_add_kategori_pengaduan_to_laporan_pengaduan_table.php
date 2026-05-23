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
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            $table->string('kategori_pengaduan')->nullable()->after('tanggal_pengaduan');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pengaduan', function (Blueprint $table) {
            $table->dropColumn('kategori_pengaduan');
        });
    }
};
