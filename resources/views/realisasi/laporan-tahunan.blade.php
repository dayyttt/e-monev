@extends('layouts.app')

@section('title', 'Laporan Tahunan - E-Monev')
@section('page-title', 'Laporan Realisasi Tahunan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Laporan Realisasi Tahunan</h2>
            <p class="text-gray-600 text-sm">Tahun {{ $tahun }}</p>
        </div>
        <div class="flex gap-3">
            <select id="filterTahun" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button onclick="exportPDF()" class="gradient-bg text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
            <button onclick="exportExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Total Target</p>
            <p class="text-xl font-bold text-blue-600">Rp {{ number_format($summary['total_target_keuangan'] / 1000000000, 2) }}M</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Total Realisasi</p>
            <p class="text-xl font-bold text-green-600">Rp {{ number_format($summary['total_realisasi_keuangan'] / 1000000000, 2) }}M</p>
        </div>
        <div class="bg-orange-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Persentase Keuangan</p>
            <p class="text-xl font-bold text-orange-600">{{ number_format($summary['persentase_keuangan'], 2) }}%</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Rata-rata Fisik</p>
            <p class="text-xl font-bold text-purple-600">{{ number_format($summary['avg_fisik'], 2) }}%</p>
        </div>
    </div>

    <!-- Chart Progress -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <h3 class="font-bold text-gray-800 mb-4">Progress Keuangan per Kegiatan</h3>
        <div class="space-y-4">
            @foreach($byKegiatan as $item)
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">{{ $item['kegiatan']->nama_kegiatan }}</span>
                    <span class="text-sm font-bold text-gray-800">{{ number_format($item['persentase_keuangan'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-500
                        @if($item['persentase_keuangan'] >= 90) bg-green-500
                        @elseif($item['persentase_keuangan'] >= 70) bg-yellow-500
                        @else bg-red-500 @endif" 
                        style="width: {{ min($item['persentase_keuangan'], 100) }}%">
                    </div>
                </div>
                <div class="flex justify-between mt-1 text-xs text-gray-500">
                    <span>Target: Rp {{ number_format($item['total_target_keuangan'], 0, ',', '.') }}</span>
                    <span>Realisasi: Rp {{ number_format($item['total_realisasi_keuangan'], 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Detail Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kegiatan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Target Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Realisasi Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">% Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Rata-rata Fisik</th>
                    <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byKegiatan as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <p class="font-semibold text-gray-800">{{ $item['kegiatan']->nama_kegiatan }}</p>
                        <p class="text-xs text-gray-500">{{ $item['kegiatan']->kode_kegiatan }}</p>
                    </td>
                    <td class="py-3 px-4 text-right text-sm">
                        Rp {{ number_format($item['total_target_keuangan'], 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right text-sm font-semibold text-green-600">
                        Rp {{ number_format($item['total_realisasi_keuangan'], 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($item['persentase_keuangan'] >= 90) bg-green-100 text-green-800
                            @elseif($item['persentase_keuangan'] >= 70) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ number_format($item['persentase_keuangan'], 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                            {{ number_format($item['avg_fisik'], 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($item['persentase_keuangan'] >= 90)
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        @elseif($item['persentase_keuangan'] >= 70)
                            <i class="fas fa-exclamation-circle text-yellow-500 text-xl"></i>
                        @else
                            <i class="fas fa-times-circle text-red-500 text-xl"></i>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('filterTahun').addEventListener('change', function() {
    window.location.href = `/realisasi/laporan/tahunan?tahun=${this.value}`;
});

function exportPDF() {
    alert('Fitur export PDF sedang dalam pengembangan');
}

function exportExcel() {
    alert('Fitur export Excel sedang dalam pengembangan');
}
</script>
@endpush
