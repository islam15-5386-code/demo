<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'title',
        'audience',
        'type',
        'message',
        'is_read',
        'sent_at',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'sent_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
