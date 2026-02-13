@extends('layouts.app')

@section('title', 'Input Realisasi - E-Monev')
@section('page-title', 'Input Realisasi Fisik & Keuangan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Input Realisasi</h2>
            <p class="text-gray-600 text-sm">Input realisasi fisik dan keuangan per bulan</p>
        </div>
        <div class="flex gap-3">
            <select id="filterTahun" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select id="filterBulan" class="border-2 border-gray-300 rounded-lg px-4 py-2">
                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $namaBulan)
                <option value="{{ $index + 1 }}" {{ ($index + 1) == $bulan ? 'selected' : '' }}>{{ $namaBulan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kode</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kegiatan</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Rekening</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Target Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Realisasi Keuangan</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Target Fisik (%)</th>
                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Realisasi Fisik (%)</th>
                    <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rincianKegiatans as $rincian)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm">
                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-semibold">{{ $rincian->kode_rincian }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <p class="font-semibold text-sm text-gray-800">{{ $rincian->nama_rincian }}</p>
                        <p class="text-xs text-gray-500">{{ $rincian->subKegiatan->nama_sub_kegiatan }}</p>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">
                        {{ $rincian->kode_rekening ?? '-' }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <input type="number" class="w-32 border rounded px-2 py-1 text-sm text-right" 
                            id="target_keuangan_{{ $rincian->id }}" 
                            value="{{ $rincian->anggaran }}" min="0">
                    </td>
                    <td class="py-3 px-4 text-right">
                        <input type="number" class="w-32 border rounded px-2 py-1 text-sm text-right" 
                            id="realisasi_keuangan_{{ $rincian->id }}" 
                            value="0" min="0">
                    </td>
                    <td class="py-3 px-4 text-right">
                        <input type="number" class="w-24 border rounded px-2 py-1 text-sm text-right" 
                            id="target_fisik_{{ $rincian->id }}" 
                            value="100" min="0" max="100">
                    </td>
                    <td class="py-3 px-4 text-right">
                        <input type="number" class="w-24 border rounded px-2 py-1 text-sm text-right" 
                            id="realisasi_fisik_{{ $rincian->id }}" 
                            value="0" min="0" max="100">
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="simpanRealisasi({{ $rincian->id }})" 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-save mr-1"></i>Simpan
                        </button>
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
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('filterTahun').addEventListener('change', function() {
    updateFilter();
});

document.getElementById('filterBulan').addEventListener('change', function() {
    updateFilter();
});

function updateFilter() {
    const tahun = document.getElementById('filterTahun').value;
    const bulan = document.getElementById('filterBulan').value;
    window.location.href = `/realisasi/input?tahun=${tahun}&bulan=${bulan}`;
}

async function simpanRealisasi(rincianId) {
    const tahun = document.getElementById('filterTahun').value;
    const bulan = document.getElementById('filterBulan').value;
    
    const data = {
        rincian_kegiatan_id: rincianId,
        tahun: parseInt(tahun),
        bulan: parseInt(bulan),
        target_keuangan: parseFloat(document.getElementById(`target_keuangan_${rincianId}`).value),
        realisasi_keuangan: parseFloat(document.getElementById(`realisasi_keuangan_${rincianId}`).value),
        target_fisik: parseFloat(document.getElementById(`target_fisik_${rincianId}`).value),
        realisasi_fisik: parseFloat(document.getElementById(`realisasi_fisik_${rincianId}`).value)
    };

    try {
        const response = await fetch('/realisasi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            alert('Realisasi berhasil disimpan!');
        } else {
            const error = await response.json();
            alert('Error: ' + JSON.stringify(error));
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}
</script>
@endpush
