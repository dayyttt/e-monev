<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'kode_sub_kegiatan',
        'kode_rekening',
        'nama_rekening',
        'nama_sub_kegiatan',
        'deskripsi',
        'anggaran',
        'total_realisasi_keuangan',
        'order',
        'status'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function rincianKegiatans()
    {
        return $this->hasMany(RincianKegiatan::class)->orderBy('order');
    }

    public function getTotalAnggaranAttribute()
    {
        return $this->rincianKegiatans->sum('anggaran');
    }

    // Auto recalculate anggaran ke atas saat sub kegiatan berubah
    public static function boot()
    {
        parent::boot();

        static::saved(function ($subKegiatan) {
            $subKegiatan->recalculateAnggaranToKegiatan();
        });
    }

    // Recalculate anggaran dari rincian kegiatan
    public function recalculateAnggaran()
    {
        $totalAnggaran = $this->rincianKegiatans()->sum('anggaran');
        
        \DB::table('sub_kegiatans')
            ->where('id', $this->id)
            ->update(['anggaran' => $totalAnggaran]);
        
        // Update kegiatan juga
        $this->recalculateAnggaranToKegiatan();
    }

    // Update anggaran kegiatan dari sum sub kegiatan
    public function recalculateAnggaranToKegiatan()
    {
        if ($this->kegiatan_id) {
            $totalKegiatan = \DB::table('sub_kegiatans')
                ->where('kegiatan_id', $this->kegiatan_id)
                ->sum('anggaran');
            
            \DB::table('kegiatans')
                ->where('id', $this->kegiatan_id)
                ->update(['anggaran' => $totalKegiatan]);
            
            // Update bidang juga
            $kegiatan = $this->kegiatan;
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
