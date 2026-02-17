<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'nilai_cpl'
    ];

    protected $casts = [
        'nilai_cpl' => 'array',
        'angkatan' => 'integer',
    ];

    public function scopeByAngkatan($query, $angkatan)
    {
        return $query->where('angkatan', $angkatan);
    }

    public function getCategorizedCplAttribute()
    {
        $categorized = [];
        if ($this->nilai_cpl) {
            foreach ($this->nilai_cpl as $cpl => $nilai) {
                $categorized[$cpl] = $this->kategorikanCpl($nilai);
            }
        }
        return $categorized;
    }

    public function kategorikanCpl($nilai)
    {
        if (is_null($nilai) || $nilai === '') {
            return 'Missing';
        } elseif ($nilai > 75) {
            return 'Baik';
        } elseif ($nilai > 60) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }

    /**
     * Alias untuk kategorikanCpl untuk konsistensi naming
     */
    public function categorizeNilai($nilai)
    {
        return $this->kategorikanCpl($nilai);
    }

    public function getRingkasanNilai()
    {
        $summary = [
            'baik' => 0,
            'cukup' => 0,
            'kurang' => 0,
            'missing' => 0
        ];

        if ($this->nilai_cpl) {
            foreach ($this->nilai_cpl as $nilai) {
                $kategori = strtolower($this->kategorikanCpl($nilai));
                if (isset($summary[$kategori])) {
                    $summary[$kategori]++;
                }
            }
        }

        return $summary;
    }

    /**
     * Extract angkatan from NIM
     * Format baru:
     * - 2023+: 102012300355 -> angkatan 23 (2023)
     * - 2022-: 1201220567 -> angkatan 22 (2022)
     */
    public static function extractAngkatanFromNim($nim)
    {
        if (!$nim || strlen($nim) < 8) {
            return null;
        }
        
        // Cek format baru (2023+)
        if (substr($nim, 0, 8) === '10201230') {
            // Format: 102012300355 - tahun 2023+
            return 2023;
        }
        
        // Format lama (2022-)
        if (substr($nim, 0, 4) === '1201') {
            $tahunAngkatan = (int)substr($nim, 4, 2); // Ambil digit ke-5 dan ke-6
            return 2000 + $tahunAngkatan;
        }
        
        return null;
    }

    /**
     * Generate NIM baru berdasarkan angkatan dan nomor urut
     */
    public static function generateNim($angkatan, $nomorUrut)
    {
        if ($angkatan >= 2023) {
            // Format baru untuk 2023+: 102012300355
            $prefix = '10201230';
            $nomor = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
            return $prefix . $nomor;
        } else {
            // Format lama untuk 2022-: 1201220567
            $tahun2digit = substr($angkatan, -2);
            $prefix = '1201' . $tahun2digit;
            $nomor = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
            return $prefix . $nomor;
        }
    }

    /**
     * Validasi format NIM
     */
    public static function validateNimFormat($nim)
    {
        if (!$nim) {
            return false;
        }

        // Format baru 2023+: 102012300355 (12 digit)
        if (preg_match('/^10201230\d{4}$/', $nim)) {
            return true;
        }

        // Format lama 2022-: 1201220567 (10 digit)
        if (preg_match('/^1201\d{6}$/', $nim)) {
            return true;
        }

        return false;
    }

    /**
     * Mutator untuk angkatan - otomatis ekstrak dari NIM jika kosong
     */
    public function setAngkatanAttribute($value)
    {
        if (!$value && $this->nim) {
            $value = self::extractAngkatanFromNim($this->nim);
        }
        
        $this->attributes['angkatan'] = $value;
    }
}
