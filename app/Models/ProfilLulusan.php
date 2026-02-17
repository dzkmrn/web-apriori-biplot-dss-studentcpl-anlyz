<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilLulusan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_profil',
        'deskripsi',
        'jumlah',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jumlah' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
