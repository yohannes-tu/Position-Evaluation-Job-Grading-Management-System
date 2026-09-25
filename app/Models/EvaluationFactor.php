<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class EvaluationFactor extends Model
{
    protected $fillable = [
        'name',
        'description',
        'weight',
        'max_score',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'max_score' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    public function criteria(): HasMany
    {
        return $this->hasMany(EvaluationCriterion::class);
    }

    public function positionEvaluationFactors(): HasMany
   {
    return $this->hasMany(
        PositionEvaluationFactor::class
    );
   }
}