// CSRF Token Setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Initialize Sortable for Kegiatan
const kegiatanList = document.getElementById('kegiatan-list');
if (kegiatanList) {
    new Sortable(kegiatanList, {
        animation: 150,
        handle: '.fa-grip-vertical',
        onEnd: function(evt) {
            const items = Array.from(kegiatanList.children).map(el => el.dataset.id);
            fetch('/kegiatan/reorder', {
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

// Initialize Sortable for Sub Kegiatan
document.querySelectorAll('.sub-kegiatan-list').forEach(list => {
    new Sortable(list, {
        animation: 150,
        handle: '.fa-grip-vertical',
        group: 'sub-kegiatan',
        onEnd: function(evt) {
            const kegiatanId = evt.to.dataset.kegiatanId;
            const items = Array.from(evt.to.children).map(el => el.dataset.id);
            fetch('/sub-kegiatan/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ items, kegiatan_id: kegiatanId })
            });
        }
    });
});

// Initialize Sortable for Rincian Kegiatan
document.querySelectorAll('.rincian-kegiatan-list').forEach(list => {
    new Sortable(list, {
        animation: 150,
        handle: '.fa-grip-vertical',
        group: 'rincian-kegiatan',
        onEnd: function(evt) {
            const subKegiatanId = evt.to.dataset.subKegiatanId;
            const items = Array.from(evt.to.children).map(el => el.dataset.id);
            fetch('/rincian-kegiatan/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ items, sub_kegiatan_id: subKegiatanId })
            });
        }
    });
});

// Toggle Functions
function toggleSubKegiatan(id) {
    const element = document.getElementById(`sub-kegiatan-${id}`);
    element.classList.toggle('hidden');
}

function toggleRincianKegiatan(id) {
    const element = document.getElementById(`rincian-kegiatan-${id}`);
    element.classList.toggle('hidden');
}

// Kegiatan Modal Functions
function openKegiatanModal() {
    document.getElementById('kegiatanModal').classList.remove('hidden');
    document.getElementById('kegiatanModalTitle').textContent = 'Tambah Kegiatan';
    document.getElementById('kegiatanForm').reset();
    document.getElementById('kegiatan_id').value = '';
}

function closeKegiatanModal() {
    document.getElementById('kegiatanModal').classList.add('hidden');
}

function editKegiatan(kegiatan) {
    document.getElementById('kegiatanModal').classList.remove('hidden');
    document.getElementById('kegiatanModalTitle').textContent = 'Edit Kegiatan';
    document.getElementById('kegiatan_id').value = kegiatan.id;
    document.getElementById('bidang_id').value = kegiatan.bidang_id || '';
    document.getElementById('kode_kegiatan').value = kegiatan.kode_kegiatan;
    document.getElementById('nama_kegiatan').value = kegiatan.nama_kegiatan;
    document.getElementById('deskripsi_kegiatan').value = kegiatan.deskripsi || '';
    document.getElementById('kegiatan_status').value = kegiatan.status;
}

document.getElementById('kegiatanForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('kegiatan_id').value;
    const data = {
        bidang_id: document.getElementById('bidang_id').value,
        kode_kegiatan: document.getElementById('kode_kegiatan').value,
        nama_kegiatan: document.getElementById('nama_kegiatan').value,
        deskripsi: document.getElementById('deskripsi_kegiatan').value,
        status: document.getElementById('kegiatan_status').value
    };

    const url = id ? `/kegiatan/${id}` : '/kegiatan';
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

async function deleteKegiatan(id) {
    if (!confirm('Yakin ingin menghapus kegiatan ini?')) return;

    try {
        const response = await fetch(`/kegiatan/${id}`, {
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

// Sub Kegiatan Modal Functions
function openSubKegiatanModal(kegiatanId) {
    document.getElementById('subKegiatanModal').classList.remove('hidden');
    document.getElementById('subKegiatanModalTitle').textContent = 'Tambah Sub Kegiatan';
    document.getElementById('subKegiatanForm').reset();
    document.getElementById('sub_kegiatan_id').value = '';
    document.getElementById('parent_kegiatan_id').value = kegiatanId;
}

function closeSubKegiatanModal() {
    document.getElementById('subKegiatanModal').classList.add('hidden');
}

function editSubKegiatan(subKegiatan) {
    document.getElementById('subKegiatanModal').classList.remove('hidden');
    document.getElementById('subKegiatanModalTitle').textContent = 'Edit Sub Kegiatan';
    document.getElementById('sub_kegiatan_id').value = subKegiatan.id;
    document.getElementById('parent_kegiatan_id').value = subKegiatan.kegiatan_id;
    document.getElementById('kode_sub_kegiatan').value = subKegiatan.kode_sub_kegiatan;
    document.getElementById('nama_sub_kegiatan').value = subKegiatan.nama_sub_kegiatan;
    document.getElementById('deskripsi_sub_kegiatan').value = subKegiatan.deskripsi || '';
    document.getElementById('sub_kegiatan_status').value = subKegiatan.status;
}

document.getElementById('subKegiatanForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('sub_kegiatan_id').value;
    const data = {
        kegiatan_id: document.getElementById('parent_kegiatan_id').value,
        kode_sub_kegiatan: document.getElementById('kode_sub_kegiatan').value,
        nama_sub_kegiatan: document.getElementById('nama_sub_kegiatan').value,
        deskripsi: document.getElementById('deskripsi_sub_kegiatan').value,
        status: document.getElementById('sub_kegiatan_status').value
    };

    const url = id ? `/sub-kegiatan/${id}` : '/sub-kegiatan';
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

async function deleteSubKegiatan(id) {
    if (!confirm('Yakin ingin menghapus sub kegiatan ini?')) return;

    try {
        const response = await fetch(`/sub-kegiatan/${id}`, {
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

// Rincian Kegiatan Modal Functions
function openRincianKegiatanModal(subKegiatanId) {
    document.getElementById('rincianKegiatanModal').classList.remove('hidden');
    document.getElementById('rincianKegiatanModalTitle').textContent = 'Tambah Rincian Kegiatan';
    document.getElementById('rincianKegiatanForm').reset();
    document.getElementById('rincian_kegiatan_id').value = '';
    document.getElementById('parent_sub_kegiatan_id').value = subKegiatanId;
}

function closeRincianKegiatanModal() {
    document.getElementById('rincianKegiatanModal').classList.add('hidden');
}

function editRincianKegiatan(rincian) {
    document.getElementById('rincianKegiatanModal').classList.remove('hidden');
    document.getElementById('rincianKegiatanModalTitle').textContent = 'Edit Rincian Kegiatan';
    document.getElementById('rincian_kegiatan_id').value = rincian.id;
    document.getElementById('parent_sub_kegiatan_id').value = rincian.sub_kegiatan_id;
    document.getElementById('kode_rincian').value = rincian.kode_rincian;
    document.getElementById('nama_rincian').value = rincian.nama_rincian;
    document.getElementById('deskripsi_rincian').value = rincian.deskripsi || '';
    document.getElementById('anggaran_rincian').value = rincian.anggaran;
    document.getElementById('progress_rincian').value = rincian.progress;
    document.getElementById('rincian_status').value = rincian.status;
}

document.getElementById('rincianKegiatanForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('rincian_kegiatan_id').value;
    const data = {
        sub_kegiatan_id: document.getElementById('parent_sub_kegiatan_id').value,
        kode_rincian: document.getElementById('kode_rincian').value,
        nama_rincian: document.getElementById('nama_rincian').value,
        deskripsi: document.getElementById('deskripsi_rincian').value,
        anggaran: document.getElementById('anggaran_rincian').value,
        progress: document.getElementById('progress_rincian').value,
        status: document.getElementById('rincian_status').value
    };

    const url = id ? `/rincian-kegiatan/${id}` : '/rincian-kegiatan';
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

async function deleteRincianKegiatan(id) {
    if (!confirm('Yakin ingin menghapus rincian kegiatan ini?')) return;

    try {
        const response = await fetch(`/rincian-kegiatan/${id}`, {
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

async function recalculateAnggaran() {
    if (!confirm('Recalculate anggaran dari rincian kegiatan ke atas (bottom-up)?')) return;

    try {
        const response = await fetch('/kegiatan/redistribute-anggaran', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (response.ok) {
            alert('Recalculate anggaran berhasil!');
            location.reload();
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}
