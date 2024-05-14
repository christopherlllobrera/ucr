<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DraftbillSeeder;

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
        $this->call(DraftbillSeeder::class);
        $this->call(DraftbilldetailSeeder::class);
        $this->call(DraftbillrelationSeeder::class);
    }
}
