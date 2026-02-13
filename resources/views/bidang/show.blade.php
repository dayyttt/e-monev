@extends('layouts.app')

@section('title', 'Detail Bidang - E-Monev')
@section('page-title', 'Detail Bidang')

@section('content')
<!-- Bidang Header -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-bold">{{ $bidang->kode_bidang }}</span>
                @if($bidang->singkatan)
                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm font-semibold">{{ $bidang->singkatan }}</span>
                @endif
                <span class="px-3 py-1 rounded-lg text-sm font-semibold
                    @if($bidang->status == 'aktif') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800 @endif">
                    <i class="fas fa-circle text-xs mr-1"></i>{{ ucfirst($bidang->status) }}
                </span>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $bidang->nama_bidang }}</h1>
            
            @if($bidang->deskripsi)
            <p class="text-gray-600 mb-4">{{ $bidang->deskripsi }}</p>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @if($bidang->kepala_bidang)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">Kepala Bidang</p>
                    <p class="font-semibold text-gray-800">{{ $bidang->kepala_bidang }}</p>
                    @if($bidang->nip_kepala)
                    <p class="text-xs text-gray-500 mt-1">NIP: {{ $bidang->nip_kepala }}</p>
                    @endif
                </div>
                @endif
                
                @if($bidang->telepon)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">Telepon</p>
                    <p class="font-semibold text-gray-800">
                        <i class="fas fa-phone text-green-500 mr-2"></i>{{ $bidang->telepon }}
                    </p>
                </div>
                @endif
                
                @if($bidang->email)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">Email</p>
                    <p class="font-semibold text-gray-800">
                        <i class="fas fa-envelope text-blue-500 mr-2"></i>{{ $bidang->email }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <a href="{{ route('bidang.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-blue-50 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Kegiatan</p>
                <p class="text-3xl font-bold text-blue-600">{{ $bidang->kegiatans->count() }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-tasks text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-green-50 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Sub Kegiatan</p>
                <p class="text-3xl font-bold text-green-600">{{ $bidang->kegiatans->sum(function($k) { return $k->subKegiatans->count(); }) }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-list-ul text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-orange-50 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Anggaran</p>
                <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($bidang->total_anggaran / 1000000000, 2) }}M</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <i class="fas fa-wallet text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kegiatan -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Kegiatan</h2>
    
    @if($bidang->kegiatans->count() > 0)
    <div class="space-y-4">
        @foreach($bidang->kegiatans as $kegiatan)
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-lg text-xs font-bold">{{ $kegiatan->kode_kegiatan }}</span>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($kegiatan->status == 'aktif') bg-green-100 text-green-800
                            @elseif($kegiatan->status == 'selesai') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($kegiatan->status) }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $kegiatan->nama_kegiatan }}</h3>
                    @if($kegiatan->deskripsi)
                    <p class="text-sm text-gray-600 mb-2">{{ $kegiatan->deskripsi }}</p>
                    @endif
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-gray-500">
                            <i class="fas fa-wallet text-orange-500 mr-1"></i>
                            <span class="font-semibold">Rp {{ number_format($kegiatan->anggaran, 0, ',', '.') }}</span>
                        </span>
                        <span class="text-gray-500">
                            <i class="fas fa-list-ul text-blue-500 mr-1"></i>
                            <span class="font-semibold">{{ $kegiatan->subKegiatans->count() }} Sub Kegiatan</span>
                        </span>
                    </div>
                </div>
                <a href="{{ route('kegiatan.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            @if($kegiatan->subKegiatans->count() > 0)
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-500 mb-2 font-semibold">Sub Kegiatan:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($kegiatan->subKegiatans as $sub)
                    <div class="bg-gray-50 rounded p-2">
                        <p class="text-xs font-semibold text-gray-700">{{ $sub->nama_sub_kegiatan }}</p>
                        <p class="text-xs text-gray-500">{{ $sub->rincianKegiatans->count() }} rincian</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
        <p class="text-gray-500">Belum ada kegiatan untuk bidang ini</p>
    </div>
    @endif
</div>
@endsection
