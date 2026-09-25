<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id',
        'document_type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}