<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;

    protected $table = 'realisasi';

    protected $fillable = [
        'rincian_kegiatan_id',
        'tahun',
        'bulan',
        'triwulan',
        'realisasi_keuangan',
        'target_keuangan',
        'persentase_keuangan',
        'realisasi_fisik',
        'target_fisik',
        'persentase_fisik',
        'keterangan',
        'status_verifikasi',
        'tanggal_input',
        'input_by'
    ];

    protected $casts = [
        'tanggal_input' => 'datetime',
    ];

    public function rincianKegiatan()
    {
        return $this->belongsTo(RincianKegiatan::class);
    }

    // Helper untuk nama bulan
    public function getNamaBulanAttribute()
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$this->bulan] ?? '';
    }

    // Helper untuk nama triwulan
    public function getNamaTriwulanAttribute()
    {
        return 'Triwulan ' . $this->triwulan;
    }

    // Auto calculate dan update aggregates
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto calculate triwulan
            $model->triwulan = ceil($model->bulan / 3);
            
            // Auto calculate persentase
            if ($model->target_keuangan > 0) {
                $model->persentase_keuangan = ($model->realisasi_keuangan / $model->target_keuangan) * 100;
            }
            
            if ($model->target_fisik > 0) {
                $model->persentase_fisik = ($model->realisasi_fisik / $model->target_fisik) * 100;
            }
        });

        static::saved(function ($realisasi) {
            $realisasi->updateAggregates();
        });

        static::deleted(function ($realisasi) {
            $realisasi->updateAggregates();
        });
    }

    // Update aggregate ke level atas
    public function updateAggregates()
    {
        try {
            $rincian = $this->rincianKegiatan;
            if (!$rincian) return;

            // Update total realisasi di rincian kegiatan
            $totalRealisasiRincian = $rincian->realisasi()->sum('realisasi_keuangan');
            \DB::table('rincian_kegiatans')
                ->where('id', $rincian->id)
                ->update(['total_realisasi_keuangan' => $totalRealisasiRincian]);

            // Update sub kegiatan
            $subKegiatan = $rincian->subKegiatan;
            if ($subKegiatan) {
                $totalRealisasiSub = \DB::table('rincian_kegiatans')
                    ->where('sub_kegiatan_id', $subKegiatan->id)
                    ->sum('total_realisasi_keuangan');
                
                \DB::table('sub_kegiatans')
                    ->where('id', $subKegiatan->id)
                    ->update(['total_realisasi_keuangan' => $totalRealisasiSub]);

                // Update kegiatan
                $kegiatan = $subKegiatan->kegiatan;
                if ($kegiatan) {
                    $totalRealisasiKegiatan = \DB::table('sub_kegiatans')
                        ->where('kegiatan_id', $kegiatan->id)
                        ->sum('total_realisasi_keuangan');
                    
                    \DB::table('kegiatans')
                        ->where('id', $kegiatan->id)
                        ->update(['total_realisasi_keuangan' => $totalRealisasiKegiatan]);

                    // Update bidang
                    if ($kegiatan->bidang_id) {
                        $totalRealisasiBidang = \DB::table('kegiatans')
                            ->where('bidang_id', $kegiatan->bidang_id)
                            ->sum('total_realisasi_keuangan');
                        
                        \DB::table('bidangs')
                            ->where('id', $kegiatan->bidang_id)
                            ->update(['total_realisasi_keuangan' => $totalRealisasiBidang]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error updating aggregates: ' . $e->getMessage());
        }
    }
}
