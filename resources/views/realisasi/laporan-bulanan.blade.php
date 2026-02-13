@extends('layouts.app')

@section('title', 'Laporan Bulanan - E-Monev')
@section('page-title', 'Laporan Realisasi Bulanan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Laporan Realisasi Bulanan</h2>
            <p class="text-gray-600 text-sm">Periode: 
                @php
                    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                @endphp
                {{ $namaBulan[$bulan] }} {{ $tahun }}
            </p>
        </div>
        <div class="flex gap-3">
            <select id="filterTahun" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select id="filterBulan" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @foreach($namaBulan as $index => $nama)
                    @if($index > 0)
                    <option value="{{ $index }}" {{ $index == $bulan ? 'selected' : '' }}>{{ $nama }}</option>
                    @endif
                @endforeach
            </select>
            <button onclick="exportPDF()" class="gradient-bg text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Target Keuangan</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($summary['total_target_keuangan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Realisasi Keuangan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_realisasi_keuangan'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($summary['persentase_keuangan'], 2) }}% dari target</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Rata-rata Fisik</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($summary['avg_fisik'], 2) }}%</p>
        </div>
    </div>

    <!-- Detail Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kegiatan</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Rekening</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Target Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Realisasi Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">%</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Realisasi Fisik</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($realisasi as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4">
                        <p class="font-semibold text-sm text-gray-800">{{ $item->rincianKegiatan->nama_rincian }}</p>
                        <p class="text-xs text-gray-500">{{ $item->rincianKegiatan->subKegiatan->nama_sub_kegiatan }}</p>
                        <p class="text-xs text-gray-400">{{ $item->rincianKegiatan->subKegiatan->kegiatan->nama_kegiatan }}</p>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">
                        {{ $item->rincianKegiatan->kode_rekening ?? '-' }}
                    </td>
                    <td class="py-3 px-4 text-right text-sm">
                        Rp {{ number_format($item->target_keuangan, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right text-sm font-semibold text-green-600">
                        Rp {{ number_format($item->realisasi_keuangan, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($item->persentase_keuangan >= 90) bg-green-100 text-green-800
                            @elseif($item->persentase_keuangan >= 70) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ number_format($item->persentase_keuangan, 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-800">
                            {{ number_format($item->realisasi_fisik, 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($item->status_verifikasi == 'verified') bg-green-100 text-green-800
                            @elseif($item->status_verifikasi == 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($item->status_verifikasi) }}
                        </span>
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
document.getElementById('filterTahun').addEventListener('change', updateFilter);
document.getElementById('filterBulan').addEventListener('change', updateFilter);

function updateFilter() {
    const tahun = document.getElementById('filterTahun').value;
    const bulan = document.getElementById('filterBulan').value;
    window.location.href = `/realisasi/laporan/bulanan?tahun=${tahun}&bulan=${bulan}`;
}

function exportPDF() {
    alert('Fitur export PDF sedang dalam pengembangan');
}
</script>
@endpush
