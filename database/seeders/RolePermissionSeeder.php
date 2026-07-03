<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage-users',
            'view-doctors',
            'manage-doctors',
            'view-appointments',
            'manage-appointments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(['manage-users', 'view-doctors', 'view-appointments']);

        $receptionist = Role::firstOrCreate(['name' => 'receptionist']);
        $receptionist->syncPermissions(['view-doctors', 'manage-doctors', 'view-appointments']);

        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $doctor->syncPermissions(['view-appointments', 'manage-appointments']);
    }
}
