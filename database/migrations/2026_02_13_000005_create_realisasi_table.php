<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rincian_kegiatan_id')->constrained('rincian_kegiatans')->onDelete('cascade');
            $table->integer('tahun');
            $table->integer('bulan'); // 1-12
            $table->integer('triwulan'); // 1-4
            
            // Realisasi Keuangan
            $table->decimal('realisasi_keuangan', 15, 2)->default(0);
            $table->decimal('target_keuangan', 15, 2)->default(0);
            $table->decimal('persentase_keuangan', 5, 2)->default(0);
            
            // Realisasi Fisik
            $table->decimal('realisasi_fisik', 5, 2)->default(0); // dalam persen
            $table->decimal('target_fisik', 5, 2)->default(0); // dalam persen
            $table->decimal('persentase_fisik', 5, 2)->default(0);
            
            // Keterangan
            $table->text('keterangan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('tanggal_input')->nullable();
            $table->string('input_by')->nullable();
            
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['tahun', 'bulan']);
            $table->index(['tahun', 'triwulan']);
            $table->unique(['rincian_kegiatan_id', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasi');
    }
};
