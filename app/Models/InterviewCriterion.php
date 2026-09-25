<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterviewCriterion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'weight',
        'max_score',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'max_score' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(
            InterviewScore::class,
            'interview_criterion_id'
        );
    }
}