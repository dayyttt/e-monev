@extends('layouts.app')

@section('title', 'Laporan Triwulanan - E-Monev')
@section('page-title', 'Laporan Realisasi Triwulanan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Laporan Realisasi Triwulanan</h2>
            <p class="text-gray-600 text-sm">Periode: Triwulan {{ $triwulan }} Tahun {{ $tahun }}</p>
        </div>
        <div class="flex gap-3">
            <select id="filterTahun" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select id="filterTriwulan" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @for($t = 1; $t <= 4; $t++)
                <option value="{{ $t }}" {{ $t == $triwulan ? 'selected' : '' }}>Triwulan {{ $t }}</option>
                @endfor
            </select>
            <button onclick="exportPDF()" class="gradient-bg text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Total Target Keuangan</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($summary['total_target_keuangan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Total Realisasi Keuangan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_realisasi_keuangan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Rata-rata Fisik</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($summary['avg_fisik'], 2) }}%</p>
        </div>
    </div>

    <!-- Detail per Rincian Kegiatan -->
    <div class="space-y-4">
        @foreach($grouped as $item)
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="font-bold text-gray-800">{{ $item['rincian']->nama_rincian }}</h3>
                    <p class="text-sm text-gray-600">{{ $item['rincian']->subKegiatan->nama_sub_kegiatan }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Realisasi</p>
                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($item['total_realisasi_keuangan'], 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-2 px-3">Bulan</th>
                            <th class="text-right py-2 px-3">Target Keuangan</th>
                            <th class="text-right py-2 px-3">Realisasi Keuangan</th>
                            <th class="text-right py-2 px-3">%</th>
                            <th class="text-right py-2 px-3">Fisik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item['items'] as $realisasi)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 px-3">{{ $realisasi->nama_bulan }}</td>
                            <td class="py-2 px-3 text-right">Rp {{ number_format($realisasi->target_keuangan, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-right font-semibold">Rp {{ number_format($realisasi->realisasi_keuangan, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-right">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($realisasi->persentase_keuangan >= 90) bg-green-100 text-green-800
                                    @elseif($realisasi->persentase_keuangan >= 70) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ number_format($realisasi->persentase_keuangan, 1) }}%
                                </span>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-800">
                                    {{ number_format($realisasi->realisasi_fisik, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('filterTahun').addEventListener('change', updateFilter);
document.getElementById('filterTriwulan').addEventListener('change', updateFilter);

function updateFilter() {
    const tahun = document.getElementById('filterTahun').value;
    const triwulan = document.getElementById('filterTriwulan').value;
    window.location.href = `/realisasi/laporan/triwulanan?tahun=${tahun}&triwulan=${triwulan}`;
}

function exportPDF() {
    alert('Fitur export PDF sedang dalam pengembangan');
}
</script>
@endpush
