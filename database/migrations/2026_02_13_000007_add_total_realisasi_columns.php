<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add total_realisasi_keuangan to rincian_kegiatans
        Schema::table('rincian_kegiatans', function (Blueprint $table) {
            $table->decimal('total_realisasi_keuangan', 15, 2)->default(0)->after('anggaran');
        });

        // Add total_realisasi_keuangan to sub_kegiatans
        Schema::table('sub_kegiatans', function (Blueprint $table) {
            $table->decimal('total_realisasi_keuangan', 15, 2)->default(0)->after('anggaran');
        });

        // Add total_realisasi_keuangan to kegiatans
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->decimal('total_realisasi_keuangan', 15, 2)->default(0)->after('anggaran');
        });

        // Add total_realisasi_keuangan to bidangs
        Schema::table('bidangs', function (Blueprint $table) {
            $table->decimal('total_realisasi_keuangan', 15, 2)->default(0)->after('order');
            $table->decimal('total_anggaran', 15, 2)->default(0)->after('total_realisasi_keuangan');
        });
    }

    public function down(): void
    {
        Schema::table('rincian_kegiatans', function (Blueprint $table) {
            $table->dropColumn('total_realisasi_keuangan');
        });

        Schema::table('sub_kegiatans', function (Blueprint $table) {
            $table->dropColumn('total_realisasi_keuangan');
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('total_realisasi_keuangan');
        });

        Schema::table('bidangs', function (Blueprint $table) {
            $table->dropColumn(['total_realisasi_keuangan', 'total_anggaran']);
        });
    }
};
