@extends('layouts.app')

@section('title', 'Dashboard - E-Monev')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <!-- Total Bidang -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold mb-1">Total Bidang</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalBidang }}</h3>
                <p class="text-blue-600 text-sm mt-2">
                    <i class="fas fa-building"></i> Unit Kerja
                </p>
            </div>
            <div class="bg-indigo-100 rounded-full p-4">
                <i class="fas fa-building text-indigo-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Kegiatan -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold mb-1">Total Kegiatan</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalKegiatan }}</h3>
                <p class="text-green-600 text-sm mt-2">
                    <i class="fas fa-arrow-up"></i> {{ $kegiatanAktif }} Aktif
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-tasks text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Sub Kegiatan -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold mb-1">Sub Kegiatan</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalSubKegiatan }}</h3>
                <p class="text-blue-600 text-sm mt-2">
                    <i class="fas fa-list"></i> Dari {{ $totalKegiatan }} kegiatan
                </p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fas fa-list-ul text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Rincian -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold mb-1">Rincian Kegiatan</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalRincian }}</h3>
                <p class="text-purple-600 text-sm mt-2">
                    <i class="fas fa-check-circle"></i> {{ $rincianSelesai }} Selesai
                </p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-clipboard-list text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Anggaran -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold mb-1">Total Anggaran</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalAnggaran / 1000000000, 1) }}M</h3>
                <p class="text-orange-600 text-sm mt-2">
                    <i class="fas fa-money-bill-wave"></i> Miliar Rupiah
                </p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-wallet text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Bidang Stats -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Kegiatan per Bidang</h3>
        <div class="space-y-4">
            @foreach($bidangStats as $bidang)
            <div>
                <div class="flex justify-between mb-2">
                    <div>
                        <span class="text-sm font-semibold text-gray-700">{{ $bidang->singkatan }}</span>
                        <p class="text-xs text-gray-500">{{ Str::limit($bidang->nama_bidang, 30) }}</p>
                    </div>
                    <span class="text-sm font-bold text-blue-600">{{ $bidang->kegiatans_count }} kegiatan</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full" 
                        style="width: {{ $totalKegiatan > 0 ? ($bidang->kegiatans_count / $totalKegiatan * 100) : 0 }}%">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Status Kegiatan Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Status Kegiatan</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-600">Draft</span>
                    <span class="text-sm font-bold text-yellow-600">{{ $kegiatanDraft }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ $totalKegiatan > 0 ? ($kegiatanDraft / $totalKegiatan * 100) : 0 }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-600">Aktif</span>
                    <span class="text-sm font-bold text-green-600">{{ $kegiatanAktif }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $totalKegiatan > 0 ? ($kegiatanAktif / $totalKegiatan * 100) : 0 }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-600">Selesai</span>
                    <span class="text-sm font-bold text-gray-600">{{ $kegiatanSelesai }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gray-500 h-3 rounded-full" style="width: {{ $totalKegiatan > 0 ? ($kegiatanSelesai / $totalKegiatan * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Overview -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Progress Overview</h3>
        <div class="flex items-center justify-center">
            <div class="relative w-48 h-48">
                <svg class="transform -rotate-90 w-48 h-48">
                    <circle cx="96" cy="96" r="80" stroke="#e5e7eb" stroke-width="16" fill="none" />
                    <circle cx="96" cy="96" r="80" stroke="url(#gradient)" stroke-width="16" fill="none"
                        stroke-dasharray="{{ 2 * 3.14159 * 80 }}"
                        stroke-dashoffset="{{ 2 * 3.14159 * 80 * (1 - $avgProgress / 100) }}"
                        stroke-linecap="round" />
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-4xl font-bold text-gray-800">{{ number_format($avgProgress, 1) }}%</span>
                    <span class="text-sm text-gray-500">Rata-rata</span>
                </div>
            </div>
        </div>
        <p class="text-center text-gray-600 mt-4">Progress keseluruhan dari semua rincian kegiatan</p>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">Kegiatan Terbaru</h3>
        <a href="{{ route('kegiatan.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-semibold">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Kode</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Nama Kegiatan</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Anggaran</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Sub Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentKegiatan as $kegiatan)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">{{ $kegiatan->kode_kegiatan }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <p class="font-semibold text-gray-800">{{ $kegiatan->nama_kegiatan }}</p>
                        <p class="text-xs text-gray-500">{{ Str::limit($kegiatan->deskripsi, 50) }}</p>
                        @if($kegiatan->bidang)
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-building mr-1"></i>{{ $kegiatan->bidang->singkatan }}
                        </p>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">
                        Rp {{ number_format($kegiatan->anggaran, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($kegiatan->status == 'aktif') bg-green-100 text-green-800
                            @elseif($kegiatan->status == 'selesai') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($kegiatan->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">
                        <i class="fas fa-list-ul mr-1"></i> {{ $kegiatan->sub_kegiatans_count }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
