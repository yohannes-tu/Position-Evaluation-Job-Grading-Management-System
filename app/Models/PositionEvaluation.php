<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Grade;

class PositionEvaluation extends Model
{
    protected $fillable = [
        'position_id',
        'total_score',
        'status',
        'evaluated_by',
        'evaluated_at',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'evaluated_at' => 'datetime',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
    public function factors(): HasMany
   {
    return $this->hasMany(
        PositionEvaluationFactor::class
    );
    }

    public function getRatingAttribute(): string
{
    $score = (float) $this->total_score;

    if ($score >= 90) {
        return 'Excellent';
    }

    if ($score >= 80) {
        return 'Very Good';
    }

    if ($score >= 70) {
        return 'Good';
    }

    if ($score >= 60) {
        return 'Fair';
    }

    return 'Needs Improvement';
}
public function recommendedGrade()
{
    return Grade::where('is_active', true)
        ->where('min_score', '<=', $this->total_score)
        ->where('max_score', '>=', $this->total_score)
        ->first();
}
}