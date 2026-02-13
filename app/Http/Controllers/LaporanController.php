<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LaporanController extends Controller
{
    public function rekapBidang()
    {
        $bidangs = Bidang::with('kegiatans')
            ->withCount('kegiatans')
            ->where('status', 'aktif')
            ->orderBy('nama_bidang')
            ->get();
        
        $totalAnggaran = $bidangs->sum('total_anggaran');
        $totalRealisasi = $bidangs->sum('total_realisasi_keuangan');
        
        return view('laporan.rekap-bidang', compact('bidangs', 'totalAnggaran', 'totalRealisasi'));
    }

    public function recalculate()
    {
        // Run artisan command
        Artisan::call('realisasi:recalculate');
        
        return response()->json(['success' => true, 'message' => 'Recalculate berhasil']);
    }
}
