<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapping kategori lama ke kategori baru yang standar
        $categoryMapping = [
            // Kategori 1: Penguasaan dan penerapan ilmu dasar sains dan matematik
            'Penguasaan dan Penerapan Ilmu Dasar Sains dan Matematik' => 'Penguasaan dan penerapan ilmu dasar sains dan matematik',
            
            // Kategori 2: Kemampuan perumusan solusi permasalahan pada objek Teknik Industri  
            'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' => 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri',
            
            // Kategori 3: Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri
            'Sistem Terintegrasi' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
            'Kemampuan Perancangan dan Penelitian pada Objek Sistem Terintegrasi' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
            'Teknologi dan Komunikasi' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
            'Kemampuan Penguasaan Teknik Umum dan TIK dalam upaya implementasi Keilmuan Teknik Industri' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
            'Komunikasi dan Kepemimpinan' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri',
            
            // Kategori 4: Penguasaan aspek non-akademis pendukung
            'Penguasaan Aspek Non-Akademis Pendukung' => 'Penguasaan aspek non-akademis pendukung',
            
            // Kategori 5: Penguasaan keilmuaan pendukung kewirausahaan
            'Penguasaan Keilmuan Pendukung Kewirausahaan' => 'Penguasaan keilmuaan pendukung kewirausahaan',
        ];

        // Update kategori di database
        foreach ($categoryMapping as $oldCategory => $newCategory) {
            DB::table('cpls')
                ->where('kategori', $oldCategory)
                ->update(['kategori' => $newCategory]);
        }

        // Log hasil
        echo "Kategori CPL telah distandarisasi ke 5 kategori utama:\n";
        echo "1. Penguasaan dan penerapan ilmu dasar sains dan matematik\n";
        echo "2. Kemampuan perumusan solusi permasalahan pada objek Teknik Industri\n";
        echo "3. Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri\n";
        echo "4. Penguasaan aspek non-akademis pendukung\n";
        echo "5. Penguasaan keilmuaan pendukung kewirausahaan\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tidak dilakukan karena akan merusak data
        echo "Rollback tidak dilakukan untuk menjaga integritas data.\n";
    }
};
