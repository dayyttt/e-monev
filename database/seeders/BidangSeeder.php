<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            [
                'kode_bidang' => 'BID-001',
                'nama_bidang' => 'Bidang Pekerjaan Umum dan Penataan Ruang',
                'singkatan' => 'PUPR',
                'deskripsi' => 'Menangani pembangunan infrastruktur, jalan, jembatan, dan penataan ruang',
                'kepala_bidang' => 'Ir. Budi Santoso, M.T.',
                'nip_kepala' => '197505101998031001',
                'telepon' => '021-12345678',
                'email' => 'pupr@example.com',
                'status' => 'aktif',
                'order' => 0
            ],
            [
                'kode_bidang' => 'BID-002',
                'nama_bidang' => 'Bidang Pendidikan dan Kebudayaan',
                'singkatan' => 'DIKBUD',
                'deskripsi' => 'Menangani pendidikan, pelatihan guru, dan pelestarian budaya',
                'kepala_bidang' => 'Dr. Siti Nurhaliza, M.Pd.',
                'nip_kepala' => '198203151999032002',
                'telepon' => '021-87654321',
                'email' => 'dikbud@example.com',
                'status' => 'aktif',
                'order' => 1
            ],
            [
                'kode_bidang' => 'BID-003',
                'nama_bidang' => 'Bidang Komunikasi dan Informatika',
                'singkatan' => 'KOMINFO',
                'deskripsi' => 'Menangani teknologi informasi, e-government, dan komunikasi publik',
                'kepala_bidang' => 'Ahmad Fauzi, S.Kom., M.T.',
                'nip_kepala' => '198907202010011003',
                'telepon' => '021-11223344',
                'email' => 'kominfo@example.com',
                'status' => 'aktif',
                'order' => 2
            ],
            [
                'kode_bidang' => 'BID-004',
                'nama_bidang' => 'Bidang Kesehatan',
                'singkatan' => 'KESEHATAN',
                'deskripsi' => 'Menangani pelayanan kesehatan masyarakat dan program kesehatan',
                'kepala_bidang' => 'dr. Rina Wijaya, Sp.PK.',
                'nip_kepala' => '197812251999032001',
                'telepon' => '021-55667788',
                'email' => 'kesehatan@example.com',
                'status' => 'aktif',
                'order' => 3
            ],
            [
                'kode_bidang' => 'BID-005',
                'nama_bidang' => 'Bidang Pertanian dan Ketahanan Pangan',
                'singkatan' => 'PERTANIAN',
                'deskripsi' => 'Menangani pertanian, peternakan, dan ketahanan pangan',
                'kepala_bidang' => 'Ir. Joko Widodo, M.Si.',
                'nip_kepala' => '197606101998031002',
                'telepon' => '021-99887766',
                'email' => 'pertanian@example.com',
                'status' => 'aktif',
                'order' => 4
            ]
        ];

        foreach ($bidangs as $bidang) {
            Bidang::create($bidang);
        }
    }
}
