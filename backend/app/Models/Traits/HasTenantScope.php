<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * HasTenantScope — mixin for tenant-owned Eloquent models.
 *
 * Registers the TenantScope global scope and provides a convenience
 * `withoutTenantScope()` method for admin/seeder operations that
 * intentionally need to bypass isolation.
 */
trait HasTenantScope
{
    public static function bootHasTenantScope(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    /**
     * Return a new query builder with the tenant scope removed.
     * Use this ONLY in admin/seeder/super-admin contexts.
     */
    public static function withoutTenantScope(): Builder
    {
        return static::withoutGlobalScope(TenantScope::class);
    }
}
