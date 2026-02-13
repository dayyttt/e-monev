<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\RincianKegiatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKegiatan = Kegiatan::count();
        $totalSubKegiatan = SubKegiatan::count();
        $totalRincian = RincianKegiatan::count();
        $totalAnggaran = Kegiatan::sum('anggaran');
        $totalBidang = \App\Models\Bidang::where('status', 'aktif')->count();
        
        $kegiatanDraft = Kegiatan::where('status', 'draft')->count();
        $kegiatanAktif = Kegiatan::where('status', 'aktif')->count();
        $kegiatanSelesai = Kegiatan::where('status', 'selesai')->count();
        
        $rincianSelesai = RincianKegiatan::where('status', 'selesai')->count();
        
        $avgProgress = RincianKegiatan::avg('progress') ?? 0;
        
        $recentKegiatan = Kegiatan::with('bidang')
            ->withCount('subKegiatans')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $bidangStats = \App\Models\Bidang::withCount('kegiatans')
            ->where('status', 'aktif')
            ->orderBy('kegiatans_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalKegiatan',
            'totalSubKegiatan',
            'totalRincian',
            'totalAnggaran',
            'totalBidang',
            'kegiatanDraft',
            'kegiatanAktif',
            'kegiatanSelesai',
            'rincianSelesai',
            'avgProgress',
            'recentKegiatan',
            'bidangStats'
        ));
    }
}
