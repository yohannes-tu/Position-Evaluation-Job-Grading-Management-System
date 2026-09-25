<?php

namespace App\Models;
use App\Models\Position;
use App\Models\Application;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends Model
{
    protected $fillable = [
        'position_id',
        'vacancy_code',
        'number_of_openings',
        'posting_date',
        'closing_date',
        'employment_type',
        'location',
        'show_salary',
        'description',
        'application_instructions',
        'required_documents',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
        'closed_at',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'closing_date' => 'date',
        'show_salary' => 'boolean',
        'required_documents' => 'array',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Position associated with this vacancy.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * User who approved the vacancy.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Applications submitted for this vacancy.
     *

     */
    public function application(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Check whether the vacancy is publicly visible.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check whether applications are still open.
     */
    public function isOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if (!$this->closing_date) {
            return true;
        }

        return now()->startOfDay()->lte($this->closing_date);
    }

    public function applications(): HasMany
{
    return $this->hasMany(Application::class);
}
public function hiringRecords(): HasMany
{
    return $this->hasMany(HiringRecord::class);
}
}