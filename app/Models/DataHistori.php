<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssociationRule;

class DataHistori extends Model
{
    use HasFactory;

    protected $table = 'data_histori';

    protected $fillable = [
        'tanggal',
        'angkatan',
        'deskripsi',
        'hasil_analisis',
        'min_support',
        'min_confidence',
        'total_rules'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'hasil_analisis' => 'array',
        'angkatan' => 'integer',
        'min_support' => 'float',
        'min_confidence' => 'float',
        'total_rules' => 'integer',
    ];

    public function associationRules()
    {
        return $this->hasMany(AssociationRule::class);
    }

    public function getRules1to1Attribute()
    {
        return $this->associationRules()->where('rule_type', '1to1')->get();
    }

    public function getRules2to1Attribute()
    {
        return $this->associationRules()->where('rule_type', '2to1')->get();
    }
}
