<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('laporan_tugas', 'temuan_pemeriksaan')) {
            Schema::table('laporan_tugas', function (Blueprint $table) {
                $table->json('temuan_pemeriksaan')->nullable()->after('rekomendasi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('laporan_tugas', 'temuan_pemeriksaan')) {
            Schema::table('laporan_tugas', function (Blueprint $table) {
                $table->dropColumn('temuan_pemeriksaan');
            });
        }
    }
};
