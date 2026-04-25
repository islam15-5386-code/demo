<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Database\Seeders\Support\BangladeshLmsDataset;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => 'Institute Admin',
            'teacher' => 'Teacher',
            'student' => 'Student',
        ];

        foreach ($roles as $name => $label) {
            Role::query()->updateOrCreate(
                ['name' => $name],
                ['label' => $label, 'guard_name' => 'web']
            );
        }

        Role::query()->whereNotIn('name', array_keys($roles))->delete();

        foreach (BangladeshLmsDataset::permissions() as $name => $label) {
            Permission::query()->updateOrCreate(
                ['name' => $name],
                ['label' => $label, 'guard_name' => 'web']
            );
        }

        $permissionIdsByName = Permission::query()->pluck('id', 'name');

        foreach (BangladeshLmsDataset::rolePermissions() as $roleName => $permissions) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role === null) {
                continue;
            }

            $role->permissions()->sync(
                collect($permissions)
                    ->map(fn (string $permissionName): ?int => $permissionIdsByName[$permissionName] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );
        }
    }
}
