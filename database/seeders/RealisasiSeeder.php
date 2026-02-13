<?php

namespace Database\Seeders;

use App\Models\Realisasi;
use App\Models\RincianKegiatan;
use Illuminate\Database\Seeder;

class RealisasiSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = date('Y');
        $rincianKegiatans = RincianKegiatan::all();

        foreach ($rincianKegiatans as $rincian) {
            // Generate realisasi untuk 6 bulan terakhir
            for ($bulan = 1; $bulan <= 6; $bulan++) {
                $targetKeuangan = $rincian->anggaran / 12; // Target per bulan
                $realisasiKeuangan = $targetKeuangan * (rand(60, 110) / 100); // 60-110% dari target
                
                $targetFisik = 100 / 12; // Target fisik per bulan
                $realisasiFisik = $targetFisik * (rand(70, 120) / 100); // 70-120% dari target
                
                Realisasi::create([
                    'rincian_kegiatan_id' => $rincian->id,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'triwulan' => ceil($bulan / 3),
                    'target_keuangan' => $targetKeuangan,
                    'realisasi_keuangan' => $realisasiKeuangan,
                    'persentase_keuangan' => ($realisasiKeuangan / $targetKeuangan) * 100,
                    'target_fisik' => $targetFisik,
                    'realisasi_fisik' => min($realisasiFisik, 100),
                    'persentase_fisik' => min(($realisasiFisik / $targetFisik) * 100, 100),
                    'keterangan' => 'Data realisasi bulan ' . $bulan,
                    'status_verifikasi' => $bulan <= 4 ? 'verified' : 'pending',
                    'tanggal_input' => now()->subMonths(6 - $bulan),
                    'input_by' => 'Admin'
                ]);
            }
        }
    }
}
