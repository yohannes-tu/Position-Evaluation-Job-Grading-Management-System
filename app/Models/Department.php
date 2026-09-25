<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    /**
 * Get the positions belonging to this department.
 */
public function positions(): HasMany
{
    return $this->hasMany(Position::class);
}
}