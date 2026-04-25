<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveClassRecording extends Model
{
    protected $fillable = [
        'tenant_id',
        'live_class_id',
        'recording_url',
        'duration_seconds',
        'duration',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'duration' => 'integer',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function liveClass(): BelongsTo
    {
        return $this->belongsTo(LiveClass::class);
    }
}
