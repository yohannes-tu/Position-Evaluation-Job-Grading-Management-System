<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = [
        'vacancy_id',
        'applicant_id',
        'application_number',
        'submitted_at',
        'status',
        'cover_letter',
        'screening_score',
        'recruiter_notes',
        'shortlisted_at',
        'interview_at',
        'selected_at',
        'rejected_at',
        'rejection_reason',
        'score'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'interview_at' => 'datetime',
        'selected_at' => 'datetime',
        'rejected_at' => 'datetime',
        'screening_score' => 'decimal:2',
        'score' => 'decimal:2',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function interviews(): HasMany
{
    return $this->hasMany(Interview::class);
}
public function hiringRecord(): HasOne
{
    return $this->hasOne(HiringRecord::class);
}

}