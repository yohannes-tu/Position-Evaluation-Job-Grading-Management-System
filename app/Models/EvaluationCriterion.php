<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCriterion extends Model
{
    protected $fillable = [
        'evaluation_factor_id',
        'name',
        'description',
        'points',
    ];

    protected $casts = [
        'points' => 'decimal:2',
    ];

    public function factor(): BelongsTo
    {
        return $this->belongsTo(
            EvaluationFactor::class,
            'evaluation_factor_id'
        );
    }
    public function positionEvaluationFactors(): HasMany
{
    return $this->hasMany(
        PositionEvaluationFactor::class
    );
}
}