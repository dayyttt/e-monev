@extends('layouts.app')

@section('title', 'Master Data Kegiatan - E-Monev')
@section('page-title', 'Master Data Kegiatan')

@section('content')
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-1">Kelola Kegiatan</h2>
                    <p class="text-gray-600 text-sm">Drag and drop untuk mengubah urutan atau memindahkan item</p>
                    <p class="text-blue-600 text-xs mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Anggaran dihitung otomatis dari SUM rincian kegiatan (bottom-up)
                    </p>
                </div>
                <div class="flex gap-2">
                    <button onclick="recalculateAnggaran()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-calculator mr-2"></i>Recalculate Anggaran
                    </button>
                    <button onclick="openKegiatanModal()" class="gradient-bg text-white px-6 py-3 rounded-lg hover:opacity-90 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
                    </button>
                </div>
            </div>
        </div>
    </div>

        <div id="kegiatan-list" class="space-y-4">
            @foreach($kegiatans as $kegiatan)
            <div class="kegiatan-item bg-white rounded-lg shadow-md p-6 card-hover border-l-4 border-purple-500" data-id="{{ $kegiatan->id }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start flex-1">
                        <i class="fas fa-grip-vertical text-gray-400 mr-3 mt-1 cursor-move hover:text-purple-600"></i>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-lg text-sm font-bold">{{ $kegiatan->kode_kegiatan }}</span>
                                @if($kegiatan->bidang)
                                <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-building mr-1"></i>{{ $kegiatan->bidang->singkatan }}
                                </span>
                                @endif
                                <span class="px-3 py-1 rounded-lg text-sm font-semibold
                                    @if($kegiatan->status == 'aktif') bg-green-100 text-green-800
                                    @elseif($kegiatan->status == 'selesai') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    <i class="fas fa-circle text-xs mr-1"></i>{{ ucfirst($kegiatan->status) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $kegiatan->nama_kegiatan }}</h3>
                            @if($kegiatan->deskripsi)
                            <p class="text-gray-600 mb-3 text-sm">{{ $kegiatan->deskripsi }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm">
                                <span class="text-gray-500">
                                    <i class="fas fa-wallet text-orange-500 mr-1"></i>
                                    <span class="font-semibold text-gray-700">Rp {{ number_format($kegiatan->anggaran, 0, ',', '.') }}</span>
                                </span>
                                <span class="text-gray-500">
                                    <i class="fas fa-list-ul text-blue-500 mr-1"></i>
                                    <span class="font-semibold text-gray-700">{{ $kegiatan->subKegiatans->count() }} Sub Kegiatan</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick='editKegiatan(@json($kegiatan))' class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteKegiatan({{ $kegiatan->id }})" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button onclick="toggleSubKegiatan({{ $kegiatan->id }})" class="text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>

                <div id="sub-kegiatan-{{ $kegiatan->id }}" class="hidden mt-4 pl-8 border-l-4 border-purple-200">
                    <button onclick="openSubKegiatanModal({{ $kegiatan->id }})" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg mb-4 text-sm shadow-md transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Sub Kegiatan
                    </button>
                    
                    <div class="sub-kegiatan-list space-y-3" data-kegiatan-id="{{ $kegiatan->id }}">
                        @foreach($kegiatan->subKegiatans as $subKegiatan)
                        <div class="sub-kegiatan-item bg-gray-50 rounded-lg p-4" data-id="{{ $subKegiatan->id }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-start flex-1">
                                    <i class="fas fa-grip-vertical text-gray-400 mr-3 mt-1 cursor-move"></i>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">{{ $subKegiatan->kode_sub_kegiatan }}</span>
                                            <span class="px-2 py-1 rounded text-xs font-semibold
                                                @if($subKegiatan->status == 'aktif') bg-green-100 text-green-800
                                                @elseif($subKegiatan->status == 'selesai') bg-gray-100 text-gray-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($subKegiatan->status) }}
                                            </span>
                                        </div>
                                        <h4 class="font-bold text-gray-800 mb-1">{{ $subKegiatan->nama_sub_kegiatan }}</h4>
                                        @if($subKegiatan->deskripsi)
                                        <p class="text-sm text-gray-600 mb-1">{{ $subKegiatan->deskripsi }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">Anggaran: <span class="font-semibold">Rp {{ number_format($subKegiatan->anggaran, 0, ',', '.') }}</span></p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick='editSubKegiatan(@json($subKegiatan))' class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteSubKegiatan({{ $subKegiatan->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button onclick="toggleRincianKegiatan({{ $subKegiatan->id }})" class="text-gray-600 hover:text-gray-800 text-sm">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="rincian-kegiatan-{{ $subKegiatan->id }}" class="hidden mt-3 pl-6 border-l-2 border-green-200">
                                <button onclick="openRincianKegiatanModal({{ $subKegiatan->id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded mb-3 text-xs">
                                    <i class="fas fa-plus mr-1"></i>Tambah Rincian
                                </button>
                                
                                <div class="rincian-kegiatan-list space-y-2" data-sub-kegiatan-id="{{ $subKegiatan->id }}">
                                    @foreach($subKegiatan->rincianKegiatans as $rincian)
                                    <div class="rincian-kegiatan-item bg-white rounded p-3 border border-gray-200" data-id="{{ $rincian->id }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start flex-1">
                                                <i class="fas fa-grip-vertical text-gray-400 mr-2 mt-1 cursor-move text-xs"></i>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-xs font-semibold">{{ $rincian->kode_rincian }}</span>
                                                        <span class="px-2 py-0.5 rounded text-xs font-semibold
                                                            @if($rincian->status == 'aktif') bg-green-100 text-green-800
                                                            @elseif($rincian->status == 'selesai') bg-gray-100 text-gray-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ ucfirst($rincian->status) }}
                                                        </span>
                                                    </div>
                                                    <h5 class="font-semibold text-gray-800 text-sm mb-1">{{ $rincian->nama_rincian }}</h5>
                                                    @if($rincian->deskripsi)
                                                    <p class="text-xs text-gray-600 mb-1">{{ $rincian->deskripsi }}</p>
                                                    @endif
                                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                                        <span>Anggaran: <span class="font-semibold">Rp {{ number_format($rincian->anggaran, 0, ',', '.') }}</span></span>
                                                        <span>Progress: <span class="font-semibold">{{ $rincian->progress }}%</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button onclick='editRincianKegiatan(@json($rincian))' class="text-blue-600 hover:text-blue-800 text-xs">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteRincianKegiatan({{ $rincian->id }})" class="text-red-600 hover:text-red-800 text-xs">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Modal Kegiatan -->
    <div id="kegiatanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="gradient-bg p-6 rounded-t-xl">
                <h2 id="kegiatanModalTitle" class="text-2xl font-bold text-white">Tambah Kegiatan</h2>
            </div>
            <form id="kegiatanForm" class="p-6">
                <input type="hidden" id="kegiatan_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Bidang <span class="text-red-500">*</span></label>
                    <select id="bidang_id" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">{{ $bidang->singkatan }} - {{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Kode Kegiatan</label>
                        <input type="text" id="kode_kegiatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Status</label>
                        <select id="kegiatan_status" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Nama Kegiatan</label>
                    <input type="text" id="nama_kegiatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi_kegiatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" rows="3"></textarea>
                </div>
                
                <!-- Info Anggaran Otomatis -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-800 mb-1">Anggaran Dihitung Otomatis</p>
                            <p class="text-xs text-blue-700">Anggaran kegiatan akan otomatis dihitung dari SUM anggaran sub kegiatan (bottom-up). Tidak perlu input manual.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeKegiatanModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">Batal</button>
                    <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sub Kegiatan -->
    <div id="subKegiatanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-xl">
                <h2 id="subKegiatanModalTitle" class="text-2xl font-bold text-white">Tambah Sub Kegiatan</h2>
            </div>
            <form id="subKegiatanForm" class="p-6">
                <input type="hidden" id="sub_kegiatan_id">
                <input type="hidden" id="parent_kegiatan_id">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Kode Sub Kegiatan</label>
                        <input type="text" id="kode_sub_kegiatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Status</label>
                        <select id="sub_kegiatan_status" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:outline-none transition" required>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Nama Sub Kegiatan</label>
                    <input type="text" id="nama_sub_kegiatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:outline-none transition" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi_sub_kegiatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:outline-none transition" rows="3"></textarea>
                </div>
                
                <!-- Info Anggaran Otomatis -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-800 mb-1">Anggaran Dihitung Otomatis</p>
                            <p class="text-xs text-blue-700">Anggaran sub kegiatan akan otomatis dihitung dari SUM anggaran rincian kegiatan (bottom-up). Tidak perlu input manual.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSubKegiatanModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-2 rounded-lg transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Rincian Kegiatan -->
    <div id="rincianKegiatanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-t-xl">
                <h2 id="rincianKegiatanModalTitle" class="text-2xl font-bold text-white">Tambah Rincian Kegiatan</h2>
            </div>
            <form id="rincianKegiatanForm" class="p-6">
                <input type="hidden" id="rincian_kegiatan_id">
                <input type="hidden" id="parent_sub_kegiatan_id">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Kode Rincian</label>
                        <input type="text" id="kode_rincian" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Status</label>
                        <select id="rincian_status" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Nama Rincian</label>
                    <input type="text" id="nama_rincian" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi_rincian" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" rows="3"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Anggaran (Rp)</label>
                        <input type="number" id="anggaran_rincian" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Progress (%)</label>
                        <input type="number" id="progress_rincian" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-purple-500 focus:outline-none transition" required min="0" max="100">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRincianKegiatanModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-2 rounded-lg transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/emonev.js') }}"></script>
@endpush
