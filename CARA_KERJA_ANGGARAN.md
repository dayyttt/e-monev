# Cara Kerja Anggaran & Realisasi di E-Monev

## Konsep Dasar

Sistem E-Monev menggunakan pendekatan **Bottom-Up** untuk menghitung anggaran dan realisasi.

## Struktur Hierarki

```
Bidang
  └─ Kegiatan
      └─ Sub Kegiatan
          └─ Rincian Kegiatan (INPUT MANUAL DI SINI)
              └─ Realisasi Bulanan (INPUT MANUAL DI SINI)
```

## Cara Kerja ANGGARAN (Bottom-Up)

### 1. Input Manual di Level Paling Bawah
User **HANYA** input anggaran di **Rincian Kegiatan**

Contoh:
- Rincian 1: Rp 1.000.000
- Rincian 2: Rp 2.000.000
- Rincian 3: Rp 1.500.000

### 2. Sistem Otomatis SUM ke Atas

**Sub Kegiatan** = SUM(Rincian Kegiatan)
```
Sub Kegiatan = 1.000.000 + 2.000.000 + 1.500.000 = Rp 4.500.000
```

**Kegiatan** = SUM(Sub Kegiatan)
```
Kegiatan = Sub1 + Sub2 + Sub3
```

**Bidang** = SUM(Kegiatan)
```
Bidang = Kegiatan1 + Kegiatan2 + Kegiatan3
```

### 3. Kapan Auto-Calculate Terjadi?

- Saat tambah Rincian Kegiatan baru
- Saat update anggaran Rincian Kegiatan
- Saat hapus Rincian Kegiatan
- Manual klik tombol "Recalculate Anggaran"

## Cara Kerja REALISASI (Bottom-Up)

### 1. Input Manual di Rincian Kegiatan
User input realisasi per bulan di **Rincian Kegiatan**

Contoh Rincian 1:
- Januari: Rp 100.000
- Februari: Rp 150.000
- Maret: Rp 200.000

### 2. Sistem Otomatis SUM ke Atas

**Total Realisasi Rincian** = SUM(Realisasi Bulanan)
```
Rincian 1 = 100.000 + 150.000 + 200.000 = Rp 450.000
```

**Total Realisasi Sub Kegiatan** = SUM(Total Realisasi Rincian)
```
Sub Kegiatan = Rincian1 + Rincian2 + Rincian3
```

**Total Realisasi Kegiatan** = SUM(Total Realisasi Sub Kegiatan)
```
Kegiatan = Sub1 + Sub2 + Sub3
```

**Total Realisasi Bidang** = SUM(Total Realisasi Kegiatan)
```
Bidang = Kegiatan1 + Kegiatan2 + Kegiatan3
```

### 3. Kapan Auto-Calculate Terjadi?

- Saat save realisasi baru
- Saat update realisasi
- Saat hapus realisasi
- Manual klik tombol "Recalculate" di laporan

## Command Manual

### Recalculate Anggaran
```bash
php artisan anggaran:recalculate
```
Menghitung ulang anggaran dari Rincian → Sub → Kegiatan → Bidang

### Recalculate Realisasi
```bash
php artisan realisasi:recalculate
```
Menghitung ulang realisasi dari Realisasi → Rincian → Sub → Kegiatan → Bidang

## Contoh Lengkap

### Input Data:

**Rincian Kegiatan A1:**
- Anggaran: Rp 1.000.000
- Realisasi Jan: Rp 100.000
- Realisasi Feb: Rp 150.000

**Rincian Kegiatan A2:**
- Anggaran: Rp 2.000.000
- Realisasi Jan: Rp 200.000
- Realisasi Feb: Rp 300.000

### Hasil Auto-Calculate:

**Sub Kegiatan A:**
- Anggaran: Rp 3.000.000 (1.000.000 + 2.000.000)
- Realisasi: Rp 750.000 (250.000 + 500.000)

**Kegiatan:**
- Anggaran: SUM dari semua Sub Kegiatan
- Realisasi: SUM dari semua Sub Kegiatan

**Bidang:**
- Anggaran: SUM dari semua Kegiatan
- Realisasi: SUM dari semua Kegiatan

## Keuntungan Sistem Bottom-Up

1. ✅ Data lebih akurat (input di level detail)
2. ✅ Mudah tracking per item
3. ✅ Otomatis aggregate ke atas
4. ✅ Tidak perlu input manual di setiap level
5. ✅ Konsisten dengan e-Monev pemerintah

## Catatan Penting

⚠️ **JANGAN** input anggaran manual di Kegiatan atau Sub Kegiatan!
✅ **HANYA** input anggaran di Rincian Kegiatan
✅ Sistem akan otomatis menghitung ke atas

⚠️ **JANGAN** input realisasi manual di level atas!
✅ **HANYA** input realisasi di Rincian Kegiatan (per bulan)
✅ Sistem akan otomatis menghitung ke atas
