<?php

namespace App\Models;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Vacancy;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
    'department_id',
    'title',
    'code',
    'description',
    'responsibilities',
    'education',
    'experience',
    'employment_type',
    'is_active',
    'status',
    'approved_grade_id',
    'approved_by',
    'approved_at',
    'approval_notes',
];

    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function vacancies(): HasMany
{
    return $this->hasMany(Vacancy::class);
}

    /**
     * Get the department that owns the position.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    

    /**
     * Get the evaluations for the position.
     */
    

    public function evaluations(): HasMany
{
    return $this->hasMany(PositionEvaluation::class);
}
 public function approvedGrade()
{
    return $this->belongsTo(
        \App\Models\Grade::class,
        'approved_grade_id'
    );
}

public function approver()
{
    return $this->belongsTo(
        \App\Models\User::class,
        'approved_by'
    );
}
}