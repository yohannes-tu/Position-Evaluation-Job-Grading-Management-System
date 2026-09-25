<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'scheduled_at',
        'interview_type',
        'location',
        'meeting_link',
        'duration_minutes',
        'status',
        'total_score',
        'recommendation',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'total_score' => 'decimal:2',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function panelMembers(): HasMany
    {
        return $this->hasMany(InterviewPanelMember::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(InterviewScore::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}