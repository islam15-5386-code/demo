<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope — automatically filters queries to the currently resolved tenant.
 *
 * Applied as a global scope on all tenant-owned models. The current tenant ID
 * is resolved by ResolveTenant middleware and stored in the Laravel service
 * container as 'current_tenant_id'. When no tenant is resolved (e.g. during
 * seeding or artisan commands), the scope is bypassed automatically.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->currentTenantId();

        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }

    public static function currentTenantId(): ?int
    {
        try {
            $resolved = app('current_tenant_id');

            if (is_int($resolved) || (is_string($resolved) && ctype_digit($resolved))) {
                return (int) $resolved;
            }
        } catch (\Throwable) {
            // Not bound — running outside of a tenant HTTP context (CLI, tests, seeding)
        }

        return null;
    }
}
