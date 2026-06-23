<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penandatangan', function (Blueprint $table) {
            $table->id('penandatangan_id');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('pangkat')->nullable();
            $table->string('nip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('penandatangan_id')->nullable()->after('laporan_id');

            $table->foreign('penandatangan_id')
                ->references('penandatangan_id')
                ->on('penandatangan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->dropForeign(['penandatangan_id']);
            $table->dropColumn('penandatangan_id');
        });

        Schema::dropIfExists('penandatangan');
    }
};
