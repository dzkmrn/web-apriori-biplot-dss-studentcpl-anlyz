<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cpl;
use App\Models\ProfilLulusan;
use App\Models\Mahasiswa;
use App\Models\DataHistori;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create CPLs
        $cpls = [
            ['kode_cpl' => 'CPL01', 'kategori' => 'Sikap', 'deskripsi' => 'Bertakwa kepada Tuhan Yang Maha Esa dan mampu menunjukkan sikap religius'],
            ['kode_cpl' => 'CPL02', 'kategori' => 'Sikap', 'deskripsi' => 'Menjunjung tinggi nilai kemanusiaan dalam menjalankan tugas'],
            ['kode_cpl' => 'CPL03', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai konsep teoritis bidang pengetahuan tertentu secara umum'],
            ['kode_cpl' => 'CPL04', 'kategori' => 'Pengetahuan', 'deskripsi' => 'Menguasai konsep teoritis bagian khusus dalam bidang pengetahuan tersebut secara mendalam'],
            ['kode_cpl' => 'CPL05', 'kategori' => 'Keterampilan Umum', 'deskripsi' => 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif'],
            ['kode_cpl' => 'CPL06', 'kategori' => 'Keterampilan Khusus', 'deskripsi' => 'Mampu menganalisis data dan informasi berdasarkan logika ilmiah'],
        ];

        foreach ($cpls as $cpl) {
            Cpl::create(array_merge($cpl, ['is_active' => true]));
        }

        // Create Profil Lulusan
        $profilLulusans = [
            [
                'nama_profil' => 'Data Scientist',
                'deskripsi' => 'Lulusan yang mampu menganalisis data besar dan menghasilkan insight untuk pengambilan keputusan bisnis. Memiliki kemampuan dalam machine learning, statistika, dan visualisasi data.',
                'jumlah' => 10
            ],
            [
                'nama_profil' => 'Software Engineer',
                'deskripsi' => 'Lulusan yang mampu merancang, mengembangkan, dan memelihara sistem perangkat lunak. Menguasai berbagai bahasa pemrograman dan metodologi pengembangan software.',
                'jumlah' => 15
            ],
            [
                'nama_profil' => 'System Analyst',
                'deskripsi' => 'Lulusan yang mampu menganalisis kebutuhan sistem informasi dan merancang solusi teknologi yang tepat untuk organisasi.',
                'jumlah' => 8
            ]
        ];

        foreach ($profilLulusans as $profil) {
            ProfilLulusan::create($profil);
        }

        // Create sample Mahasiswa (skip - data sudah ada dari import sebelumnya)
        // Data mahasiswa sudah tersedia dari import Excel sebelumnya

        // Create sample DataHistori
        DataHistori::create([
            'tanggal' => now(),
            'angkatan' => 2019,
            'deskripsi' => 'Analisis CPL Angkatan 2019 - Test Data',
            'hasil_analisis' => [
                'total_transactions' => 2,
                'rules_1to1' => [],
                'rules_2to1' => []
            ],
            'min_support' => 0.05,
            'min_confidence' => 0.3,
            'total_rules' => 0,
            'interpretasi' => 'Ini adalah data test untuk keperluan pengujian sistem. Format NIM: 1201190001 (angkatan 19 = 2019).'
        ]);
    }
}
