<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bidang')->unique();
            $table->string('nama_bidang');
            $table->string('singkatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('kepala_bidang')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Add bidang_id to kegiatans table
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->after('id')->constrained('bidangs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });
        
        Schema::dropIfExists('bidangs');
    }
};
