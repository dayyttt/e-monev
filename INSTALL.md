# Panduan Instalasi E-Monev

## Quick Start

Jalankan perintah berikut secara berurutan:

```bash
# 1. Masuk ke folder project
cd e-monev

# 2. Install dependencies (sudah dilakukan)
# composer install

# 3. Generate application key
php artisan key:generate

# 4. Buat database MySQL
# Buka MySQL dan jalankan:
# CREATE DATABASE e_monev;

# 5. Update file .env
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai konfigurasi MySQL Anda

# 6. Jalankan migration
php artisan migrate

# 7. (Optional) Isi data contoh
php artisan db:seed --class=KegiatanSeeder

# 8. Jalankan server
php artisan serve
```

Buka browser: **http://localhost:8000**

## Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_monev
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan dengan konfigurasi MySQL Anda.

## Troubleshooting

### Error: SQLSTATE[HY000] [1049] Unknown database
**Solusi:** Buat database terlebih dahulu
```sql
CREATE DATABASE e_monev;
```

### Error: Access denied for user
**Solusi:** Periksa username dan password MySQL di file `.env`

### Error: Class 'KegiatanSeeder' not found
**Solusi:** Jalankan `composer dump-autoload`

## Fitur yang Tersedia

✅ Drag and drop untuk reordering
✅ Nested drag and drop (pindah antar parent)
✅ CRUD lengkap untuk semua master data
✅ Status tracking (Draft, Aktif, Selesai)
✅ Progress monitoring
✅ Manajemen anggaran

Selamat menggunakan E-Monev! 🚀
