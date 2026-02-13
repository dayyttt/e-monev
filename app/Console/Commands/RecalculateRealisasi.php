<?php

namespace App\Console\Commands;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\RincianKegiatan;
use Illuminate\Console\Command;

class RecalculateRealisasi extends Command
{
    protected $signature = 'realisasi:recalculate';
    protected $description = 'Recalculate total realisasi keuangan dari bawah ke atas';

    public function handle()
    {
        $this->info('Memulai recalculate realisasi keuangan...');

        // 1. Update Rincian Kegiatan
        $this->info('Update Rincian Kegiatan...');
        $rincians = RincianKegiatan::all();
        foreach ($rincians as $rincian) {
            $total = $rincian->realisasi()->sum('realisasi_keuangan');
            $rincian->update(['total_realisasi_keuangan' => $total]);
        }
        $this->info("✓ {$rincians->count()} Rincian Kegiatan updated");

        // 2. Update Sub Kegiatan
        $this->info('Update Sub Kegiatan...');
        $subKegiatans = SubKegiatan::all();
        foreach ($subKegiatans as $sub) {
            $total = $sub->rincianKegiatans()->sum('total_realisasi_keuangan');
            $sub->update(['total_realisasi_keuangan' => $total]);
        }
        $this->info("✓ {$subKegiatans->count()} Sub Kegiatan updated");

        // 3. Update Kegiatan
        $this->info('Update Kegiatan...');
        $kegiatans = Kegiatan::all();
        foreach ($kegiatans as $kegiatan) {
            $total = $kegiatan->subKegiatans()->sum('total_realisasi_keuangan');
            $kegiatan->update(['total_realisasi_keuangan' => $total]);
        }
        $this->info("✓ {$kegiatans->count()} Kegiatan updated");

        // 4. Update Bidang
        $this->info('Update Bidang...');
        $bidangs = Bidang::all();
        foreach ($bidangs as $bidang) {
            $totalRealisasi = $bidang->kegiatans()->sum('total_realisasi_keuangan');
            $totalAnggaran = $bidang->kegiatans()->sum('anggaran');
            $bidang->update([
                'total_realisasi_keuangan' => $totalRealisasi,
                'total_anggaran' => $totalAnggaran
            ]);
        }
        $this->info("✓ {$bidangs->count()} Bidang updated");

        $this->info('');
        $this->info('✓ Recalculate selesai!');
        
        return 0;
    }
}
