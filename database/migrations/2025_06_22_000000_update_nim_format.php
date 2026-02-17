<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update format NIM sesuai aturan:
     * - Tahun 2023: 102012300355 (10 digit pertama: 1020123003, 2 digit terakhir: nomor urut)
     * - Tahun 2022 kebawah: 1201220567 (12012205, 2 digit terakhir: nomor urut)
     */
    public function up(): void
    {
        // Cek apakah ada data mahasiswa yang perlu diupdate
        $mahasiswas = DB::table('mahasiswas')->get();
        
        foreach ($mahasiswas as $mahasiswa) {
            $nim = $mahasiswa->nim;
            $angkatan = $mahasiswa->angkatan;
            
            // Skip jika sudah sesuai format
            if (strlen($nim) >= 10) {
                continue;
            }
            
            // Generate NIM baru berdasarkan angkatan
            $new_nim = $this->generateNewNim($angkatan, $mahasiswa->id);
            
            // Update NIM
            DB::table('mahasiswas')
                ->where('id', $mahasiswa->id)
                ->update(['nim' => $new_nim]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke format lama jika diperlukan
        // Untuk safety, kita tidak melakukan rollback otomatis
        // karena bisa menyebabkan data loss
    }

    /**
     * Generate NIM baru berdasarkan angkatan
     */
    private function generateNewNim($angkatan, $id)
    {
        $tahun_angkatan = 2000 + $angkatan; // Asumsi angkatan dalam format 2 digit
        
        if ($tahun_angkatan >= 2023) {
            // Format baru untuk 2023+: 102012300355
            // 10201230 (8 digit prefix) + 0355 (4 digit nomor urut)
            $prefix = '10201230';
            $nomor_urut = str_pad($id, 4, '0', STR_PAD_LEFT);
            return $prefix . $nomor_urut;
        } else {
            // Format lama untuk 2022 kebawah: 1201220567
            // 120122 (6 digit berdasarkan tahun) + 0567 (4 digit nomor urut)
            $tahun_2digit = substr($tahun_angkatan, -2);
            $prefix = '1201' . $tahun_2digit;
            $nomor_urut = str_pad($id, 4, '0', STR_PAD_LEFT);
            return $prefix . $nomor_urut;
        }
    }
}; 