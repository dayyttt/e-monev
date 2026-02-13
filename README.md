# E-Monev System

Sistem Electronic Monitoring and Evaluation untuk mengelola master data Kegiatan, Sub Kegiatan, dan Rincian Kegiatan dengan fitur drag and drop.

## Fitur Utama

- ✅ CRUD Master Data Kegiatan
- ✅ CRUD Master Data Sub Kegiatan
- ✅ CRUD Master Data Rincian Kegiatan
- ✅ Drag and Drop untuk reordering
- ✅ Nested drag and drop (pindah sub kegiatan ke kegiatan lain, rincian ke sub kegiatan lain)
- ✅ Relasi hierarki: Kegiatan → Sub Kegiatan → Rincian Kegiatan
- ✅ Status tracking (Draft, Aktif, Selesai)
- ✅ Progress monitoring untuk rincian kegiatan
- ✅ Manajemen anggaran per level

## Teknologi

- Laravel 10
- MySQL
- TailwindCSS
- SortableJS (untuk drag and drop)
- Font Awesome Icons

## Instalasi

1. Copy file .env
```bash
copy .env.example .env
```

2. Install dependencies
```bash
composer install
```

3. Generate application key
```bash
php artisan key:generate
```

4. Buat database MySQL dengan nama `e_monev`

5. Update konfigurasi database di file `.env`:
```
DB_DATABASE=e_monev
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migration
```bash
php artisan migrate
```

7. (Optional) Jalankan seeder untuk data contoh
```bash
php artisan db:seed --class=KegiatanSeeder
```

8. Jalankan server
```bash
php artisan serve
```

9. Buka browser: http://localhost:8000

## Struktur Database

### Tabel: kegiatans
- id
- kode_kegiatan (unique)
- nama_kegiatan
- deskripsi
- anggaran
- order (untuk sorting)
- status (draft/aktif/selesai)
- timestamps

### Tabel: sub_kegiatans
- id
- kegiatan_id (foreign key)
- kode_sub_kegiatan (unique)
- nama_sub_kegiatan
- deskripsi
- anggaran
- order (untuk sorting)
- status (draft/aktif/selesai)
- timestamps

### Tabel: rincian_kegiatans
- id
- sub_kegiatan_id (foreign key)
- kode_rincian (unique)
- nama_rincian
- deskripsi
- anggaran
- order (untuk sorting)
- progress (0-100%)
- status (draft/aktif/selesai)
- timestamps

## Cara Penggunaan

### Menambah Data
1. Klik tombol "Tambah Kegiatan" untuk menambah kegiatan baru
2. Klik tombol "Tambah Sub Kegiatan" di dalam kegiatan untuk menambah sub kegiatan
3. Klik tombol "Tambah Rincian" di dalam sub kegiatan untuk menambah rincian kegiatan

### Drag and Drop
1. Klik dan tahan icon grip (☰) di sebelah kiri item
2. Drag ke posisi yang diinginkan
3. Lepas untuk menyimpan urutan baru
4. Untuk nested drag: drag sub kegiatan ke kegiatan lain, atau rincian ke sub kegiatan lain

### Edit dan Hapus
1. Klik icon pensil untuk edit
2. Klik icon trash untuk hapus
3. Klik icon chevron untuk expand/collapse sub items

## API Endpoints

### Kegiatan
- GET `/kegiatan` - List semua kegiatan
- POST `/kegiatan` - Tambah kegiatan baru
- PUT `/kegiatan/{id}` - Update kegiatan
- DELETE `/kegiatan/{id}` - Hapus kegiatan
- POST `/kegiatan/reorder` - Reorder kegiatan

### Sub Kegiatan
- POST `/sub-kegiatan` - Tambah sub kegiatan
- PUT `/sub-kegiatan/{id}` - Update sub kegiatan
- DELETE `/sub-kegiatan/{id}` - Hapus sub kegiatan
- POST `/sub-kegiatan/reorder` - Reorder sub kegiatan

### Rincian Kegiatan
- POST `/rincian-kegiatan` - Tambah rincian kegiatan
- PUT `/rincian-kegiatan/{id}` - Update rincian kegiatan
- DELETE `/rincian-kegiatan/{id}` - Hapus rincian kegiatan
- POST `/rincian-kegiatan/reorder` - Reorder rincian kegiatan

## Lisensi

Open source - silakan digunakan dan dikembangkan sesuai kebutuhan.
