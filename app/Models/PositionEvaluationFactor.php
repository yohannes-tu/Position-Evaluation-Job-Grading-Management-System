<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PositionEvaluationFactor extends Model
{
    protected $fillable = [
        'position_evaluation_id',
        'evaluation_factor_id',
        'evaluation_criterion_id',
        'score',
        'weight',
        'weighted_score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'weight' => 'decimal:2',
        'weighted_score' => 'decimal:2',
    ];

    public function positionEvaluation(): BelongsTo
    {
        return $this->belongsTo(
            PositionEvaluation::class
        );
    }

    public function evaluationFactor(): BelongsTo
    {
        return $this->belongsTo(
            EvaluationFactor::class
        );
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(
            EvaluationCriterion::class,
            'evaluation_criterion_id'
        );
    }
}