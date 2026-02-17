<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_cpl',
        'deskripsi',
        'kategori',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function kategoriColor()
    {
        return match($this->kategori) {
            'Penguasaan dan penerapan ilmu dasar sains dan matematik' => 'bg-primary',
            'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' => 'bg-success',
            'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'bg-info',
            'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'bg-warning',
            'Penguasaan aspek non-akademis pendukung' => 'bg-danger',
            'Penguasaan keilmuaan pendukung kewirausahaan' => 'bg-dark',
            // Legacy support untuk backward compatibility
            'Kemampuan perancangan dan penelitian pada objek sistem integrasi' => 'bg-info',
            'Sikap' => 'bg-success',
            'Pengetahuan' => 'bg-info',
            'Keterampilan Umum' => 'bg-warning',
            'Keterampilan Khusus' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
}
