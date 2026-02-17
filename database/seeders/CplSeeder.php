<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cpl;

class CplSeeder extends Seeder
{
    /**
     * Run the database migrations.
     */
    public function run(): void
    {
        $cpls = [
            // Penguasaan dan Penerapan Ilmu Dasar Sains dan Matematik
            [
                'kode_cpl' => 'CPL 1',
                'deskripsi' => 'Menguasai konsep teoretis sains alam, aplikasi matematika rekayasa; prinsip-prinsip rekayasa (engineering fundamentals), sains rekayasa dan perancangan rekayasa yang diperlukan untuk analisis dan perancangan sistem terintegrasi',
                'kategori' => 'Penguasaan dan Penerapan Ilmu Dasar Sains dan Matematik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 2',
                'deskripsi' => 'Mampu menerapkan matematika, sains, dan prinsip rekayasa (engineering principles) untuk menyelesaikan masalah rekayasa kompleks pada sistem terintegrasi (meliputi manusia, material, peralatan, energi, dan informasi)',
                'kategori' => 'Penguasaan dan Penerapan Ilmu Dasar Sains dan Matematik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Kemampuan perumusan solusi permasalahan pada objek Teknik Industri
            [
                'kode_cpl' => 'CPL 3',
                'deskripsi' => 'Mampu mengidentifikasi, memformulasikan dan menganalisis masalah rekayasa kompleks pada sistem terintegrasi berdasarkan pendekatan analitik, komputasional atau eksperimental',
                'kategori' => 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 4',
                'deskripsi' => 'Mampu merumuskan solusi untuk masalah rekayasa kompleks pada sistem terintegrasi dengan memperhatikan faktor-faktor ekonomi, kesehatan dan keselamatan publik, kultural, sosial dan lingkungan.',
                'kategori' => 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 5',
                'deskripsi' => 'Mampu merancang dan menganalisis solusi untuk masalah teknik industri yang kompleks dengan mempertimbangkan aspek ekonomi, sosial, dan lingkungan.',
                'kategori' => 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri
            [
                'kode_cpl' => 'CPL 6',
                'deskripsi' => 'Mampu merancang dan melaksanakan percobaan di laboratorium dan/atau lapangan terkait sistem terintegrasi sesuai standar teknis, keselamatan dan kesehatan lingkungan yang berlaku',
                'kategori' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 7',
                'deskripsi' => 'Mampu meneliti dan menyelidiki masalah rekayasa kompleks pada sistem terintegrasi menggunakan dasar prinsip-prinsip rekayasa dan dengan melaksanakan riset, analisis, interpretasi data dan sintesa informasi untuk memberikan solusi',
                'kategori' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 8',
                'deskripsi' => 'Menguasai pengetahuan tentang teknik komunikasi dan perkembangan teknologi informasi dan komunikasi (TIK) terbaru dan terkini',
                'kategori' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 9',
                'deskripsi' => 'Mampu memilih sumberdaya dan memanfaatkan perangkat perancangan dan analisis rekayasa berbasis teknologi informasi dan komputasi yang sesuai untuk melakukan aktivitas rekayasa',
                'kategori' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 10',
                'deskripsi' => 'Mampu mengintegrasikan teknologi informasi dalam sistem industri untuk meningkatkan efisiensi dan efektivitas proses produksi.',
                'kategori' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 11',
                'deskripsi' => 'Mampu melakukan komunikasi secara tertulis maupun lisan yang efektif',
                'kategori' => 'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Penguasaan keilmuaan pendukung kewirausahaan
            [
                'kode_cpl' => 'CPL 12',
                'deskripsi' => 'Mampu memanfaatkan pengetahuan dan keterampilan menggunakan teknologi, manajemen serta informasi pasar dan uang untuk mengembangkan sebuah inovasi usaha',
                'kategori' => 'Penguasaan keilmuaan pendukung kewirausahaan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 13',
                'deskripsi' => 'Mampu mengidentifikasi peluang bisnis dan mengembangkan model bisnis yang berkelanjutan',
                'kategori' => 'Penguasaan keilmuaan pendukung kewirausahaan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 14',
                'deskripsi' => 'Mampu mengelola dan memimpin tim dalam lingkungan kewirausahaan yang dinamis',
                'kategori' => 'Penguasaan keilmuaan pendukung kewirausahaan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Penguasaan aspek non-akademis pendukung
            [
                'kode_cpl' => 'CPL 15',
                'deskripsi' => 'Mampu mengenali kebutuhan, dan mengelola pembelajaran diri seumur hidup',
                'kategori' => 'Penguasaan aspek non-akademis pendukung',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_cpl' => 'CPL 16',
                'deskripsi' => 'Kemampuan untuk menunjukkan kinerja mandiri, bermutu, dan terukur dalam mengorganisasikan, menyajikan dan mengevaluasi tugas dengan memperhatikan batasan yang diberikan dan mampu melakukan kerjasama dalam sebuah kelompok kerja yang multikultural dan multidisiplin.',
                'kategori' => 'Penguasaan aspek non-akademis pendukung',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($cpls as $cpl) {
            Cpl::create($cpl);
        }
    }
}
