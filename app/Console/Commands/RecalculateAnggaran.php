<?php

namespace App\Console\Commands;

use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\RincianKegiatan;
use Illuminate\Console\Command;

class RecalculateAnggaran extends Command
{
    protected $signature = 'anggaran:recalculate';
    protected $description = 'Recalculate anggaran dari rincian ke atas (bottom-up)';

    public function handle()
    {
        $this->info('Memulai recalculate anggaran dari bawah ke atas...');

        // 1. Recalculate Sub Kegiatan dari Rincian
        $this->info('Recalculate Sub Kegiatan...');
        $subKegiatans = SubKegiatan::all();
        foreach ($subKegiatans as $sub) {
            $total = \DB::table('rincian_kegiatans')
                ->where('sub_kegiatan_id', $sub->id)
                ->sum('anggaran');
            
            \DB::table('sub_kegiatans')
                ->where('id', $sub->id)
                ->update(['anggaran' => $total]);
            
            $this->info("  ✓ {$sub->nama_sub_kegiatan}: Rp " . number_format($total, 0, ',', '.'));
        }

        // 2. Recalculate Kegiatan dari Sub Kegiatan
        $this->info('');
        $this->info('Recalculate Kegiatan...');
        $kegiatans = Kegiatan::all();
        foreach ($kegiatans as $kegiatan) {
            $total = \DB::table('sub_kegiatans')
                ->where('kegiatan_id', $kegiatan->id)
                ->sum('anggaran');
            
            \DB::table('kegiatans')
                ->where('id', $kegiatan->id)
                ->update(['anggaran' => $total]);
            
            $this->info("  ✓ {$kegiatan->nama_kegiatan}: Rp " . number_format($total, 0, ',', '.'));
        }

        // 3. Recalculate Bidang dari Kegiatan
        $this->info('');
        $this->info('Recalculate Bidang...');
        $bidangs = \App\Models\Bidang::all();
        foreach ($bidangs as $bidang) {
            $total = \DB::table('kegiatans')
                ->where('bidang_id', $bidang->id)
                ->sum('anggaran');
            
            \DB::table('bidangs')
                ->where('id', $bidang->id)
                ->update(['total_anggaran' => $total]);
            
            $this->info("  ✓ {$bidang->nama_bidang}: Rp " . number_format($total, 0, ',', '.'));
        }

        $this->info('');
        $this->info('✓ Recalculate anggaran selesai!');
        
        return 0;
    }
}
