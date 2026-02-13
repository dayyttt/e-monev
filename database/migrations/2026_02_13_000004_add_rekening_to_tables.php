<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('kode_rekening')->nullable()->after('kode_kegiatan');
            $table->string('nama_rekening')->nullable()->after('kode_rekening');
        });

        Schema::table('sub_kegiatans', function (Blueprint $table) {
            $table->string('kode_rekening')->nullable()->after('kode_sub_kegiatan');
            $table->string('nama_rekening')->nullable()->after('kode_rekening');
        });

        Schema::table('rincian_kegiatans', function (Blueprint $table) {
            $table->string('kode_rekening')->nullable()->after('kode_rincian');
            $table->string('nama_rekening')->nullable()->after('kode_rekening');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn(['kode_rekening', 'nama_rekening']);
        });

        Schema::table('sub_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['kode_rekening', 'nama_rekening']);
        });

        Schema::table('rincian_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['kode_rekening', 'nama_rekening']);
        });
    }
};
