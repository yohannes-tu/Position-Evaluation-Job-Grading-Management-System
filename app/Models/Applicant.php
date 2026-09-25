<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    protected $fillable = [
        'application_account_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'national_id',
        'address',
        'city',
        'region',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name
        );
    }
}