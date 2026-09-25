<?php

namespace App\Models;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryScale extends Model
{
    protected $fillable = [
        'grade_id',
        'minimum_salary',
        'midpoint_salary',
        'maximum_salary',
        'currency',
        'effective_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'minimum_salary' => 'decimal:2',
        'midpoint_salary' => 'decimal:2',
        'maximum_salary' => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(
            Grade::class,
            'grade_id'
            );
    }
}