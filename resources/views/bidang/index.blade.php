@extends('layouts.app')

@section('title', 'Master Bidang - E-Monev')
@section('page-title', 'Master Data Bidang')

@section('content')
<div class="mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-1">Kelola Bidang/Unit Kerja</h2>
                <p class="text-gray-600 text-sm">Drag and drop untuk mengubah urutan bidang</p>
            </div>
            <button onclick="openBidangModal()" class="gradient-bg text-white px-6 py-3 rounded-lg hover:opacity-90 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Bidang
            </button>
        </div>
    </div>
</div>

<div id="bidang-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($bidangs as $bidang)
    <div class="bidang-item bg-white rounded-lg shadow-md p-6 card-hover border-t-4 border-blue-500" data-id="{{ $bidang->id }}">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-start flex-1">
                <i class="fas fa-grip-vertical text-gray-400 mr-3 mt-1 cursor-move hover:text-blue-600"></i>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-lg text-xs font-bold">{{ $bidang->kode_bidang }}</span>
                        @if($bidang->singkatan)
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">{{ $bidang->singkatan }}</span>
                        @endif
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($bidang->status == 'aktif') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <i class="fas fa-circle text-xs mr-1"></i>{{ ucfirst($bidang->status) }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $bidang->nama_bidang }}</h3>
                    @if($bidang->deskripsi)
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($bidang->deskripsi, 80) }}</p>
                    @endif
                    
                    @if($bidang->kepala_bidang)
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <p class="text-xs text-gray-500 mb-1">Kepala Bidang</p>
                        <p class="font-semibold text-sm text-gray-800">{{ $bidang->kepala_bidang }}</p>
                        @if($bidang->nip_kepala)
                        <p class="text-xs text-gray-500">NIP: {{ $bidang->nip_kepala }}</p>
                        @endif
                    </div>
                    @endif
                    
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-gray-500">
                            <i class="fas fa-tasks text-blue-500 mr-1"></i>
                            <span class="font-semibold text-gray-700">{{ $bidang->kegiatans_count }} Kegiatan</span>
                        </span>
                        @if($bidang->telepon)
                        <span class="text-gray-500">
                            <i class="fas fa-phone text-green-500 mr-1"></i>
                            <span class="text-xs">{{ $bidang->telepon }}</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex gap-2 pt-3 border-t border-gray-200">
            <a href="/bidang/{{ $bidang->id }}" class="flex-1 text-center bg-blue-50 hover:bg-blue-100 text-blue-600 py-2 rounded-lg transition text-sm font-semibold">
                <i class="fas fa-eye mr-1"></i>Detail
            </a>
            <button onclick='editBidang(@json($bidang))' class="flex-1 bg-green-50 hover:bg-green-100 text-green-600 py-2 rounded-lg transition text-sm font-semibold">
                <i class="fas fa-edit mr-1"></i>Edit
            </button>
            <button onclick="deleteBidang({{ $bidang->id }})" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg transition text-sm">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal Bidang -->
<div id="bidangModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="gradient-bg p-6 rounded-t-xl">
            <h2 id="bidangModalTitle" class="text-2xl font-bold text-white">Tambah Bidang</h2>
        </div>
        <form id="bidangForm" class="p-6">
            <input type="hidden" id="bidang_id">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Kode Bidang</label>
                    <input type="text" id="kode_bidang" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Singkatan</label>
                    <input type="text" id="singkatan" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Nama Bidang</label>
                <input type="text" id="nama_bidang" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Deskripsi</label>
                <textarea id="deskripsi_bidang" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition" rows="3"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Kepala Bidang</label>
                    <input type="text" id="kepala_bidang" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700">NIP Kepala</label>
                    <input type="text" id="nip_kepala" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Telepon</label>
                    <input type="text" id="telepon" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Email</label>
                    <input type="email" id="email" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Status</label>
                <select id="status_bidang" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Non-Aktif</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeBidangModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">Batal</button>
                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Initialize Sortable for Bidang
const bidangList = document.getElementById('bidang-list');
if (bidangList) {
    new Sortable(bidangList, {
        animation: 150,
        handle: '.fa-grip-vertical',
        onEnd: function(evt) {
            const items = Array.from(bidangList.querySelectorAll('.bidang-item')).map(el => el.dataset.id);
            fetch('/bidang/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ items })
            });
        }
    });
}

function openBidangModal() {
    document.getElementById('bidangModal').classList.remove('hidden');
    document.getElementById('bidangModalTitle').textContent = 'Tambah Bidang';
    document.getElementById('bidangForm').reset();
    document.getElementById('bidang_id').value = '';
}

function closeBidangModal() {
    document.getElementById('bidangModal').classList.add('hidden');
}

function editBidang(bidang) {
    document.getElementById('bidangModal').classList.remove('hidden');
    document.getElementById('bidangModalTitle').textContent = 'Edit Bidang';
    document.getElementById('bidang_id').value = bidang.id;
    document.getElementById('kode_bidang').value = bidang.kode_bidang;
    document.getElementById('nama_bidang').value = bidang.nama_bidang;
    document.getElementById('singkatan').value = bidang.singkatan || '';
    document.getElementById('deskripsi_bidang').value = bidang.deskripsi || '';
    document.getElementById('kepala_bidang').value = bidang.kepala_bidang || '';
    document.getElementById('nip_kepala').value = bidang.nip_kepala || '';
    document.getElementById('telepon').value = bidang.telepon || '';
    document.getElementById('email').value = bidang.email || '';
    document.getElementById('status_bidang').value = bidang.status;
}

document.getElementById('bidangForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('bidang_id').value;
    const data = {
        kode_bidang: document.getElementById('kode_bidang').value,
        nama_bidang: document.getElementById('nama_bidang').value,
        singkatan: document.getElementById('singkatan').value,
        deskripsi: document.getElementById('deskripsi_bidang').value,
        kepala_bidang: document.getElementById('kepala_bidang').value,
        nip_kepala: document.getElementById('nip_kepala').value,
        telepon: document.getElementById('telepon').value,
        email: document.getElementById('email').value,
        status: document.getElementById('status_bidang').value
    };

    const url = id ? `/bidang/${id}` : '/bidang';
    const method = id ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            location.reload();
        } else {
            const error = await response.json();
            alert('Error: ' + JSON.stringify(error));
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

async function deleteBidang(id) {
    if (!confirm('Yakin ingin menghapus bidang ini? Kegiatan yang terkait akan kehilangan referensi bidang.')) return;

    try {
        const response = await fetch(`/bidang/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}
</script>
@endpush
