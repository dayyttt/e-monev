@extends('layouts.app')

@section('title', 'Rekap per Bidang - E-Monev')
@section('page-title', 'Rekap Realisasi per Bidang')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Rekap Realisasi per Bidang</h2>
            <p class="text-gray-600 text-sm">Otomatis dihitung dari realisasi rincian kegiatan</p>
        </div>
        <div class="flex gap-3">
            <button onclick="recalculate()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-sync-alt mr-2"></i>Recalculate
            </button>
            <button onclick="exportExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Total -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-6 mb-6 text-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-purple-100 text-sm mb-1">Total Anggaran Semua Bidang</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalAnggaran / 1000000000, 2) }}M</p>
            </div>
            <div>
                <p class="text-purple-100 text-sm mb-1">Total Realisasi Keuangan</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalRealisasi / 1000000000, 2) }}M</p>
            </div>
            <div>
                <p class="text-purple-100 text-sm mb-1">Persentase Realisasi</p>
                <p class="text-3xl font-bold">{{ $totalAnggaran > 0 ? number_format(($totalRealisasi / $totalAnggaran) * 100, 2) : 0 }}%</p>
            </div>
        </div>
    </div>

    <!-- Table per Bidang -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Bidang</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kode Rekening</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Total Anggaran</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Total Realisasi</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Sisa Anggaran</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">%</th>
                    <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Jumlah Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bidangs as $bidang)
                @php
                    $persentase = $bidang->total_anggaran > 0 ? ($bidang->total_realisasi_keuangan / $bidang->total_anggaran) * 100 : 0;
                    $sisa = $bidang->total_anggaran - $bidang->total_realisasi_keuangan;
                @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer" onclick="toggleDetail({{ $bidang->id }})">
                    <td class="py-3 px-4">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mr-2 transition-transform" id="icon-{{ $bidang->id }}"></i>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $bidang->nama_bidang }}</p>
                                <p class="text-xs text-gray-500">{{ $bidang->singkatan }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">
                        {{ $bidang->kode_bidang }}
                    </td>
                    <td class="py-3 px-4 text-right text-sm">
                        Rp {{ number_format($bidang->total_anggaran, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right text-sm font-semibold text-green-600">
                        Rp {{ number_format($bidang->total_realisasi_keuangan, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right text-sm font-semibold text-orange-600">
                        Rp {{ number_format($sisa, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($persentase >= 90) bg-green-100 text-green-800
                            @elseif($persentase >= 70) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ number_format($persentase, 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $bidang->kegiatans_count }}
                        </span>
                    </td>
                </tr>
                
                <!-- Detail Kegiatan per Bidang -->
                <tr id="detail-{{ $bidang->id }}" class="hidden">
                    <td colspan="7" class="bg-gray-50 p-4">
                        <div class="ml-8">
                            <h4 class="font-bold text-gray-700 mb-3">Detail Kegiatan:</h4>
                            <table class="w-full text-sm">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-600">Kegiatan</th>
                                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-600">Rekening</th>
                                        <th class="text-right py-2 px-3 text-xs font-semibold text-gray-600">Anggaran</th>
                                        <th class="text-right py-2 px-3 text-xs font-semibold text-gray-600">Realisasi</th>
                                        <th class="text-right py-2 px-3 text-xs font-semibold text-gray-600">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bidang->kegiatans as $kegiatan)
                                    @php
                                        $persen = $kegiatan->anggaran > 0 ? ($kegiatan->total_realisasi_keuangan / $kegiatan->anggaran) * 100 : 0;
                                    @endphp
                                    <tr class="border-b border-gray-200">
                                        <td class="py-2 px-3">
                                            <p class="font-semibold text-gray-700">{{ $kegiatan->nama_kegiatan }}</p>
                                            <p class="text-xs text-gray-500">{{ $kegiatan->kode_kegiatan }}</p>
                                        </td>
                                        <td class="py-2 px-3 text-gray-600">{{ $kegiatan->kode_rekening }}</td>
                                        <td class="py-2 px-3 text-right">Rp {{ number_format($kegiatan->anggaran, 0, ',', '.') }}</td>
                                        <td class="py-2 px-3 text-right font-semibold text-green-600">Rp {{ number_format($kegiatan->total_realisasi_keuangan, 0, ',', '.') }}</td>
                                        <td class="py-2 px-3 text-right">
                                            <span class="px-2 py-1 rounded text-xs font-semibold
                                                @if($persen >= 90) bg-green-100 text-green-800
                                                @elseif($persen >= 70) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ number_format($persen, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
function toggleDetail(id) {
    const detail = document.getElementById(`detail-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    
    detail.classList.toggle('hidden');
    icon.classList.toggle('rotate-90');
}

async function recalculate() {
    if (!confirm('Recalculate semua total realisasi dari bawah ke atas?')) return;
    
    try {
        const response = await fetch('/laporan/rekap-bidang/recalculate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            alert('Recalculate berhasil!');
            location.reload();
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

function exportExcel() {
    alert('Fitur export Excel sedang dalam pengembangan');
}
</script>
@endpush
