<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subdomain',
        'city',
        'logo_text',
        'primary_color',
        'secondary_color',
        'accent_color',
        'support_email',
        'contact_email',
        'website',
        'custom_domain',
        'address',
        'phone',
        'logo_url',
        'plan_type',
        'plan_name',
        'seat_limit',
        'status',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'seat_limit' => 'integer',
        ];
    }

    /**
     * Auto-populate slug and plan_name on create/update.
     */
    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            if (empty($tenant->slug) && ! empty($tenant->subdomain)) {
                $tenant->slug = Str::slug($tenant->subdomain);
            }

            if (empty($tenant->plan_name) && ! empty($tenant->plan_type)) {
                $tenant->plan_name = $tenant->plan_type;
            }
        });

        static::updating(function (Tenant $tenant) {
            if (empty($tenant->slug) && ! empty($tenant->subdomain)) {
                $tenant->slug = Str::slug($tenant->subdomain);
            }
        });
    }

    // ─── Relationships ──────────────────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function billingProfile(): HasOne
    {
        return $this->hasOne(BillingProfile::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function liveClasses(): HasMany
    {
        return $this->hasMany(LiveClass::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditEvents(): HasMany
    {
        return $this->hasMany(AuditEvent::class);
    }

    public function complianceRecords(): HasMany
    {
        return $this->hasMany(ComplianceRecord::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->is_active && $this->status !== 'suspended' && $this->status !== 'inactive';
    }

    public function loginUrl(): string
    {
        $base = config('app.frontend_url', 'http://localhost:3000');

        return "{$base}/login?tenant={$this->slug}";
    }
}
