<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\RincianKegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // Kegiatan 1 - Bidang PUPR
        $kegiatan1 = Kegiatan::create([
            'bidang_id' => 1, // PUPR
            'kode_kegiatan' => 'KEG-001',
            'kode_rekening' => '1.01.01',
            'nama_rekening' => 'Belanja Langsung',
            'nama_kegiatan' => 'Pembangunan Infrastruktur Jalan',
            'deskripsi' => 'Pembangunan dan perbaikan jalan di wilayah kabupaten',
            'anggaran' => 5000000000,
            'order' => 0,
            'status' => 'aktif'
        ]);

        $sub1 = SubKegiatan::create([
            'kegiatan_id' => $kegiatan1->id,
            'kode_sub_kegiatan' => 'SUB-001-01',
            'kode_rekening' => '1.01.01.01',
            'nama_rekening' => 'Belanja Modal Jalan',
            'nama_sub_kegiatan' => 'Pembangunan Jalan Utama',
            'deskripsi' => 'Pembangunan jalan utama sepanjang 10 km',
            'anggaran' => 3000000000,
            'order' => 0,
            'status' => 'aktif'
        ]);

        RincianKegiatan::create([
            'sub_kegiatan_id' => $sub1->id,
            'kode_rincian' => 'RIN-001-01-01',
            'kode_rekening' => '5.1.02.01.01',
            'nama_rekening' => 'Belanja Bahan Material',
            'nama_rincian' => 'Pengadaan Material',
            'deskripsi' => 'Pengadaan aspal dan material pendukung',
            'anggaran' => 1500000000,
            'order' => 0,
            'progress' => 75,
            'status' => 'aktif'
        ]);

        RincianKegiatan::create([
            'sub_kegiatan_id' => $sub1->id,
            'kode_rincian' => 'RIN-001-01-02',
            'kode_rekening' => '5.1.02.01.02',
            'nama_rekening' => 'Belanja Jasa Konstruksi',
            'nama_rincian' => 'Pelaksanaan Konstruksi',
            'deskripsi' => 'Pelaksanaan pembangunan jalan',
            'anggaran' => 1500000000,
            'order' => 1,
            'progress' => 50,
            'status' => 'aktif'
        ]);

        $sub2 = SubKegiatan::create([
            'kegiatan_id' => $kegiatan1->id,
            'kode_sub_kegiatan' => 'SUB-001-02',
            'kode_rekening' => '1.01.01.02',
            'nama_rekening' => 'Belanja Pemeliharaan Jalan',
            'nama_sub_kegiatan' => 'Perbaikan Jalan Rusak',
            'deskripsi' => 'Perbaikan jalan rusak di berbagai lokasi',
            'anggaran' => 2000000000,
            'order' => 1,
            'status' => 'aktif'
        ]);

        RincianKegiatan::create([
            'sub_kegiatan_id' => $sub2->id,
            'kode_rincian' => 'RIN-001-02-01',
            'kode_rekening' => '5.1.02.02.01',
            'nama_rekening' => 'Belanja Jasa Survey',
            'nama_rincian' => 'Survey Lokasi',
            'deskripsi' => 'Survey dan pemetaan jalan rusak',
            'anggaran' => 500000000,
            'order' => 0,
            'progress' => 100,
            'status' => 'selesai'
        ]);

        // Kegiatan 2 - Bidang Pendidikan
        $kegiatan2 = Kegiatan::create([
            'bidang_id' => 2, // DIKBUD
            'kode_kegiatan' => 'KEG-002',
            'kode_rekening' => '1.02.01',
            'nama_rekening' => 'Belanja Pendidikan',
            'nama_kegiatan' => 'Peningkatan Kualitas Pendidikan',
            'deskripsi' => 'Program peningkatan kualitas pendidikan di sekolah',
            'anggaran' => 3000000000,
            'order' => 1,
            'status' => 'aktif'
        ]);

        $sub3 = SubKegiatan::create([
            'kegiatan_id' => $kegiatan2->id,
            'kode_sub_kegiatan' => 'SUB-002-01',
            'kode_rekening' => '1.02.01.01',
            'nama_rekening' => 'Belanja Pelatihan',
            'nama_sub_kegiatan' => 'Pelatihan Guru',
            'deskripsi' => 'Pelatihan dan sertifikasi guru',
            'anggaran' => 1500000000,
            'order' => 0,
            'status' => 'aktif'
        ]);

        RincianKegiatan::create([
            'sub_kegiatan_id' => $sub3->id,
            'kode_rincian' => 'RIN-002-01-01',
            'kode_rekening' => '5.2.01.01.01',
            'nama_rekening' => 'Belanja Jasa Workshop',
            'nama_rincian' => 'Workshop Metode Pembelajaran',
            'deskripsi' => 'Workshop metode pembelajaran modern',
            'anggaran' => 750000000,
            'order' => 0,
            'progress' => 30,
            'status' => 'aktif'
        ]);

        RincianKegiatan::create([
            'sub_kegiatan_id' => $sub3->id,
            'kode_rincian' => 'RIN-002-01-02',
            'kode_rekening' => '5.2.01.01.02',
            'nama_rekening' => 'Belanja Sertifikasi',
            'nama_rincian' => 'Sertifikasi Kompetensi',
            'deskripsi' => 'Program sertifikasi kompetensi guru',
            'anggaran' => 750000000,
            'order' => 1,
            'progress' => 10,
            'status' => 'draft'
        ]);

        // Kegiatan 3 - Bidang Kominfo
        $kegiatan3 = Kegiatan::create([
            'bidang_id' => 3, // KOMINFO
            'kode_kegiatan' => 'KEG-003',
            'kode_rekening' => '1.03.01',
            'nama_rekening' => 'Belanja Teknologi Informasi',
            'nama_kegiatan' => 'Pengembangan Sistem E-Government',
            'deskripsi' => 'Digitalisasi layanan pemerintahan',
            'anggaran' => 2000000000,
            'order' => 2,
            'status' => 'draft'
        ]);

        $sub4 = SubKegiatan::create([
            'kegiatan_id' => $kegiatan3->id,
            'kode_sub_kegiatan' => 'SUB-003-01',
            'kode_rekening' => '1.03.01.01',
            'nama_rekening' => 'Belanja Pengembangan Aplikasi',
            'nama_sub_kegiatan' => 'Pengembangan Aplikasi',
            'deskripsi' => 'Pengembangan aplikasi layanan publik',
            'anggaran' => 1200000000,
            'order' => 0,
            'status' => 'draft'
        ]);

        RincianKegiatan::create([
            'sub_kegiatan_id' => $sub4->id,
            'kode_rincian' => 'RIN-003-01-01',
            'kode_rekening' => '5.3.01.01.01',
            'nama_rekening' => 'Belanja Jasa Konsultan',
            'nama_rincian' => 'Analisis Kebutuhan',
            'deskripsi' => 'Analisis kebutuhan sistem',
            'anggaran' => 300000000,
            'order' => 0,
            'progress' => 0,
            'status' => 'draft'
        ]);
    }
}
