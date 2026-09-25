<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewScore extends Model
{
    protected $fillable = [
        'interview_id',
        'panel_member_id',
        'interview_criterion_id',
        'criterion',
        'score',
        'weight',
        'weighted_score',
        'comments',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'weight' => 'decimal:2',
        'weighted_score' => 'decimal:2',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(
            Interview::class
        );
    }

    public function panelMember(): BelongsTo
    {
        return $this->belongsTo(
            InterviewPanelMember::class,
            'panel_member_id'
        );
    }

    public function interviewCriterion(): BelongsTo
    {
        return $this->belongsTo(
            InterviewCriterion::class,
            'interview_criterion_id'
        );
    }
}