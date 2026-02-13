<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_bidang',
        'nama_bidang',
        'singkatan',
        'deskripsi',
        'kepala_bidang',
        'nip_kepala',
        'telepon',
        'email',
        'status',
        'order',
        'total_realisasi_keuangan',
        'total_anggaran'
    ];

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class)->orderBy('order');
    }

    public function getTotalAnggaranAttribute()
    {
        return $this->kegiatans->sum('anggaran');
    }

    public function getTotalKegiatanAttribute()
    {
        return $this->kegiatans->count();
    }

    // Auto update total anggaran saat kegiatan berubah
    public static function boot()
    {
        parent::boot();

        static::saved(function ($bidang) {
            // Update total anggaran menggunakan DB query langsung
            $total = \DB::table('kegiatans')
                ->where('bidang_id', $bidang->id)
                ->sum('anggaran');
            
            \DB::table('bidangs')
                ->where('id', $bidang->id)
                ->update(['total_anggaran' => $total]);
        });
    }
}
