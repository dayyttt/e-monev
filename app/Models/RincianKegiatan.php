<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_kegiatan_id',
        'kode_rincian',
        'kode_rekening',
        'nama_rekening',
        'nama_rincian',
        'deskripsi',
        'anggaran',
        'total_realisasi_keuangan',
        'order',
        'progress',
        'status'
    ];

    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class);
    }

    public function realisasi()
    {
        return $this->hasMany(Realisasi::class);
    }

    public function getTotalRealisasiKeuanganAttribute()
    {
        return $this->realisasi->sum('realisasi_keuangan');
    }

    public function getTotalRealisasiFisikAttribute()
    {
        return $this->realisasi->avg('realisasi_fisik');
    }

    // Auto recalculate anggaran ke atas saat rincian berubah
    public static function boot()
    {
        parent::boot();

        static::saved(function ($rincian) {
            $rincian->recalculateAnggaranToSubKegiatan();
        });

        static::deleted(function ($rincian) {
            $rincian->recalculateAnggaranToSubKegiatan();
        });
    }

    // Update anggaran sub kegiatan dari sum rincian
    public function recalculateAnggaranToSubKegiatan()
    {
        if ($this->sub_kegiatan_id) {
            $totalSub = \DB::table('rincian_kegiatans')
                ->where('sub_kegiatan_id', $this->sub_kegiatan_id)
                ->sum('anggaran');
            
            \DB::table('sub_kegiatans')
                ->where('id', $this->sub_kegiatan_id)
                ->update(['anggaran' => $totalSub]);
            
            // Update kegiatan
            $subKegiatan = $this->subKegiatan;
            if ($subKegiatan && $subKegiatan->kegiatan_id) {
                $totalKegiatan = \DB::table('sub_kegiatans')
                    ->where('kegiatan_id', $subKegiatan->kegiatan_id)
                    ->sum('anggaran');
                
                \DB::table('kegiatans')
                    ->where('id', $subKegiatan->kegiatan_id)
                    ->update(['anggaran' => $totalKegiatan]);
                
                // Update bidang
                $kegiatan = $subKegiatan->kegiatan;
                if ($kegiatan && $kegiatan->bidang_id) {
                    $totalBidang = \DB::table('kegiatans')
                        ->where('bidang_id', $kegiatan->bidang_id)
                        ->sum('anggaran');
                    
                    \DB::table('bidangs')
                        ->where('id', $kegiatan->bidang_id)
                        ->update(['total_anggaran' => $totalBidang]);
                }
            }
        }
    }
}
