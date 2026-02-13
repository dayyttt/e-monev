<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bidang_id',
        'kode_kegiatan',
        'kode_rekening',
        'nama_rekening',
        'nama_kegiatan',
        'deskripsi',
        'anggaran',
        'total_realisasi_keuangan',
        'order',
        'status'
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function subKegiatans()
    {
        return $this->hasMany(SubKegiatan::class)->orderBy('order');
    }

    public function getTotalAnggaranAttribute()
    {
        return $this->subKegiatans->sum('anggaran');
    }

    // Auto update anggaran dan realisasi ke atas
    public static function boot()
    {
        parent::boot();

        static::saved(function ($kegiatan) {
            // Update total anggaran bidang
            if ($kegiatan->bidang_id) {
                $total = \DB::table('kegiatans')
                    ->where('bidang_id', $kegiatan->bidang_id)
                    ->sum('anggaran');
                
                \DB::table('bidangs')
                    ->where('id', $kegiatan->bidang_id)
                    ->update(['total_anggaran' => $total]);
            }
        });
    }

    // Recalculate anggaran dari sub kegiatan
    public function recalculateAnggaran()
    {
        $totalAnggaran = $this->subKegiatans()->sum('anggaran');
        
        \DB::table('kegiatans')
            ->where('id', $this->id)
            ->update(['anggaran' => $totalAnggaran]);
        
        // Update bidang juga
        if ($this->bidang_id) {
            $totalBidang = \DB::table('kegiatans')
                ->where('bidang_id', $this->bidang_id)
                ->sum('anggaran');
            
            \DB::table('bidangs')
                ->where('id', $this->bidang_id)
                ->update(['total_anggaran' => $totalBidang]);
        }
    }
}
