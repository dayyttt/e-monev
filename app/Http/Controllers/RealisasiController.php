<?php

namespace App\Http\Controllers;

use App\Models\Realisasi;
use App\Models\RincianKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $periode = $request->get('periode', 'bulanan'); // bulanan, triwulanan, tahunan
        
        $rincianKegiatans = RincianKegiatan::with(['subKegiatan.kegiatan', 'realisasi' => function($q) use ($tahun) {
            $q->where('tahun', $tahun);
        }])->get();
        
        return view('realisasi.index', compact('rincianKegiatans', 'tahun', 'periode'));
    }

    public function input(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        
        $rincianKegiatans = RincianKegiatan::with(['subKegiatan.kegiatan'])->get();
        
        return view('realisasi.input', compact('rincianKegiatans', 'tahun', 'bulan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rincian_kegiatan_id' => 'required|exists:rincian_kegiatans,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'realisasi_keuangan' => 'required|numeric|min:0',
            'target_keuangan' => 'required|numeric|min:0',
            'realisasi_fisik' => 'required|numeric|min:0|max:100',
            'target_fisik' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string'
        ]);

        $validated['tanggal_input'] = now();
        $validated['input_by'] = 'Admin'; // TODO: ganti dengan auth user

        $realisasi = Realisasi::updateOrCreate(
            [
                'rincian_kegiatan_id' => $validated['rincian_kegiatan_id'],
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan']
            ],
            $validated
        );

        return response()->json(['success' => true, 'data' => $realisasi]);
    }

    public function laporanBulanan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        
        $realisasi = Realisasi::with(['rincianKegiatan.subKegiatan.kegiatan'])
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->get();
        
        $summary = [
            'total_target_keuangan' => $realisasi->sum('target_keuangan'),
            'total_realisasi_keuangan' => $realisasi->sum('realisasi_keuangan'),
            'avg_fisik' => $realisasi->avg('realisasi_fisik'),
            'persentase_keuangan' => 0,
            'persentase_fisik' => 0
        ];
        
        if ($summary['total_target_keuangan'] > 0) {
            $summary['persentase_keuangan'] = ($summary['total_realisasi_keuangan'] / $summary['total_target_keuangan']) * 100;
        }
        
        return view('realisasi.laporan-bulanan', compact('realisasi', 'tahun', 'bulan', 'summary'));
    }

    public function laporanTriwulanan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $triwulan = $request->get('triwulan', ceil(date('n') / 3));
        
        $realisasi = Realisasi::with(['rincianKegiatan.subKegiatan.kegiatan'])
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get();
        
        // Group by rincian kegiatan
        $grouped = $realisasi->groupBy('rincian_kegiatan_id')->map(function($items) {
            return [
                'rincian' => $items->first()->rincianKegiatan,
                'total_target_keuangan' => $items->sum('target_keuangan'),
                'total_realisasi_keuangan' => $items->sum('realisasi_keuangan'),
                'avg_fisik' => $items->avg('realisasi_fisik'),
                'items' => $items
            ];
        });
        
        $summary = [
            'total_target_keuangan' => $realisasi->sum('target_keuangan'),
            'total_realisasi_keuangan' => $realisasi->sum('realisasi_keuangan'),
            'avg_fisik' => $realisasi->avg('realisasi_fisik')
        ];
        
        return view('realisasi.laporan-triwulanan', compact('grouped', 'tahun', 'triwulan', 'summary'));
    }

    public function laporanTahunan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $realisasi = Realisasi::with(['rincianKegiatan.subKegiatan.kegiatan'])
            ->where('tahun', $tahun)
            ->get();
        
        // Group by kegiatan
        $byKegiatan = $realisasi->groupBy(function($item) {
            return $item->rincianKegiatan->subKegiatan->kegiatan->id;
        })->map(function($items) {
            $kegiatan = $items->first()->rincianKegiatan->subKegiatan->kegiatan;
            return [
                'kegiatan' => $kegiatan,
                'total_target_keuangan' => $items->sum('target_keuangan'),
                'total_realisasi_keuangan' => $items->sum('realisasi_keuangan'),
                'avg_fisik' => $items->avg('realisasi_fisik'),
                'persentase_keuangan' => $items->sum('target_keuangan') > 0 
                    ? ($items->sum('realisasi_keuangan') / $items->sum('target_keuangan')) * 100 
                    : 0
            ];
        });
        
        $summary = [
            'total_target_keuangan' => $realisasi->sum('target_keuangan'),
            'total_realisasi_keuangan' => $realisasi->sum('realisasi_keuangan'),
            'avg_fisik' => $realisasi->avg('realisasi_fisik'),
            'persentase_keuangan' => 0
        ];
        
        if ($summary['total_target_keuangan'] > 0) {
            $summary['persentase_keuangan'] = ($summary['total_realisasi_keuangan'] / $summary['total_target_keuangan']) * 100;
        }
        
        return view('realisasi.laporan-tahunan', compact('byKegiatan', 'tahun', 'summary'));
    }
}
