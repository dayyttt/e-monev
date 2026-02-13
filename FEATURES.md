# Fitur E-Monev System

## 1. Drag and Drop

### Reordering dalam satu parent
- Drag kegiatan untuk mengubah urutan kegiatan
- Drag sub kegiatan untuk mengubah urutan dalam satu kegiatan
- Drag rincian kegiatan untuk mengubah urutan dalam satu sub kegiatan

### Nested Drag and Drop
- **Sub Kegiatan**: Bisa dipindah ke kegiatan lain dengan drag and drop
- **Rincian Kegiatan**: Bisa dipindah ke sub kegiatan lain dengan drag and drop

Contoh:
```
Kegiatan A
  ├─ Sub Kegiatan 1
  └─ Sub Kegiatan 2

Kegiatan B
  └─ Sub Kegiatan 3

Drag "Sub Kegiatan 2" ke "Kegiatan B" →

Kegiatan A
  └─ Sub Kegiatan 1

Kegiatan B
  ├─ Sub Kegiatan 3
  └─ Sub Kegiatan 2
```

## 2. Master Data Management

### Kegiatan
- Kode Kegiatan (unique)
- Nama Kegiatan
- Deskripsi
- Anggaran
- Status (Draft/Aktif/Selesai)

### Sub Kegiatan
- Kode Sub Kegiatan (unique)
- Nama Sub Kegiatan
- Deskripsi
- Anggaran
- Status (Draft/Aktif/Selesai)
- Relasi ke Kegiatan

### Rincian Kegiatan
- Kode Rincian (unique)
- Nama Rincian
- Deskripsi
- Anggaran
- Progress (0-100%)
- Status (Draft/Aktif/Selesai)
- Relasi ke Sub Kegiatan

## 3. Status Tracking

Setiap level memiliki status:
- **Draft**: Masih dalam perencanaan
- **Aktif**: Sedang berjalan
- **Selesai**: Sudah selesai

Status ditampilkan dengan badge berwarna:
- Draft: Kuning
- Aktif: Hijau
- Selesai: Abu-abu

## 4. Progress Monitoring

Rincian Kegiatan memiliki field progress (0-100%) untuk tracking kemajuan pelaksanaan.

## 5. Manajemen Anggaran

- Setiap level memiliki field anggaran
- Format: Rupiah (Rp)
- Validasi: Minimal 0

## 6. Expand/Collapse

- Klik icon chevron untuk expand/collapse sub items
- Memudahkan navigasi data yang banyak

## 7. Responsive Design

- Menggunakan TailwindCSS
- Tampilan responsive untuk berbagai ukuran layar

## 8. Real-time Update

- Perubahan urutan langsung tersimpan ke database
- Reload otomatis setelah CRUD operation

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Database**: MySQL
- **Frontend**: Blade Template, TailwindCSS
- **Drag and Drop**: SortableJS
- **Icons**: Font Awesome 6

## API Response Format

Semua API endpoint mengembalikan JSON response:

### Success Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "kode_kegiatan": "KEG-001",
    "nama_kegiatan": "Nama Kegiatan",
    ...
  }
}
```

### Error Response
```json
{
  "message": "Error message",
  "errors": {
    "field_name": ["Error detail"]
  }
}
```

## Validasi

### Kegiatan
- kode_kegiatan: required, unique
- nama_kegiatan: required
- anggaran: required, numeric, min:0
- status: required, in:draft,aktif,selesai

### Sub Kegiatan
- kegiatan_id: required, exists
- kode_sub_kegiatan: required, unique
- nama_sub_kegiatan: required
- anggaran: required, numeric, min:0
- status: required, in:draft,aktif,selesai

### Rincian Kegiatan
- sub_kegiatan_id: required, exists
- kode_rincian: required, unique
- nama_rincian: required
- anggaran: required, numeric, min:0
- progress: required, numeric, min:0, max:100
- status: required, in:draft,aktif,selesai

## Cascade Delete

- Hapus Kegiatan → Otomatis hapus semua Sub Kegiatan dan Rincian Kegiatan
- Hapus Sub Kegiatan → Otomatis hapus semua Rincian Kegiatan

## Future Enhancement Ideas

- [ ] Dashboard dengan chart dan statistik
- [ ] Export data ke Excel/PDF
- [ ] Import data dari Excel
- [ ] User authentication dan authorization
- [ ] Activity log
- [ ] Notifikasi deadline
- [ ] File attachment untuk setiap item
- [ ] Komentar dan diskusi
- [ ] Timeline view
- [ ] Gantt chart
- [ ] Budget vs Actual comparison
- [ ] Multi-year planning
