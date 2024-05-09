<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('secretpassword'),
            'email_verified_at' => now(),
        ]);
        $roles = ['admin', 'user'];

            foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName]);
            $user->assignRole($role);
        }

        $this->call(AccrualSeeder::class);
    }
}
