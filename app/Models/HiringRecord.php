<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HiringRecord extends Model
{
    protected $fillable = [
        'application_id',
        'vacancy_id',
        'hired_by',
        'hired_at',
        'employment_status',
        'notes',
    ];

    protected $casts = [
        'hired_at' => 'date',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function hiredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hired_by');
    }
}