<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfilLulusan;

class ProfilLulusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profilLulusan = [
            [
                'nama_profil' => 'Studi Lanjut',
                'deskripsi' => 'Lulusan yang melanjutkan pendidikan ke jenjang yang lebih tinggi (S2/S3) untuk mengembangkan keahlian dan kompetensi akademik di bidang teknik industri atau bidang terkait.',
                'jumlah' => 0,
                'is_active' => true,
            ],
            [
                'nama_profil' => 'Pegawai Profesional',
                'deskripsi' => 'Lulusan yang bekerja sebagai profesional di berbagai sektor industri, pemerintahan, atau organisasi dengan menerapkan keahlian teknik industri dalam dunia kerja.',
                'jumlah' => 0,
                'is_active' => true,
            ],
            [
                'nama_profil' => 'Kewirausahaan',
                'deskripsi' => 'Lulusan yang mengembangkan jiwa entrepreneurship dengan menciptakan usaha mandiri, startup, atau inovasi bisnis yang menerapkan prinsip-prinsip teknik industri.',
                'jumlah' => 0,
                'is_active' => true,
            ]
        ];

        foreach ($profilLulusan as $profil) {
            ProfilLulusan::create($profil);
        }
    }
} 