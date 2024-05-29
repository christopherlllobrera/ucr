<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('secretpassword'),
            'email_verified_at' => now(),
        ]);

        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@miescor.ph',
            'password' => Hash::make('4Dmi@50.MIESCoR'),
            'email_verified_at' => now(),
        ]);

        $roles = ['admin', 'user'];

            foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName]);
            $user->assignRole($role);
        }

        $permissions = [
        'create-user',
        'update-user',
        'view-user',
        'delete-user',

        'view-logs',

        'create-accrual',
        'update-accrual',
        'view-accrual',
        'delete-accrual',

        'create-draftbill',
        'update-draftbill',
        'view-draftbill',
        'delete-draftbill',

        'create-invoice',
        'update-invoice',
        'view-invoice',
        'delete-invoice',

        'create-collection',
        'update-collection',
        'view-collection',
        'delete-collection',

        'view-any-accrual',
        'view-any-draftbill',
        'view-any-invoice',
        'view-any-collection',

        //add new permissions here and customize/add it in the policies
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::create(['name' => $permissionName]);
            $user->givePermissionTo($permission);
        }

    }
}
