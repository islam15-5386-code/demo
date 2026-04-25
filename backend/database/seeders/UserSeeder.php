<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\Support\BangladeshLmsDataset;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleIds = Role::query()->pluck('id', 'name');
        $demoTenant = Tenant::query()->orderBy('id')->first();

        // Test Login User - Student
        $testStudent = User::query()->updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'tenant_id' => $demoTenant?->id,
                'name' => 'Test Student',
                'phone' => '01700000001',
                'password' => 'password123',
                'role' => 'student',
                'department' => 'CSE',
                'city' => $demoTenant?->city ?? 'Dhaka',
                'address' => $demoTenant?->address ?? 'Test Address',
                'is_active' => true,
            ]
        );

        // Test Login User - Admin
        $testAdmin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'tenant_id' => $demoTenant?->id,
                'name' => 'Test Admin',
                'phone' => '01700000002',
                'password' => 'password123',
                'role' => 'admin',
                'department' => 'Administration',
                'city' => $demoTenant?->city ?? 'Dhaka',
                'address' => $demoTenant?->address ?? 'Test Address',
                'is_active' => true,
            ]
        );

        $testTeacher = User::query()->updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'tenant_id' => $demoTenant?->id,
                'name' => 'Test Teacher',
                'phone' => '01700000003',
                'password' => 'password123',
                'role' => 'teacher',
                'department' => 'Faculty',
                'city' => $demoTenant?->city ?? 'Dhaka',
                'address' => $demoTenant?->address ?? 'Test Address',
                'is_active' => true,
            ]
        );

        if (isset($roleIds['student'])) {
            $testStudent->roles()->sync([$roleIds['student']]);
        }
        if (isset($roleIds['admin'])) {
            $testAdmin->roles()->sync([$roleIds['admin']]);
        }
        if (isset($roleIds['teacher'])) {
            $testTeacher->roles()->sync([$roleIds['teacher']]);
        }

        $nameIndex = 0;

        Tenant::query()->orderBy('id')->each(function (Tenant $tenant) use ($roleIds, &$nameIndex): void {
            $this->createUserForTenant($tenant, 'admin', 'admin', 'Administration', $nameIndex++);

            for ($teacherIndex = 0; $teacherIndex < 4; $teacherIndex++) {
                $this->createUserForTenant($tenant, 'teacher', 'teacher', 'Faculty', $nameIndex++);
            }

            for ($studentIndex = 0; $studentIndex < 28; $studentIndex++) {
                $departments = ['CSE', 'BBA', 'English', 'Textile', 'Marketing', 'Accounts', 'HR', 'Operations'];
                $this->createUserForTenant(
                    $tenant,
                    'student',
                    'student',
                    $departments[($studentIndex + $tenant->id) % count($departments)],
                    $nameIndex++
                );
            }
        });

        User::query()->whereNotIn('role', ['admin', 'teacher', 'student'])->delete();
    }

    private function createUserForTenant(Tenant $tenant, string $userRole, string $roleName, string $department, int $index): User
    {
        $name = BangladeshLmsDataset::fullName($index);

        $user = User::query()->updateOrCreate(
            ['email' => BangladeshLmsDataset::email($name, $tenant->subdomain, $index)],
            [
                'tenant_id' => $tenant->id,
                'name' => $name,
                'phone' => BangladeshLmsDataset::phone($index),
                'password' => 'password',
                'role' => $userRole,
                'department' => $department,
                'city' => $tenant->city,
                'address' => $tenant->address,
                'is_active' => true,
            ]
        );

        $role = Role::query()->where('name', $roleName)->firstOrFail();
        $user->roles()->syncWithoutDetaching([$role->id]);

        return $user;
    }
}
