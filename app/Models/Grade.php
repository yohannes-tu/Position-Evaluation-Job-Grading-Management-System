<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'name',
        'code',
        'min_score',
        'max_score',
        'description',
        'is_active',
    ];

    protected $casts = [
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function salaryScales()
{
    return $this->hasMany(
        SalaryScale::class,
        'grade_id'
    );
}
}