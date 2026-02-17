<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_histori_id',
        'lhs',
        'rhs',
        'support',
        'confidence',
        'lift',
        'rule_type',
        'interpretation',
        'profil'
    ];

    protected $casts = [
        'lhs' => 'array',
        'rhs' => 'array',
        'support' => 'float',
        'confidence' => 'float',
        'lift' => 'float',
    ];

    public function dataHistori()
    {
        return $this->belongsTo(DataHistori::class);
    }

    public function getRuleStringAttribute()
    {
        $lhs_str = implode(' AND ', $this->lhs);
        $rhs_str = implode(' AND ', $this->rhs);
        return "{$lhs_str} => {$rhs_str}";
    }

    public function getInterpretationTextAttribute()
    {
        if ($this->lift > 1) {
            return "Asosiasi positif (Lift > 1): Terdapat hubungan yang kuat";
        } elseif ($this->lift < 1) {
            return "Asosiasi negatif (Lift < 1): Hubungan lemah atau berlawanan";
        } else {
            return "Tidak ada asosiasi yang signifikan (Lift = 1)";
        }
    }

    public function scopeByRuleType($query, $type)
    {
        return $query->where('rule_type', $type);
    }

    public function scopeOrderByConfidence($query, $direction = 'desc')
    {
        return $query->orderBy('confidence', $direction);
    }

    public function scopeOrderByLift($query, $direction = 'desc')
    {
        return $query->orderBy('lift', $direction);
    }
}
